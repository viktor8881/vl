<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cron_TmpController
 *
 * @author Viktor Ivanov
 */
class Cron_TmpController extends Core_Controller_Action {
    
//    const DEFAULT_PERCENT = 0.2;
    const STABLE_TREND = 5;
    const PERSENT_BUY = 10;
    const PERSENT_SELL = 20;
    
    const COUNT_RUN_AT_TIME = 90;
    
    
    private $listPercents = [0.2, 0.5, 0.75, 1, 1.5, 2];
    private $pathTmp;
    private $listCurrencyCodes = ['R01235', 'R01239'];




    public function init() {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $this->pathTmp = $bootstrap->getOptions()['path']['temp'];
//        $queue = new Core_Queue_TechnicalAnalysis('TechnicalAnalysis');
//        $queue->sendTaskAnalysisCurrency();
//        exit;
    }
        
    public function indexAction() {
        $queue = new Core_Queue_TechnicalAnalysis('TechnicalAnalysis');
        $messages = $queue->receive();
        foreach ($messages as $message) {
            $body = $message->body;
            switch ($body) {
                case Core_Queue_TechnicalAnalysis::TASK_ANALYSIS_CURRENCY:
                    $this->cacheCurrencyAction();
                    // add task send email.
                    $queue->sendTaskAnalysisMetal();
                    break;
                case Core_Queue_TechnicalAnalysis::TASK_ANALYSIS_METAL:
                    $this->cacheMetalAction();
                    // add task send email.
                    $queue->sendTaskEmail();
                    break;
                case Core_Queue_TechnicalAnalysis::TASK_SEND_MESSAGE:
                    $this->mailAction();
                    $queue->sendTaskAnalysisCurrency();
                    break;
                default:
                    throw new RuntimeException('unknown type task.');
                    break;
            }
            $queue->deleteMessage($message);
        }
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }
    
    public function cacheInitMetalAction() {
        $fileName = $this->pathTmp.'date-metal.tmp';
        $flag = true;
        while($flag) {
            // находим дату
            $date = new Core_Date(file_get_contents($fileName));
            if (!$this->getManager('courseMetal')->hasByDate($date)) {
                $date->add(new DateInterval('P1D'));
                file_put_contents($fileName, $date->formatDMY());
                continue;
            }
            foreach($this->getManager('courseMetal')->fetchAllByDate($date) as $course) {
                foreach ($this->listPercents as $percent) {
                    $arr4Analysis = array(0, $course->getValue());
                    $newCacheCourse = $this->getManager('cacheCourseMetal')->createModel();
                    $newCacheCourse->setCode($course->getCode())
                            ->setLastValue($course->getValue())
                            ->setTypeTrend(CacheCourseMetal_Model::TREND_UP)
                            ->addDataValueByCourse($course)
                            ->setLastDate($date)
                            ->setPercent($percent);
                    $this->getManager('cacheCourseMetal')->insert($newCacheCourse);
                }
            }
            $date->add(new DateInterval('P1D'));
            file_put_contents($fileName, $date->formatDMY());
            $flag = false;
        }
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }
        
    public function cacheInitCurrencyAction() {
        $fileName = $this->pathTmp.'date-currency.tmp';
        $flag = true;
        while($flag) {
            // находим дату
            $date = new Core_Date(file_get_contents($fileName));
            if (!$this->getManager('courseCurrency')->hasByDate($date)) {
                $date->add(new DateInterval('P1D'));
                file_put_contents($fileName, $date->formatDMY());
                continue;
            }
            foreach($this->getManager('courseCurrency')->fetchAllByDate($date) as $course) {
                foreach ($this->listPercents as $percent) {
                    $arr4Analysis = array(0, $course->getValue());
                    $newCacheCourse = $this->getManager('cacheCourseCurrency')->createModel();
                    $newCacheCourse->setCode($course->getCode())
                            ->setLastValue($course->getValue())
                            ->setTypeTrend(CacheCourseMetal_Model::TREND_UP)
                            ->addDataValueByCourse($course)
                            ->setLastDate($date)
                            ->setPercent($percent);
                    $this->getManager('cacheCourseCurrency')->insert($newCacheCourse);
                }
            }
            $date->add(new DateInterval('P1D'));
            file_put_contents($fileName, $date->formatDMY());
            $flag = false;
        }
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }
        
