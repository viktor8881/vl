<?php

class Cron_ReceiveQuotationController extends Core_Controller_Action
{
    
    const URL_CURRENCY_COURCES = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=';
    const URL_METAL_COURCES = 'http://www.cbr.ru/scripts/xml_metall.asp?date_req1=%date%&date_req2=%date%';    
    
    
    public function currencyAction() {
        $currencies = $this->getManager('currency')->fetchAll();
        $date = new Core_Date();
        if (!$this->getManager('courseCurrency')->getByDate($date)) {
            $xmlstr = file_get_contents(self::URL_CURRENCY_COURCES.$date->format('d/m/Y'));
            $movies = new SimpleXMLElement($xmlstr);
            if (false !== strstr($xmlstr, $date->format('d.m.Y'))) {
                foreach ($movies->Valute as $item) {
                    $code = (string)$item['ID'];
                    if ($currencies->hasCode($code)) {
                        // insert
                        $course = $this->getManager('courseCurrency')->createModel();
                        $course->setCode($code)
                                        ->setNominal(str_replace(',','.',(string)$item->Nominal))
                                        ->setValue(str_replace(',','.',(string)$item->Value))
                                        ->setDate($date);
                        $this->getManager('courseCurrency')->insert($course);
                        // tasks to queue
                        $queue = Core_Container::getQueue();                        
                        $queue->sendRunAnalysis(true);
                    }
                }
            }
        }
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }
    
    
    public function metalAction() {
        $date = new Core_Date();
        $metals = $this->getManager('metal')->fetchAll();
        if (!$this->getManager('courseMetal')->getByDate($date)) {
            $xmlstr = file_get_contents(str_replace('%date%', $date->format('d/m/Y'), self::URL_METAL_COURCES));
            $movies = new SimpleXMLElement($xmlstr);        
            foreach ($movies->Record as $item) {
                $code = (string)$item['Code'];
                if ($metals->hasCode($code)) {
                    $course = $this->getManager('courseMetal')->createModel();
                    $course->setCode($code)
                                    ->setBuy(str_replace(',','.',(string)$item->Buy))
                                    ->setSell(str_replace(',','.',(string)$item->Sell))
                                    ->setDate($date);
                    $this->getManager('courseMetal')->insert($course);
                    // tasks to queue
                    $queue = new Core_Queue();
                    $queue->sendRunAnalysis(true);
                }
            }
        }
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout();
    }
        
}