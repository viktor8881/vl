<?php

class Cron_IndexController extends Core_Controller_Action
{
    
    public function indexAction()
    {
        $queue = Core_Container::getQueue();
        $messages = $queue->receive();
        foreach ($messages as $i => $message) {
            $body = $message->body;
            switch ($body) {
                case Core_Queue::TASK_ANALYSIS:
                    $countRec = $this->taskAnalisys();
                    if ($countRec > 0) {
                        // add task send email.
                        $queue->sendTaskEmail(true);
                    }
                    break;
                case Core_Queue::TASK_SEND_MESSAGE:
                    $this->sendMessage();
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
    
    
    private function sendMessage() {
        // readAll analysis currency for today
        $analysis = $this->getManager('analysisCurrency')->fetchAllByToday();
        if ($analysis->count()) {
            foreach ($analysis->getCurrencies() as $currency) {
                Core_Mail::sendAnalysisCurrency($currency, 
                        $analysis->listAnalysisOvertimeByCurrencyCode($currency->getCode()),
                        $analysis->listAnalysisPercentByCurrencyCode($currency->getCode()));
            }
        }
        // readAll analysis metal for today
        $analysis = $this->getManager('analysisMetal')->fetchAllByToday();
        if ($analysis->count()) {
            foreach ($analysis->getMetals() as $metal) {
                Core_Mail::sendAnalysisMetal($metal, 
                        $analysis->listAnalysisOvertimeByMetalCode($metal->getCode()),
                        $analysis->listAnalysisPercentByMetalCode($metal->getCode()));
            }
        }
    }

    private function taskAnalisys() {
        // считываем настройки выполнения анализа
        $tasks = $this->getManager('task')->fetchAll();
        foreach ($tasks as $task) {
            $serviceAnalyses = $this->getService('analysis');
            $serviceAnalyses->runByTask($task);            
        }
        
    }
    
}