    public function cacheMetalAction() {
        $fileName = $this->pathTmp.'date-metal.tmp';
        $i= 0 ;
        $flag = true;
        while($flag) {
            if (++$i > self::COUNT_RUN_AT_TIME) {
                $flag = false;
                break;
            }
            // находим дату
            $date = new Core_Date(file_get_contents($fileName));
            if (!$this->getManager('courseMetal')->hasByDate($date)) {
                $date->add(new DateInterval('P1D'));
                file_put_contents($fileName, $date->formatDMY());
                continue;
            }
            // =================================================================
            foreach($this->getManager('courseMetal')->fetchAllByDate($date) as $course) {
                foreach ($this->listPercents as $percent) {
                    $cacheCourse = $this->getManager('cacheCourseMetal')->lastByCodePercent($course->getCode(), $percent);
                    $arr4Analysis = array($cacheCourse->getLastValue(), $course->getValue());
                    if ($cacheCourse->isUpTrend()) {
                        $isContinueTrend = Service_GraphAnalysis::isUpTrend($arr4Analysis, $cacheCourse->getPercent());
                    }else{
                        $isContinueTrend = Service_GraphAnalysis::isDownTrend($arr4Analysis, $cacheCourse->getPercent());
                    }
                    if ($isContinueTrend or Service_GraphAnalysis::isEqualChannel($arr4Analysis, $cacheCourse->getPercent())) {
                        $cacheCourse->setLastValue($course->getValue())
                                ->addDataValueByCourse($course)
                                ->setLastDate($date);
                        $this->getManager('cacheCourseMetal')->update($cacheCourse);
                    }else{
                        $typeTrend = $cacheCourse->isUpTrend()?CacheCourseMetal_Model::TREND_DOWN:CacheCourseMetal_Model::TREND_UP;
                        $newCacheCourse = $this->getManager('cacheCourseMetal')->createModel();
                        $newCacheCourse->setCode($course->getCode())
                                ->setLastValue($course->getValue())
                                ->setTypeTrend($typeTrend)
                                ->addDataValue($cacheCourse->getLastDate(), $cacheCourse->getLastValue())
                                ->addDataValueByCourse($course)
                                ->setLastDate($date)
                                ->setPercent($cacheCourse->getPercent());
                        $this->getManager('cacheCourseMetal')->insert($newCacheCourse);
                    }
                    // make analysis
                    $metal = $this->getManager('metal')->getByCode($course->getCode());
                    $this->technicalAnalysisMetal($metal, $date, $percent);
                }
            }
            $date->add(new DateInterval('P1D'));
            file_put_contents($fileName, $date->formatDMY());
        }

        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }    
    
