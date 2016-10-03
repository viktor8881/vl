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
    
    
    public function cacheAction() {
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
            // =================================================================
            foreach($this->getManager('courseMetal')->fetchAllByDate($date) as $course) {
                $cacheCourse = $this->getService('cacheCourseMetal')->lastByCode($course->getCode());
            }
            
            // =================================================================
            $date->add(new DateInterval('P1D'));
            file_put_contents($fileName, $date->formatDMY());
        }

        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }
    
}
