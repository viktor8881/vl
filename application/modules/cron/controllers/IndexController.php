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
        $serviceAnalyses = $this->getService('analysis');
        // считываем настройки выполнения анализа
        $tasks = $this->getManager('task')->fetchAll();
        foreach ($tasks as $task) {
            $count += $serviceAnalyses->runByTask($task, $dateNow);
        }
        return $count;
    }

//
//    
//    public function recAction() {
//        $path = APPLICATION_PATH.'/../data/sourcesCurrency/';
//        $date = new Core_Date('02.03.2001');
//        $flag = true;
//        while($flag) {
//            if (!file_exists($path.$date->format('d.m.Y'))) {
//                file_put_contents($path.'../endsourcesCurrency', $date->format('d.m.Y'));
//                $flag = false;
//                continue;
//            }
//            $xmlstr = file_get_contents($path.$date->format('d.m.Y'));
//            $movies = new SimpleXMLElement($xmlstr);
//            if (false !== strstr($xmlstr, $date->format('d.m.Y'))) {
//                $currencies = $this->getManager('currency')->fetchAll();
//                foreach ($movies->Valute as $item) {
//                    $code = (string)$item['ID'];
//                    if ($currencies->hasCode($code)) {
//                        // insert
//                        $course = $this->getManager('courseCurrency')->createModel();
//                        $course->setCode($code)
//                                        ->setNominal(str_replace(',','.',(string)$item->Nominal))
//                                        ->setValue(str_replace(',','.',(string)$item->Value))
//                                        ->setDate($date);
//                        $this->getManager('courseCurrency')->insert($course);
//                    }
//                }
//            }
//            $date->add(new DateInterval('P1D'));
//        }
//        exit;
//    }
//    
    
    
}