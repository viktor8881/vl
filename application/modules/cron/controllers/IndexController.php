<?php

class Cron_IndexController extends Core_Controller_Action
{



    const STABLE_TREND = 3;
    private $listPercents = [0.06, 0.1, 0.2, 0.4, 0.6, 0.8, 1, 1.35, 1.7, 2];
    
//    private function tempAction() {
//        $dateNow = new Core_Date('26.11.2016');
//        foreach($this->getManager('courseMetal')->fetchAllByDate($dateNow) as $course) {
//            foreach ($this->listPercents as $percent) {
//                $metal = $this->getManager('metal')->getByCode($course->getCode());
//                $this->technicalAnalysisMetal($metal, $dateNow, $percent);
//            }
//        }
//        foreach($this->getManager('courseCurrency')->fetchAllByDate($dateNow) as $course) {
//            foreach ($this->listPercents as $percent) {
//                $currency = $this->getManager('currency')->getByCode($course->getCode());
//                $this->technicalAnalysisCurrency($currency, $dateNow, $percent);
//            }
//        }
//        $this->taskAnalysis($dateNow);
//        $this->_helper->viewRenderer->setNoRender(true);
//        $this->_helper->layout()->disableLayout(); 
//    }
//    
//    private function mailAction() {
//        $dateNow = new Core_Date('26.11.2016');
//        $this->sendMessage($dateNow);
//        $this->_helper->viewRenderer->setNoRender(true);
//        $this->_helper->layout()->disableLayout(); 
//    }

    public function indexAction()
    {
        $dateNow = new Core_Date();
        $queue = new Core_Queue_Analysis('analysis');
        $messages = $queue->receive();
        foreach ($messages as $message) {
            $body = $message->body;
            switch ($body) {
                case Core_Queue_Analysis::TASK_FILL_DATA:
                    $this->fillDataCache($dateNow);
                    // add task send email.
                    $queue->sendRunAnalysis(true);                    
                    break;
                case Core_Queue_Analysis::TASK_ANALYSIS:
                    $countRec = $this->taskAnalysis($dateNow);
                    // add task send email.
                    $queue->sendTaskEmail(true);
                    break;
                case Core_Queue_Analysis::TASK_SEND_MESSAGE:
                    $this->sendMessage($dateNow);
                    break;
                default:
                    throw new Core_Domen_NotFoundException('unknown type task.');
                    break;
            }
            $queue->deleteMessage($message);
        }
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }
    
