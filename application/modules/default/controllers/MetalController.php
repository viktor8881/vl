<?php

class MetalController extends Zend_Controller_Action
{

    const PERIOD = 5;
    const PERCENT = 10;
    
    const URL_COURCES = 'http://www.cbr.ru/scripts/xml_metall.asp?date_req1=%date%&date_req2=%date%';
    
    private $_codes = array(
        '1'=>'Золото', 
        '2'=>'Серебро',        
        '3'=>'Платина',
        '4'=>'Палладий');
    

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
        foreach ($this->_codes as $code=>$nameCurrency) {
            $rows = Core_Container::getManager('metal')->fetchAllByPeriodByCode($dateLater, $dateNow, $code);
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
                    
//                    $messageUp .= "<br />Увеличился курс покупки металла ".$nameCurrency."<br />";
//                    $messageUp .= "расчетный период - ".$period."<br />";
//                    $messageUp .= "расчетный процент  > ".$percent."<br />";
//                    $messageUp .= $minCourse->getDateFormatDMY().' '.$minCourse->getBuy()."<br />";
//                    $messageUp .= $maxCourse->getDateFormatDMY().' '.$maxCourse->getBuy()."<br />";
//                    $messageUp .= "==========================================================<br />";
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
                    
//                    $messageDown .= "<br />Уменьшился курс покупки металла ".$nameCurrency."<br />";
//                    $messageDown .= "расчетный период - ".$period."<br />";
//                    $messageDown .= "расчетный процент  > ".$percent."<br />";
//                    $messageDown .= $minCourse->getDateFormatDMY().' '.$minCourse->getBuy()."<br />";
//                    $messageDown .= $maxCourse->getDateFormatDMY().' '.$maxCourse->getBuy()."<br />";
//                    $messageDown .= "==========================================================<br />";
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
        if (!Core_Container::getManager('metal')->getByDate($date)) {
                $xmlstr = file_get_contents(str_replace('%date%', $date->format('d/m/Y'), self::URL_COURCES));
//pr($xmlstr); die('stop');
    //        $xmlstr = file_get_contents(APPLICATION_PATH.'/../data/sources/cource.xml');
                $movies = new SimpleXMLElement($xmlstr);        
                foreach ($movies->Record as $item) {
                        $code = (string)$item['Code'];
                        if (array_key_exists($code, $this->_codes)) {
                                $course = Core_Container::getManager('metal')->createModel();
                                $course->setCode($code)
                                                ->setBuy(str_replace(',','.',(string)$item->Buy))
                                                ->setSell(str_replace(',','.',(string)$item->Sell))
                                                ->setDate($date);
                                Core_Container::getManager('metal')->insert($course);
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
            $course = Core_Container::getManager('metal')->createModel();
            $course->setCode((string)$record['Code'])
                    ->setBuy(str_replace(',','.',(string)$record->Buy))
                    ->setSell(str_replace(',','.',(string)$record->Sell))
                    ->setDate((string)$record['Date']);
            Core_Container::getManager('metal')->insert($course);
        }
        $this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
        die('stop');
    }


}

