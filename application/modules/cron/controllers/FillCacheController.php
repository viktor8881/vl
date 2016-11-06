<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cron_GraphAnalysist2Controller
 *
 * @author Viktor
 */

class Cron_FillCacheController extends Core_Controller_Action {
    
    
    const INIT_DATE = '06.03.2001';
    const COUNT_RUN_AT_TIME = 100;

    private $listPercents = [0.2, 0.4, 0.6, 0.8, 1, 1.35, 1.7, 2];
    private $pathTmp;
    

    public function init() {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $this->pathTmp = $bootstrap->getOptions()['path']['temp'];
    }
    
    public function tmpMetalAction() {
        $dateNow = new Core_Date();
        $fileName = $this->pathTmp.'date-metal.tmp';
        if (!file_exists($fileName)) {
            exit;
        }
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
            if ($date->compareDate($dateNow)==1) {
                rename($fileName, $this->pathTmp.'_date-metal.tmp');
                $flag = false;
                break;
            }
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
                }
            }
            $date->add(new DateInterval('P1D'));
            file_put_contents($fileName, $date->formatDMY());
        }        
                
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }
    
    
    public function tmpCurrencyAction() {
        $dateNow = new Core_Date();
        $fileName = $this->pathTmp.'date-currency.tmp';
        if (!file_exists($fileName)) {
            exit;
        }
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
            if ($date->compareDate($dateNow)==1) {
                rename($fileName, $this->pathTmp.'_date-currency.tmp');
                $flag = false;
                break;
            }
            foreach($this->getManager('courseCurrency')->fetchAllByDate($date) as $course) {
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
                }
            }
            $date->add(new DateInterval('P1D'));
            
            file_put_contents($fileName, $date->formatDMY());
        }
                
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }
    
    
    public function tmpInitAction() {
        $date = new Core_Date(self::INIT_DATE);
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
        
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }
    
}