    private function fillDataCache(Core_Date $dateNow) {
        foreach($this->getManager('courseMetal')->fetchAllByDate($dateNow) as $course) {
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
                            ->setLastDate($dateNow);
                    $this->getManager('cacheCourseMetal')->update($cacheCourse);
                }else{
                    $typeTrend = $cacheCourse->isUpTrend()?CacheCourseMetal_Model::TREND_DOWN:CacheCourseMetal_Model::TREND_UP;
                    $newCacheCourse = $this->getManager('cacheCourseMetal')->createModel();
                    $newCacheCourse->setCode($course->getCode())
                            ->setLastValue($course->getValue())
                            ->setTypeTrend($typeTrend)
                            ->addDataValue($cacheCourse->getLastDate(), $cacheCourse->getLastValue())
                            ->addDataValueByCourse($course)
                            ->setLastDate($dateNow)
                            ->setPercent($cacheCourse->getPercent());
                    $this->getManager('cacheCourseMetal')->insert($newCacheCourse);
                }
                // make analysis
                $metal = $this->getManager('metal')->getByCode($course->getCode());
                $this->technicalAnalysisMetal($metal, $dateNow, $percent);
            }
        }
        // =====================================================================
        foreach($this->getManager('courseCurrency')->fetchAllByDate($dateNow) as $course) {
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
                            ->setLastDate($dateNow);
                    $this->getManager('cacheCourseCurrency')->update($cacheCourse);
                }else{
                    $typeTrend = $cacheCourse->isUpTrend()?CacheCourseMetal_Model::TREND_DOWN:CacheCourseMetal_Model::TREND_UP;
                    $newCacheCourse = $this->getManager('cacheCourseCurrency')->createModel();
                    $newCacheCourse->setCode($course->getCode())
                            ->setLastValue($course->getValue())
                            ->setTypeTrend($typeTrend)
                            ->addDataValue($cacheCourse->getLastDate(), $cacheCourse->getLastValue())
                            ->addDataValueByCourse($course)
                            ->setLastDate($dateNow)
                            ->setPercent($cacheCourse->getPercent());
                    $this->getManager('cacheCourseCurrency')->insert($newCacheCourse);
                }
                // make analysis
                $currency = $this->getManager('currency')->getByCode($course->getCode());
                $this->technicalAnalysisCurrency($currency, $dateNow, $percent);
            }
        }
    }

    private function sendMessage(Core_Date $dateNow) {
        // readAll analysis currency by date
        $analysis = $this->getManager('analysisCurrency')->fetchAllByDate($dateNow);
        if ($analysis->count()) {
            foreach ($analysis->getCurrencies() as $currency) {
                Core_Mail::sendAnalysisCurrency($currency, 
                        $analysis->getOvertimeByCurrencyCode($currency->getCode()),
                        $analysis->listPercentByCurrencyCode($currency->getCode()),
                        $analysis->listFigureByCurrencyCode($currency->getCode()));
            }
        }
        // readAll analysis metal by date
        $analysis = $this->getManager('analysisMetal')->fetchAllByDate($dateNow);
        if ($analysis->count()) {
            foreach ($analysis->getMetals() as $metal) {
                Core_Mail::sendAnalysisMetal($metal, 
                        $analysis->getOvertimeByMetalCode($metal->getCode()),
                        $analysis->listPercentByMetalCode($metal->getCode()),
                        $analysis->listFigureByMetalCode($metal->getCode()));
            }
        }
    }

    private function taskAnalysis(Core_Date $dateNow) {
        $count = 0;
        $serviceAnalyses = $this->getService('analysis');
        // считываем настройки выполнения анализа
        $tasks = $this->getManager('task')->fetchAll();
        foreach ($tasks as $task) {
            $count += $serviceAnalyses->runByTask($task, $dateNow);
        }
        return $count;
    }
    
    
    private function technicalAnalysisMetal(Metal_Model $metal, Core_Date $date, $percent) {
        // фигура W и M.
        $cacheCourses = $this->getManager('cacheCourseMetal')->fetch5ByCodePercent($metal->getCode(), $percent);
        if ($cacheCourses
            && $cacheCourses->countFirstData() >= self::STABLE_TREND
            && $cacheCourses->lastNullOperation()) {

            if ( $cacheCourses->firstIsUpTrend()
                && Service_GraphAnalysis::isDoubleBottom($cacheCourses->listLastValue(), $percent, $percent) ) {
                // пишем что образовалась фигура
                $subData = array('figure'=> FigureMetal_Model::FIGURE_DOUBLE_BOTTOM,
                            'courses_list_id' => $cacheCourses->listId());
                $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_FIGURE,
                    'metal_code'=>$metal->getCode(),
                    'body'=>  json_encode($subData),
                    'created'=>$date);
                $analysis = $this->getManager('analysisMetal')->createModel($data);
                $this->getManager('analysisMetal')->insert($analysis);
            }elseif ($cacheCourses->firstIsDownTrend()
                && Service_GraphAnalysis::isDoubleTop($cacheCourses->listLastValue(), $percent, $percent) ) {
                // пишем что образовалась фигура
                $subData = array('figure'   => FigureMetal_Model::FIGURE_DOUBLE_TOP,
                            'courses_list_id' => $cacheCourses->listId());
                $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_FIGURE,
                    'metal_code'=>$metal->getCode(),
                    'body'=>  json_encode($subData),
                    'created'=>$date);
                $analysis = $this->getManager('analysisMetal')->createModel($data);
                $this->getManager('analysisMetal')->insert($analysis);
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
                    // пишем что образовалась фигура
                    $subData = array('figure'       => FigureMetal_Model::FIGURE_TRIPLE_BOTTOM,
                            'courses_list_id' => $cacheCourses->listId());
                    $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_FIGURE,
                        'metal_code'=>$metal->getCode(),
                        'body'=>  json_encode($subData),
                        'created'=>$date);
                    $analysis = $this->getManager('analysisMetal')->createModel($data);
                    $this->getManager('analysisMetal')->insert($analysis);
                }
                if (Service_GraphAnalysis::isReverseHeadShoulders($cacheCourses->listLastValue(), $percent) ) {
                    // пишем что образовалась фигура
                    $subData = array('figure'       => FigureMetal_Model::FIGURE_RESERVE_HEADS_HOULDERS,
                            'courses_list_id' => $cacheCourses->listId());
                    $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_FIGURE,
                        'metal_code'=>$metal->getCode(),
                        'body'=>  json_encode($subData),
                        'created'=>$date);
                    $analysis = $this->getManager('analysisMetal')->createModel($data);
                    $this->getManager('analysisMetal')->insert($analysis);
                }
            }elseif($cacheCourses->firstIsDownTrend()) {
                if (Service_GraphAnalysis::isTripleTop($cacheCourses->listLastValue(), $percent, $percent) ) {
                    // пишем что образовалась фигура
                    $subData = array('figure'       => FigureMetal_Model::FIGURE_TRIPLE_TOP,
                            'courses_list_id' => $cacheCourses->listId());
                    $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_FIGURE,
                        'metal_code'=>$metal->getCode(),
                        'body'=>  json_encode($subData),
                        'created'=>$date);
                    $analysis = $this->getManager('analysisMetal')->createModel($data);
                    $this->getManager('analysisMetal')->insert($analysis);
                }
                if (Service_GraphAnalysis::isHeadShoulders($cacheCourses->listLastValue(), $percent) ) {
                    // пишем что образовалась фигура
                    $subData = array('figure'       => FigureMetal_Model::FIGURE_HEADS_HOULDERS,
                            'courses_list_id' => $cacheCourses->listId());
                    $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_FIGURE,
                        'metal_code'=>$metal->getCode(),
                        'body'=>  json_encode($subData),
                        'created'=>$date);
                    $analysis = $this->getManager('analysisMetal')->createModel($data);
                    $this->getManager('analysisMetal')->insert($analysis);
                }
            }

        }
    }    
    
    private function technicalAnalysisCurrency(Currency_Model $currency, Core_Date $date, $percent) {
        // фигура W и M.
        $cacheCourses = $this->getManager('cacheCourseCurrency')->fetch5ByCodePercent($currency->getCode(), $percent);
        if ($cacheCourses
            && $cacheCourses->countFirstData() >= self::STABLE_TREND
            && $cacheCourses->lastNullOperation()) {

            if ( $cacheCourses->firstIsUpTrend()
                && Service_GraphAnalysis::isDoubleBottom($cacheCourses->listLastValue(), $percent, $percent) ) {
                // пишем что образовалась фигура
                $subData = array('figure'   => FigureCurrency_Model::FIGURE_DOUBLE_BOTTOM,
                        'courses_list_id' => $cacheCourses->listId());
                $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_FIGURE,
                    'currency_code'=>$currency->getCode(),
                    'body'=>  json_encode($subData),
                    'created'=>$date);
                $analysis = $this->getManager('analysisCurrency')->createModel($data);
                $this->getManager('analysisCurrency')->insert($analysis);
            }elseif ($cacheCourses->firstIsDownTrend()
                && Service_GraphAnalysis::isDoubleTop($cacheCourses->listLastValue(), $percent, $percent) ) {
                // пишем что образовалась фигура
                $subData = array('figure'   => FigureCurrency_Model::FIGURE_DOUBLE_TOP,
                        'courses_list_id' => $cacheCourses->listId());
                $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_FIGURE,
                    'currency_code'=>$currency->getCode(),
                    'body'=>  json_encode($subData),
                    'created'=>$date);
                $analysis = $this->getManager('analysisCurrency')->createModel($data);
                $this->getManager('analysisCurrency')->insert($analysis);
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
                    // пишем что образовалась фигура
                    $subData = array('figure'   => FigureCurrency_Model::FIGURE_TRIPLE_BOTTOM,
                            'courses_list_id' => $cacheCourses->listId());
                    $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_FIGURE,
                        'currency_code'=>$currency->getCode(),
                        'body'=>  json_encode($subData),
                        'created'=>$date);
                    $analysis = $this->getManager('analysisCurrency')->createModel($data);
                    $this->getManager('analysisCurrency')->insert($analysis);
                }
                if (Service_GraphAnalysis::isReverseHeadShoulders($cacheCourses->listLastValue(), $percent) ) {
                    // пишем что образовалась фигура
                    $subData = array('figure'   => FigureCurrency_Model::FIGURE_RESERVE_HEADS_HOULDERS,
                            'courses_list_id' => $cacheCourses->listId());
                    $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_FIGURE,
                        'currency_code'=>$currency->getCode(),
                        'body'=>  json_encode($subData),
                        'created'=>$date);
                    $analysis = $this->getManager('analysisCurrency')->createModel($data);
                    $this->getManager('analysisCurrency')->insert($analysis);
                }
            }elseif($cacheCourses->firstIsDownTrend()) {
                if (Service_GraphAnalysis::isTripleTop($cacheCourses->listLastValue(), $percent, $percent) ) {
                    // пишем что образовалась фигура
                    $subData = array('figure'   => FigureCurrency_Model::FIGURE_TRIPLE_TOP,
                            'courses_list_id' => $cacheCourses->listId());
                    $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_FIGURE,
                        'currency_code'=>$currency->getCode(),
                        'body'=>  json_encode($subData),
                        'created'=>$date);
                    $analysis = $this->getManager('analysisCurrency')->createModel($data);
                    $this->getManager('analysisCurrency')->insert($analysis);
                }
                if (Service_GraphAnalysis::isHeadShoulders($cacheCourses->listLastValue(), $percent) ) {
                    // пишем что образовалась фигура
                    $subData = array('figure'   => FigureCurrency_Model::FIGURE_HEADS_HOULDERS,
                            'courses_list_id' => $cacheCourses->listId());
                    $data = array('type'=>AnalysisMetal_Model_Abstract::TYPE_FIGURE,
                        'currency_code'=>$currency->getCode(),
                        'body'=>  json_encode($subData),
                        'created'=>$date);
                    $analysis = $this->getManager('analysisCurrency')->createModel($data);
                    $this->getManager('analysisCurrency')->insert($analysis);
                }
            }

        }
    }
    
}