<?php

class MetalController extends Core_Controller_Action
{

    const PERIOD = 5;
    const PERCENT = 10;
    
    const URL_COURCES = 'http://www.cbr.ru/scripts/xml_metall.asp?date_req1=%date%&date_req2=%date%';    
    

    public function indexAction() {
        $period = (int)$this->_getParam('period', self::PERIOD);
        $percent = (float)$this->_getParam('percent', self::PERCENT);
        
        $dateNow = new Core_Date();
        $dateLater = clone $dateNow;
        $dateLater->sub(new DateInterval('P'.$period.'D'));

        $messageUp = new Mail_Model();
        $messageUp->setDay($period)
                ->setPercent($percent);
        $messageDown = new Mail_Model();
        $messageDown->setDay($period)
                ->setPercent($percent);
        foreach ($this->getManager('metal')->fetchAll() as $metal) { // $code=>$nameCurrency) {
            $code = $metal->getCode();
            $nameCurrency = $metal->getName();
            $rows = $this->getManager('courseMetal')->fetchAllByPeriodByCode($dateLater, $dateNow, $code);
            if ($rows->count() > 1) {
                $minCourse = $rows->first();
                $maxCourse = $rows->last();
                // find Up
                $minValue = $minCourse->getBuy()*(1+$percent/100);
                if ($minValue <= $maxCourse->getBuy()) {
                    $curr = new Mail_CurrencyModel();
                    $curr->setName($nameCurrency)
                            ->setStartDate($minCourse->getDate())
                            ->setStartValue($minCourse->getBuy())
                            ->setEndDate($maxCourse->getDate())
                            ->setEndValue($maxCourse->getBuy());
                    $messageUp->addCurrency($curr);                  
                }
                // find Down
                $minValue = $minCourse->getBuy()*(1-$percent/100);                
                if ($minValue >= $maxCourse->getBuy()) {
                    $curr = new Mail_CurrencyModel();
                    $curr->setName($nameCurrency)
                            ->setStartDate($minCourse->getDate())
                            ->setStartValue($minCourse->getBuy())
                            ->setEndDate($maxCourse->getDate())
                            ->setEndValue($maxCourse->getBuy());
                    $messageDown->addCurrency($curr);                    
                }
            }
        }
        if ($messageUp->hasCurrencies()) {
            Core_Mail::metalsUpMail($messageUp);
        }
        if ($messageDown->hasCurrencies()) {
            Core_Mail::metalsDownMail($messageDown);
        }
        //$this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
    }
    

    public function courseAction() {
//        die('stop');
        $date = new Core_Date();
        $metals = $this->getManager('metal')->fetchAll();
        if (!$this->getManager('courseMetal')->getByDate($date)) {
                $xmlstr = file_get_contents(str_replace('%date%', $date->format('d/m/Y'), self::URL_COURCES));
//pr($xmlstr); die('stop');
    //        $xmlstr = file_get_contents(APPLICATION_PATH.'/../data/sources/cource.xml');
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
                                file_put_contents(APPLICATION_PATH.'/../data/log/metal_'.date('dmYHi').'.log', '');
                        }
                }
        }
        $this->_helper->viewRenderer->setNoRender(true);
        //die('stop');
    }
    
    public function recordAction()
    {
        $xmlstr = file_get_contents(APPLICATION_PATH.'/../data/sources/metals.xml');
        $movies = new SimpleXMLElement($xmlstr);
        foreach ($movies->Record as $record) {             
            $course = $this->getManager('courseMetal')->createModel();
            $course->setCode((string)$record['Code'])
                    ->setBuy(str_replace(',','.',(string)$record->Buy))
                    ->setSell(str_replace(',','.',(string)$record->Sell))
                    ->setDate((string)$record['Date']);
            $this->getManager('courseMetal')->insert($course);
        }
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
        die('stop');
    }


}

