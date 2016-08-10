<?php

class IndexController extends Core_Controller_Action
{

    const PERIOD = 5;
    const PERCENT = 1;
    
    const URL_COURCES = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=';
    
    
    
//    public function sendAction() {
//        $mess = new Mail_Model();
//        $mess->setDay(3)
//            ->setPercent(8);
//            $curr = new Mail_CurrencyModel();
//            $curr->setName("Долларь")
//                    ->setStartDate(new Core_Date('2016-10-12'))
//                    ->setStartValue(12.548)
//                    ->setEndDate(new Core_Date)
//                    ->setEndValue(17.548);
//        $mess->addCurrency($curr);
//            $curr = new Mail_CurrencyModel();
//            $curr->setName("Манатта")
//                    ->setStartDate(new Core_Date('2016-04-14'))
//                    ->setStartValue(42.356048)
//                    ->setEndDate(new Core_Date)
//                    ->setEndValue(52.65824);
//        $mess->addCurrency($curr);        
//        $this->view->mess = $mess;        
//        Core_Mail::quotationsUpMail($mess);
//        Core_Mail::quotationsDownMail($mess);        
//    }

    public function indexAction() {
        die('index');
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
        foreach ($this->getManager('currency')->fetchAll() as $currency) {
            $code = $currency->getCode();
            $nameCurrency = $currency->getName();
            $rows = $this->getManager('courseCurrency')->fetchAllByPeriodByCode($dateLater, $dateNow, $code);
            if ($rows->count() > 1) {
                $minCourse = $rows->first();
                $maxCourse = $rows->last();
                $minValue = $minCourse->getValueForOne()*(1+$percent/100);
                if ($minValue <= $maxCourse->getValueForOne()) {
                    $curr = new Mail_CurrencyModel();
                    $curr->setName($nameCurrency)
                            ->setStartDate($minCourse->getDate())
                            ->setStartValue($minCourse->getValue())
                            ->setEndDate($maxCourse->getDate())
                            ->setEndValue($maxCourse->getValue());
                    $messageUp->addCurrency($curr);      
                }
                //
                $minValue = $minCourse->getValueForOne()*(1-$percent/100);
                if ($minValue >= $maxCourse->getValueForOne()) {
                    $curr = new Mail_CurrencyModel();
                    $curr->setName($nameCurrency)
                            ->setStartDate($minCourse->getDate())
                            ->setStartValue($minCourse->getValue())
                            ->setEndDate($maxCourse->getDate())
                            ->setEndValue($maxCourse->getValue());
                    $messageDown->addCurrency($curr);                    
                }
            }
        }
        if ($messageUp->hasCurrencies()) {
            Core_Mail::quotationsUpMail($messageUp);
        }
        if ($messageDown->hasCurrencies()) {
            Core_Mail::quotationsDownMail($messageDown);
        }
        //$this->_helper->layout()->disableLayout(); 
        $this->_helper->viewRenderer->setNoRender(true);
    }
    

    public function courseAction() {
        $currencies = $this->getManager('currency')->fetchAll();
        $date = new Core_Date();
        if (!$this->getManager('courseCurrency')->getByDate($date)) {
            $xmlstr = file_get_contents(self::URL_COURCES.$date->format('d/m/Y'));
            $movies = new SimpleXMLElement($xmlstr);
            if (false !== strstr($xmlstr, $date->format('d.m.Y'))) {
                foreach ($movies->Valute as $item) {
                    $code = (string)$item['ID'];
                    if ($currencies->hasCode($code)) {
                        $course = $this->getManager('courseCurrency')->createModel();
                        $course->setCode($code)
                                        ->setNominal(str_replace(',','.',(string)$item->Nominal))
                                        ->setValue(str_replace(',','.',(string)$item->Value))
                                        ->setDate($date);
                        $this->getManager('courseCurrency')->insert($course);
                        file_put_contents(APPLICATION_PATH.'/../data/log/current'.date('dmYHi').'.log', '');
                    }
                }
            }
        }
        $this->_helper->viewRenderer->setNoRender(true);
        die('');
    }

}