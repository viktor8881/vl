<?php

class Analysis_MetalController extends Core_Controller_Action
{

    public function indexAction() {
        $metal = $this->getManager('metal')->get((int)$this->getParam('id'));
        if (!$metal) {
            throw new Core_Domen_NotFoundException(_('Метал не найден.'));
        }
        if (!Zend_Validate::is($this->getParam('date'), 'Date', array(), 'Core_Validate')) {
            throw new Core_Exception(_('Не верный формат даты.'));
        }
        $date = new Core_Date($this->getParam('date'));
        $dataChart = [];
        
        $analyzes = $this->getManager('AnalysisMetal')->fetchAllByMetalCodeDate($metal->getCode(), $date);
        $figure = $analyzes->getFigureByLongest();
        if ($figure) {
            foreach ($figure->getCacheCourses() as $cacheCourse) {
                $num=1; //  != 0 for calculate step figure
                foreach ($cacheCourse->getDataValue() as $values) {
                    $key = $values['data'];
                    if (isset($dataChart[$key])) {
                        continue;
                    }
                    $chart = new Model_DataChart();
                    $chart->setDate(new Core_Date($values['data']))
                            ->setValue($values['value'])
                            ->setValueFigure($cacheCourse->getValueFigureByNum($num));
                    $dataChart[$key] = $chart;
                    $num++;
                }
            }
        }
        
        $this->view->analyzes = $analyzes;
        $this->view->metal = $metal;
        $this->view->date = $date;
        $this->view->dataChart = $dataChart;
    }
    
}