    public function cacheCurrencyAction() {
        $fileName = $this->pathTmp.'date-currency.tmp';
        $i= 0 ;
        $flag = true;
        while($flag) {
            if (++$i > self::COUNT_RUN_AT_TIME) {
                $flag = false;
                break;
            }
            // находим дату
            $date = new Core_Date(file_get_contents($fileName));
            if (!$this->getManager('courseCurrency')->hasByDate($date)) {
                $date->add(new DateInterval('P1D'));
                file_put_contents($fileName, $date->formatDMY());
                continue;
            }
            // =================================================================
            foreach($this->getManager('courseCurrency')->fetchAllByDateListCode($date, $this->listCurrencyCodes) as $course) {
                foreach ($this->listPercents as $percent) {
                    $cacheCourse = $this->getManager('cacheCourseCurrency')->lastByCodePercent($course->getCode(), $percent);
                    $arr4Analysis = array($cacheCourse->getLastValue(), $course->getValue());
                    if ($cacheCourse->isUpTrend()) {
                        $isContinueTrend = Service_GraphAnalysis::isUpTrend($arr4Analysis, $cacheCourse->getPercent());
                    }else{
                        $isContinueTrend = Service_GraphAnalysis::isDownTrend($arr4Analysis, $cacheCourse->getPercent());
                    }
                    if ($isContinueTrend or Service_GraphAnalysis::isEqualChannel($arr4Analysis, $cacheCourse->getPercent())) {
                        $cacheCourse->setLastValue($course->getValue())
                                ->addDataValueByCourse($course)
                                ->setLastDate($date);
                        $this->getManager('cacheCourseCurrency')->update($cacheCourse);
                    }else{
                        $typeTrend = $cacheCourse->isUpTrend()?CacheCourseMetal_Model::TREND_DOWN:CacheCourseMetal_Model::TREND_UP;
                        $newCacheCourse = $this->getManager('cacheCourseCurrency')->createModel();
                        $newCacheCourse->setCode($course->getCode())
                                ->setLastValue($course->getValue())
                                ->setTypeTrend($typeTrend)
                                ->addDataValue($cacheCourse->getLastDate(), $cacheCourse->getLastValue())
                                ->addDataValueByCourse($course)
                                ->setLastDate($date)
                                ->setPercent($cacheCourse->getPercent());
                        $this->getManager('cacheCourseCurrency')->insert($newCacheCourse);
                    }
                    // make analysis
                    $currency = $this->getManager('currency')->getByCode($course->getCode());
                    $this->technicalAnalysisCurrency($currency, $date, $percent);
                }
            }
            $date->add(new DateInterval('P1D'));
            file_put_contents($fileName, $date->formatDMY());
        }

        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }
        
