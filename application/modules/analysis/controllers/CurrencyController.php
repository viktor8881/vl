<?php

class Analysis_CurrencyController extends Core_Controller_Action
{

    
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
        
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }    
    
}

