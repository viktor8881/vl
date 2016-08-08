<?php

class Cron_IndexController extends Core_Controller_Action
{
    
    public function indexAction()
    {
        $queue = new Core_Queue();
//        throw new RuntimeException('obrabotka очереди');
        $messages = $queue->receive();
        foreach ($messages as $i => $message) {
            $body = $message->body;
            switch ($body) {
                case Core_Queue::TASK_ANALYSIS:
                    $this->taskAnalisys();
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