    private function technicalAnalysisMetal(Metal_Model $metal, Core_Date $date, $percent) {
        // ищем фигуры разворота.
//        foreach($this->getManager('metal')->fetchAll() as $metal) {
            $currentCourse = $this->getManager('courseMetal')->getByCodeDate($metal->getCode(), $date);
//            foreach ($this->listPercents as $percent) {
                // фигура W и M.
                $cacheCourses = $this->getManager('cacheCourseMetal')->fetch5ByCodePercent($metal->getCode(), $percent);
                if ($cacheCourses
                    && $cacheCourses->countFirstData() >= self::STABLE_TREND
                    && $cacheCourses->lastNullOperation()) {
                    
                    if ( $cacheCourses->firstIsUpTrend()
                        && Service_GraphAnalysis::isDoubleBottom($cacheCourses->listLastValue(), $percent, $percent) ) {
                        // покупаем
                        $investId = $this->investBuyMetal($currentCourse, $date);
                        // пишем что образовалась фигура
                        $figure = $this->getManager('figureMetal')->createModel();
                        $figure->setCode($metal->getCode())
                                ->setInvestmentId($investId)
                                ->setFigure(FigureMetal_Model::FIGURE_DOUBLE_BOTTOM)
                                ->setCasheCoursesListId($cacheCourses->listId());
                        $this->getManager('figureMetal')->insert($figure);
                    }elseif ($cacheCourses->firstIsDownTrend()
                        && Service_GraphAnalysis::isDoubleTop($cacheCourses->listLastValue(), $percent, $percent) ) {
                        // продаем
                        $investId = $this->investSellMetal($currentCourse, $date);
                        // пишем что образовалась фигура
                        $figure = $this->getManager('figureMetal')->createModel();
                        $figure->setCode($metal->getCode())
                                ->setInvestmentId($investId)
                                ->setFigure(FigureMetal_Model::FIGURE_DOUBLE_TOP)
                                ->setCasheCoursesListId($cacheCourses->listId());
                        $this->getManager('figureMetal')->insert($figure);
                    }
                }
                // =============================================================
                // фигура тройное дно, ReverseS&H, тройные вершины S&H
                $cacheCourses = $this->getManager('cacheCourseMetal')->fetch7ByCodePercent($metal->getCode(), $percent);
                if ($cacheCourses
                    && $cacheCourses->countFirstData() >= self::STABLE_TREND
                    && $cacheCourses->lastNullOperation() ) {
                    
                    if ($cacheCourses->firstIsUpTrend()) {
                        if (Service_GraphAnalysis::isTripleBottom($cacheCourses->listLastValue(), $percent, $percent) ) {
                            // покупаем
                            $investId = $this->investBuyMetal($currentCourse, $date);
                            // пишем что образовалась фигура
                            $figure = $this->getManager('figureMetal')->createModel();
                            $figure->setCode($metal->getCode())
                                    ->setInvestmentId($investId)
                                    ->setFigure(FigureMetal_Model::FIGURE_TRIPLE_BOTTOM)
                                    ->setCasheCoursesListId($cacheCourses->listId());
                            $this->getManager('figureMetal')->insert($figure);
                        }
                        if (Service_GraphAnalysis::isReverseHeadShoulders($cacheCourses->listLastValue(), $percent) ) {
                            // покупаем
                            $investId = $this->investBuyMetal($currentCourse, $date);
                            // пишем что образовалась фигура
                            $figure = $this->getManager('figureMetal')->createModel();
                            $figure->setCode($metal->getCode())
                                    ->setInvestmentId($investId)
                                    ->setFigure(FigureMetal_Model::FIGURE_RESERVE_HEADS_HOULDERS)
                                    ->setCasheCoursesListId($cacheCourses->listId());
                            $this->getManager('figureMetal')->insert($figure);
                        }
                    }elseif($cacheCourses->firstIsDownTrend()) {
                        if (Service_GraphAnalysis::isTripleTop($cacheCourses->listLastValue(), $percent, $percent) ) {
                            // продаем
                            $investId = $this->investSellMetal($currentCourse, $date);
                            // пишем что образовалась фигура
                            $figure = $this->getManager('figureMetal')->createModel();
                            $figure->setCode($metal->getCode())
                                    ->setInvestmentId($investId)
                                    ->setFigure(FigureMetal_Model::FIGURE_TRIPLE_TOP)
                                    ->setCasheCoursesListId($cacheCourses->listId());
                            $this->getManager('figureMetal')->insert($figure);
                        }
                        if (Service_GraphAnalysis::isHeadShoulders($cacheCourses->listLastValue(), $percent) ) {
                            // продаем
                            $investId = $this->investSellMetal($currentCourse, $date);
                            // пишем что образовалась фигура
                            $figure = $this->getManager('figureMetal')->createModel();
                            $figure->setCode($metal->getCode())
                                    ->setInvestmentId($investId)
                                    ->setFigure(FigureMetal_Model::FIGURE_HEADS_HOULDERS)
                                    ->setCasheCoursesListId($cacheCourses->listId());
                            $this->getManager('figureMetal')->insert($figure);
                        }
                    }
                                    
                }
//            }
//        }
    }    
    
