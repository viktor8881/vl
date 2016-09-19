<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cron_GraphAnalisistController
 *
 * @author Viktor
 */
require_once APPLICATION_PATH.'/modules/cron/models/StopLoss.php';
class Cron_GraphAnalisistController extends Core_Controller_Action {
    
    const ID_METAL = 1;
    
    const PERSENT_BUY = 10;
    const PERSENT_SELL = 20;
    
    private $pathTmp;
    
    public function init() {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $this->pathTmp = $bootstrap->getOptions()['path']['temp'];
    }

    public function indexAction() {
        $fileName = $this->pathTmp.'date.tmp';
        $i= 0 ;
        $flag = true;
        while($flag) {
            if (++$i > 30) {
                $flag = false;
                break;
            }
            // находим дату
            $date = new Core_Date(file_get_contents($fileName));
            if (!$this->getManager('courseCurrency')->hasByDate($date) or !$this->getManager('courseMetal')->hasByDate($date)) {
                $date->add(new DateInterval('P1D'));
                file_put_contents($fileName, $date->formatDMY());
                continue;
            }

            $stopLoss = new StopLoss($this->pathTmp.'stoploss.tmp');

            // находим дату первого повышения тренда
            $dateStartUp = $this->getDateStartUp();
            if (!$dateStartUp) {
                $dateStartUp = clone $date;
                $dateStartUp->sub(new DateInterval('P7D'));
            }
            // получим список курсов
            $filters = new Core_Domen_Filter_Collection();
            $filters->addFilter(new CourseMetal_Filter_Period(array($dateStartUp, $date)))
                    ->addFilter(new CourseMetal_Filter_Code(self::ID_METAL));
            $courses = $this->getManager('courseMetal')->fetchAllByFilter($filters);
            if ($courses->count()) {
                $firstCourses = $courses->first();
                $currentCourses = $courses->last();
                // рост инвестиций
                if (Service_GraphAnalisis::isEqualTrend($courses->getValues(), 0.5)) {
                    // echo'Стабильный горизонтальный тренд<br />';
                }elseif (Service_GraphAnalisis::isUpTrend($courses->getValues(), 0.5)) {
                    // echo'рост инвестиций<br />';
                    if ($this->isUpTrendContinue()) {
                        // все норм. поднимаем Стоп-лосс
                        $stopLoss->up($currentCourses->getValue());
                        // echo'рост продолжается<br />';
                    }else{
                        // покупаем инвестиции и пишем дату начала повышения тренда
                        $this->investBuy($currentCourses, $date);
                        $this->recordDateStartUp($firstCourses->getDateToDb());
                        $stopLoss->create($currentCourses->getValue());
                        // echo'покупка инвестиций<br />';
                    }
                }else{
                    // echo'падение инвестиций<br />';
                    // падение инвестиций
                    if ( $this->isChangeTrend() ) {
                        // echo'тренд изменился с роста на падение<br />';
                        // тренд изменился
                        if ($stopLoss->isOver($currentCourses->getValue())) {
                            // echo'достигли линии стоп-лосс. Продажа инвестиций<br />';
                            // достигли линии стоп-лосс
                            $this->investSell($currentCourses, $date);
                            $stopLoss->delete();
                        }
                    }
                    if (Service_GraphAnalisis::isDoubleTop($courses->getValues(), 1, 5)) {
                        // echo'достигли isDoubleTop. Продажа инвестиций<br />';
                        $this->investSell($currentCourses, $date);
                        $stopLoss->delete();
                    }
                }
//                pr("========================================================");
            }
            if ($date->formatDMY() == date('d.m.Y')) {
                return null;
            }
            $date->add(new DateInterval('P1D'));
            file_put_contents($fileName, $date->formatDMY());
        }

        $this->_helper->viewRenderer->setNoRender(true);
//        $this->_helper->layout()->disableLayout(); 
    }
    
    
    public function mailAction() {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $fileName = $bootstrap->getOptions()['path']['temp'].'date.tmp';
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
        $balanceCurrency    = $this->getManager('BalanceCurrency')->fetchAll();
        $balanceMetal       = $this->getManager('BalanceMetal')->fetchAll();
        $courseCurrency     = $this->getManager('courseCurrency')->fetchAllByDate($date);
        $courseMetal        = $this->getManager('courseMetal')->fetchAllByDate($date);
        $currentValue       = $this->getManager('Account')->getValue();
        Core_Mail::sendAutoInvest($balanceCurrency, $balanceMetal, $courseCurrency, $courseMetal, $date, $currentValue);
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }
    
    
    private function getDateStartUp() {
        $fileName = $this->pathTmp.'dateStartUp.tmp';
        if (file_exists($fileName)) {
            return new Core_Date(file_get_contents($fileName));
        }
        return null;
    }
    
    private function isUpTrendContinue() {
        $fileName = $this->pathTmp.'dateStartUp.tmp';
        return file_exists($fileName)?true:false;
    }
    
    private function recordDateStartUp($date) {
        $fileName = $this->pathTmp.'dateStartUp.tmp';
        file_put_contents($fileName, $date);
    }
    
    private function isChangeTrend() {
        if ($this->isUpTrendContinue()) {
            $fileName = $this->pathTmp.'dateStartUp.tmp';
            unlink($fileName);
            return true;
        }
        return false;
     }


    private function investBuy(CourseMetal_Model $course, Core_Date $date) {
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
    }
    
    private function investSell(CourseMetal_Model $course, $date) {
        $balance = $this->getManager('BalanceMetal')->getByCode(self::ID_METAL);
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
        }
    }
    
}
