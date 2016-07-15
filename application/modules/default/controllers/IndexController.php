<?php

class IndexController extends Zend_Controller_Action
{

    const PERIOD = 5;
    const PERCENT = 10;
    
    const URL_COURCES = 'http://www.cbr.ru/scripts/XML_daily.asp?date_req=';
    
    private $_codes = array(
        'R01235'=>'Доллар США', 
        'R01239'=>'Евро',        
        'R01035'=>'Фунт стерлингов',
        'R01820'=>'Японская иена',
        'R01775'=>'Швейцарский франк',
        'R01010'=>'Австралийский доллар',
        'R01350'=>'Канадский доллар');
    
    public function testAction() {
        die('testAction');
    }
    
    private function sendAction() {
//    public function sendAction() {
        $mess = new Mail_Model();
        $mess->setDay(3)
            ->setPercent(8);
            $curr = new Mail_CurrencyModel();
            $curr->setName("Долларь")
                    ->setStartDate(new Core_Date('2016-10-12'))
                    ->setStartValue(12.548)
                    ->setEndDate(new Core_Date)
                    ->setEndValue(17.548);
        $mess->addCurrency($curr);
            $curr = new Mail_CurrencyModel();
            $curr->setName("Манатта")
                    ->setStartDate(new Core_Date('2016-04-14'))
                    ->setStartValue(42.356048)
                    ->setEndDate(new Core_Date)
                    ->setEndValue(52.65824);
        $mess->addCurrency($curr);        
        $this->view->mess = $mess;        
        Core_Mail::quotationsUpMail($mess);
        Core_Mail::quotationsDownMail($mess);        
    }

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
        foreach (Core_Container::getManager('currency')->listCurrencies() as $code=>$nameCurrency) {
            $rows = Core_Container::getManager('course')->fetchAllByPeriodByCode($dateLater, $dateNow, $code);
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
        $listCurrencies = Core_Container::getManager('currency')->listCurrencies();
        $date = new Core_Date();
        if (!Core_Container::getManager('course')->getByDate($date)) {
            $xmlstr = file_get_contents(self::URL_COURCES.$date->format('d/m/Y'));
// 	$xmlstr = file_get_contents(APPLICATION_PATH.'/../data/sources/cource.xml');
            $movies = new SimpleXMLElement($xmlstr);
            if (false !== strstr($xmlstr, $date->format('d.m.Y'))) {
                foreach ($movies->Valute as $item) {
                    $code = (string)$item['ID'];
                    if (array_key_exists($code, $listCurrencies)) {
                        $course = Core_Container::getManager('course')->createModel();
                        $course->setCode($code)
                                        ->setNominal(str_replace(',','.',(string)$item->Nominal))
                                        ->setValue(str_replace(',','.',(string)$item->Value))
                                        ->setDate($date);
                        Core_Container::getManager('course')->insert($course);
                        file_put_contents(APPLICATION_PATH.'/../data/log/current'.date('dmYHi').'.log', '');
                    }
                }
            }
        }
        $this->_helper->viewRenderer->setNoRender(true);
        die('');
    }

    public function valutaAction() {
        die('');
        $xmlstr = file_get_contents(APPLICATION_PATH.'/../data/sources/valuta.xml');
        $movies = new SimpleXMLElement($xmlstr);
        foreach ($movies->Item as $item) {             
            $currency = Core_Container::getManager('currency')->createModel();
            $currency->setCode((string)$item['ID'])
                    ->setName((string)$item->Name);
//            pr($currency); exit;
            Core_Container::getManager('currency')->insert($currency);
        }
        die('');
    }
    
    public function recordAction()
    {
        die('');
        $xmlstr = file_get_contents(APPLICATION_PATH.'/../data/sources/test.xml');
        $movies = new SimpleXMLElement($xmlstr);
        foreach ($movies->Record as $record) {             
            $course = Core_Container::getManager('course')->createModel();
            $course->setCode((string)$record['Id'])
                    ->setNominal(str_replace(',','.',(string)$record->Nominal))
                    ->setValue(str_replace(',','.',(string)$record->Value))
                    ->setDate((string)$record['Date']);
            Core_Container::getManager('course')->insert($course);
        }
        die('');
    }


}