    private function technicalAnalysisCurrency(Currency_Model $currency, Core_Date $date, $percent) {
        // ищем фигуры разворота.
//        foreach($this->getManager('currency')->fetchAll() as $currency) {
            $currentCourse = $this->getManager('courseCurrency')->getByCodeDate($currency->getCode(), $date);
//            foreach ($this->listPercents as $percent) {
                // фигура W и M.
                $cacheCourses = $this->getManager('cacheCourseCurrency')->fetch5ByCodePercent($currency->getCode(), $percent);
                if ($cacheCourses
                    && $cacheCourses->countFirstData() >= self::STABLE_TREND
                    && $cacheCourses->lastNullOperation()) {
                    
                    if ( $cacheCourses->firstIsUpTrend()
                        && Service_GraphAnalysis::isDoubleBottom($cacheCourses->listLastValue(), $percent, $percent) ) {
                        // покупаем
                        $investId = $this->investBuyCurrency($currentCourse, $date);
                        // пишем что образовалась фигура
                        $figure = $this->getManager('figureCurrency')->createModel();
                        $figure->setCode($currency->getCode())
                                ->setInvestmentId($investId)
                                ->setFigure(FigureCurrency_Model::FIGURE_DOUBLE_BOTTOM)
                                ->setCasheCoursesListId($cacheCourses->listId());
                        $this->getManager('figureCurrency')->insert($figure);
                    }elseif ($cacheCourses->firstIsDownTrend()
                        && Service_GraphAnalysis::isDoubleTop($cacheCourses->listLastValue(), $percent, $percent) ) {
                        // продаем
                        $investId = $this->investSellCurrency($currentCourse, $date);
                        // пишем что образовалась фигура
                        $figure = $this->getManager('figureCurrency')->createModel();
                        $figure->setCode($currency->getCode())
                                ->setInvestmentId($investId)
                                ->setFigure(FigureCurrency_Model::FIGURE_DOUBLE_TOP)
                                ->setCasheCoursesListId($cacheCourses->listId());
                        $this->getManager('figureCurrency')->insert($figure);
                    }
                }
                // =============================================================
                // фигура тройное дно, ReverseS&H, тройные вершины S&H
                $cacheCourses = $this->getManager('cacheCourseCurrency')->fetch7ByCodePercent($currency->getCode(), $percent);
                if ($cacheCourses
                    && $cacheCourses->countFirstData() >= self::STABLE_TREND
                    && $cacheCourses->lastNullOperation() ) {
                    
                    if ($cacheCourses->firstIsUpTrend()) {
                        if (Service_GraphAnalysis::isTripleBottom($cacheCourses->listLastValue(), $percent, $percent) ) {
                            // покупаем
                            $investId = $this->investBuyCurrency($currentCourse, $date);
                            // пишем что образовалась фигура
                            $figure = $this->getManager('figureCurrency')->createModel();
                            $figure->setCode($currency->getCode())
                                    ->setInvestmentId($investId)
                                    ->setFigure(FigureCurrency_Model::FIGURE_TRIPLE_BOTTOM)
                                    ->setCasheCoursesListId($cacheCourses->listId());
                            $this->getManager('figureCurrency')->insert($figure);
                        }
                        if (Service_GraphAnalysis::isReverseHeadShoulders($cacheCourses->listLastValue(), $percent) ) {
                            // покупаем
                            $investId = $this->investBuyCurrency($currentCourse, $date);
                            // пишем что образовалась фигура
                            $figure = $this->getManager('figureCurrency')->createModel();
                            $figure->setCode($currency->getCode())
                                    ->setInvestmentId($investId)
                                    ->setFigure(FigureCurrency_Model::FIGURE_RESERVE_HEADS_HOULDERS)
                                    ->setCasheCoursesListId($cacheCourses->listId());
                            $this->getManager('figureCurrency')->insert($figure);
                        }
                    }elseif($cacheCourses->firstIsDownTrend()) {
                        if (Service_GraphAnalysis::isTripleTop($cacheCourses->listLastValue(), $percent, $percent) ) {
                            // продаем
                            $investId = $this->investSellCurrency($currentCourse, $date);
                            // пишем что образовалась фигура
                            $figure = $this->getManager('figureCurrency')->createModel();
                            $figure->setCode($currency->getCode())
                                    ->setInvestmentId($investId)
                                    ->setFigure(FigureCurrency_Model::FIGURE_TRIPLE_TOP)
                                    ->setCasheCoursesListId($cacheCourses->listId());
                            $this->getManager('figureCurrency')->insert($figure);
                        }
                        if (Service_GraphAnalysis::isHeadShoulders($cacheCourses->listLastValue(), $percent) ) {
                            // продаем
                            $investId = $this->investSellCurrency($currentCourse, $date);
                            // пишем что образовалась фигура
                            $figure = $this->getManager('figureCurrency')->createModel();
                            $figure->setCode($currency->getCode())
                                    ->setInvestmentId($investId)
                                    ->setFigure(FigureCurrency_Model::FIGURE_HEADS_HOULDERS)
                                    ->setCasheCoursesListId($cacheCourses->listId());
                            $this->getManager('figureCurrency')->insert($figure);
                        }
                    }
                                    
                }
//            }
//        }
    }
    
