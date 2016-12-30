<?php

class Analysis_CurrencyController extends Core_Controller_Action
{

    public function indexAction() {
        $currency = $this->getManager('currency')->get((int)$this->getParam('id'));
        if (!$currency) {
            throw new Core_Domen_NotFoundException(_('Валюта не найдена.'));
        }
        if (!Zend_Validate::is($this->getParam('date'), 'Date', array(), 'Core_Validate')) {
            throw new Core_Exception(_('Не верный формат даты.'));
        }
        $date = new Core_Date($this->getParam('date'));
        $dataChart = [];
        
        $analyzes = $this->getManager('AnalysisCurrency')->fetchAllByCurrencyCodeDate($currency->getCode(), $date);
        $figure = $analyzes->getFigureByLongest();
        if ($figure) {
            foreach ($figure->getCacheCourses() as $cacheCourse) {
                $num=1; //  != 0 for calculate step figure
                foreach ($cacheCourse->getDataValue() as $values) {
                    $key = $values['data'];
                    if (isset($dataChart[$key])) {
                        continue;
                    }
                    $model = new Model_DataChart();
                    $model->setDate(new Core_Date($values['data']))
                            ->setValue($values['value'])
                            ->setValueFigure($cacheCourse->getValueFigureByNum($num));
                    $dataChart[$key] = $model;
                    $num++;
                }
            }
        }
        
        
        $this->view->analyzes = $analyzes;
        $this->view->currency = $currency;
        $this->view->date = $date;
        $this->view->dataChart = $dataChart;
    }
    
}