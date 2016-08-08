<?php

class Analysis_CurrencyController extends Core_Controller_Action
{

    
    public function indexAction() {
        // считываем настройки выполнения анализа
        $tasks = $this->getManager('analysis')->fetchAll();
        foreach ($tasks as $task) {
            if ($task->isPercentPeriod()) {
                $serviceAnalysesPercent = $this->getService('AnalysesPercent');
                $serviceAnalysesPercent->runByTask($task);
            }
            
            if ($task->isOverTime()) {
                $serviceAnalysesOverTime = $this->getService('AnalysesOverTime');
                $serviceAnalysesOverTime->runByTask($task);
            }
        }
        
    }
    
}