    private function investBuyMetal(CourseMetal_Model $course, Core_Date $date) {
        $accValue = $this->getManager('account')->getValue();
        // сколько купить
        $count = Core_Math::roundMoney(($accValue / self::PERSENT_BUY)/$course->getValue());
        $invest = $this->getManager('InvestmentMetal')->createModel();
        $invest->setType(InvestmentMetal_Model::TYPE_BUY)
                ->setCount($count)
                ->setMetalCode($course->getCode())
                ->setCourse($course->getValue())
                ->setDate($date);
        $this->getManager('InvestmentMetal')->insertBuy($invest);
        return $invest->getId();
    }
    
    private function investSellMetal(CourseMetal_Model $course, $date) {
        $balance = $this->getManager('BalanceMetal')->getByCode($course->getCode());
        if ($balance && Core_Math::compareMoney($balance->getBalance(), 0) == 1) {
            // сколько продать
            $count = $balance->getBalance();
            $invest = $this->getManager('InvestmentMetal')->createModel();
            $invest->setType(InvestmentMetal_Model::TYPE_SELL)
                    ->setCount($count)
                    ->setMetalCode($course->getCode())
                    ->setCourse($this->getManager('CourseMetal')->getValueCodeByDate($course->getCode(), $date))
                    ->setDate($date);
            $this->getManager('InvestmentMetal')->insertSell($invest);
            return $invest->getId();
        }
    }
    
    private function investBuyCurrency(CourseCurrency_Model $course, Core_Date $date) {
        $accValue = $this->getManager('account')->getValue();
        // сколько купить
        $count = Core_Math::roundMoney(($accValue / self::PERSENT_BUY)/$course->getValue());
        $invest = $this->getManager('InvestmentCurrency')->createModel();
        $invest->setType(InvestmentCurrency_Model::TYPE_BUY)
                ->setCount($count)
                ->setCurrencyCode($course->getCode())
                ->setCourse($course->getValue())
                ->setDate($date);
        $this->getManager('InvestmentCurrency')->insertBuy($invest);
        return $invest->getId();
    }
    
    private function investSellCurrency(CourseCurrency_Model $course, $date) {
        $balance = $this->getManager('BalanceCurrency')->getByCode($course->getCode());
        if ($balance && Core_Math::compareMoney($balance->getBalance(), 0) == 1) {
            // сколько продать
            $count = $balance->getBalance();
            $invest = $this->getManager('InvestmentCurrency')->createModel();
            $invest->setType(InvestmentCurrency_Model::TYPE_SELL)
                    ->setCount($count)
                    ->setCurrencyCode($course->getCode())
                    ->setCourse($this->getManager('CourseCurrency')->getValueCodeByDate($course->getCode(), $date))
                    ->setDate($date);
            $this->getManager('InvestmentCurrency')->insertSell($invest);
            return $invest->getId();
        }
    }
    
    
    public function mailAction() {
        $fileName = $this->pathTmp.'date-metal.tmp';
        $date = new Core_Date(file_get_contents($fileName));
        $i=0;
        while(true) {
            if (!$this->getManager('courseCurrency')->hasByDate($date) or !$this->getManager('courseMetal')->hasByDate($date)) {
                $date->sub(new DateInterval('P1D'));
                if (++$i > 10){
                    break;    
                }
                continue;
            }else{
                break;
            }
        }
        $currentValue       = $this->getManager('Account')->getValue();
        $balanceCurrency    = $this->getManager('BalanceCurrency')->fetchAll();
        $balanceMetal       = $this->getManager('BalanceMetal')->fetchAll();
        $courseCurrency     = $this->getManager('courseCurrency')->fetchAllByDate($date);
        $courseMetal        = $this->getManager('courseMetal')->fetchAllByDate($date);
        Core_Mail::sendAutoInvest($balanceCurrency, $balanceMetal, $courseCurrency, $courseMetal, $date, $currentValue);
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }
    
}
