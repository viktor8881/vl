<?php

class Cron_IndexController extends Core_Controller_Action
{
    
    public function indexAction()
    {
        $dateNow = new Core_Date();
        $queue = Core_Container::getQueue();
        $messages = $queue->receive();
        foreach ($messages as $i => $message) {
            $body = $message->body;
            switch ($body) {
                case Core_Queue::TASK_ANALYSIS:
                    $countRec = $this->taskAnalysis($dateNow);
                    if ($countRec > 0) {
                        // add task send email.
                        $queue->sendTaskEmail(true);
                    }
                    break;
                case Core_Queue::TASK_SEND_MESSAGE:
                    $this->sendMessage($dateNow);
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
    
    
    private function sendMessage(Core_Date $dateNow) {
        // readAll analysis currency by date
        $analysis = $this->getManager('analysisCurrency')->fetchAllByDate($dateNow);
        if ($analysis->count()) {
            foreach ($analysis->getCurrencies() as $currency) {
                Core_Mail::sendAnalysisCurrency($currency, 
                        $analysis->getOvertimeByCurrencyCode($currency->getCode()),
                        $analysis->listPercentByCurrencyCode($currency->getCode()));
            }
        }
        // readAll analysis metal by date
        $analysis = $this->getManager('analysisMetal')->fetchAllByDate($dateNow);
        if ($analysis->count()) {
            foreach ($analysis->getMetals() as $metal) {
                Core_Mail::sendAnalysisMetal($metal, 
                        $analysis->getOvertimeByMetalCode($metal->getCode()),
                        $analysis->listPercentByMetalCode($metal->getCode()));
            }
        }
    }

    private function taskAnalysis(Core_Date $dateNow) {
        $count = 0;
        // считываем настройки выполнения анализа
        $tasks = $this->getManager('task')->fetchAll();
        foreach ($tasks as $task) {
            $serviceAnalyses = $this->getService('analysis');
            $count += $serviceAnalyses->runByTask($task, $dateNow);
        }
        return $count;
    }
    
    public function testAction() {
        $bootstrap = $this->getInvokeArg('bootstrap');
        $fileName = $bootstrap->getOptions()['path']['temp'].'date.tmp';
        $date = new Core_Date(file_get_contents($fileName));
        // run analysis
        $this->taskAnalysis($date);
        
        $serviceAutoInvest = $this->getService('autoinvestment');
        $serviceAutoInvest->run($date);
        
        
        
        // added very good from very bad
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }


//    public function analisysAction() {
//        $count = 0;
//        // считываем настройки выполнения анализа
//        $tasks = $this->getManager('task')->fetchAll();
//        foreach ($tasks as $task) {
//            $serviceAnalyses = $this->getService('analysis');
//            $count += $serviceAnalyses->runByTask($task);            
//        }
//        echo $count;
//        die('stop');
//        return $count;
//    }
    
}