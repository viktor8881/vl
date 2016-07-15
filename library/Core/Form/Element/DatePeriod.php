<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Элемент указания кол-во и ед измерения идиниц
 *
 * @author Viktor Ivanov
 */
class Core_Form_Element_DatePeriod extends Zend_Form_Element_Xhtml {
    
    const POSTFIX_START  = 'start';
    const POSTFIX_END    = 'end';
    
    public $helper = 'datePeriod';
    
    public $options=array(
        'start'=>array(),
        'end'=>array()
        );
    
    
    
    public function __construct($spec, $options = null) {
        $options['class'] = isset($options['class'])?$options['class'].' form-control':'form-control';
        parent::__construct($spec, $options);
        if (!isset($options['decorators'])) {
            $this->setDecorators(array(
                    'ViewHelper',
                    'Errors',
                    'Description',
                    array('HtmlTag'=>new Core_Form_Decorator_HtmlTag(array('class'=>'col-sm-9'))),
                    array('Label'=>new Core_Form_Decorator_Label(array('class'=>'col-sm-3'))),
                    ));
            $this->addDecorator(new Core_Form_Decorator_BootstrapControl());
        }
        $view = $this->getView();
        if ($view) {
            $view->addBasePath('Core/Form', 'Core_Form');
        }
        parent::__construct($spec, $options);
        $this->addValidator(new Core_Validate_DatePeriod());
    }
    
    
    public function setDateStart($date)
    {
        $this->_value[self::POSTFIX_START] = $date;
        return $this;
    }
    
    public function setDateEnd($date)
    {
        $this->_value[self::POSTFIX_END] = $date;
        return $this;
    }
    
    public function setMinDate($date)
    {
        $this->options[self::POSTFIX_START]['minDate'] = $date;
        $this->options[self::POSTFIX_END]['minDate'] = $date;
        return $this;
    }
    
    public function setMaxDate($date)
    {
        $this->options[self::POSTFIX_START]['maxDate'] = $date;
        $this->options[self::POSTFIX_END]['maxDate'] = $date;
        return $this;
    }
    
    /**
     * получить значение даты начала
     * @return type
     */
    public function getValueStart() {
        $value = parent::getValue();
        return isset($value[self::POSTFIX_START])?$value[self::POSTFIX_START]:null;
    }
    
    /**
     * получить значение даты окончания
     * @return type
     */
    public function getValueExpire() {
        $value = parent::getValue();
        return isset($value[self::POSTFIX_END])?$value[self::POSTFIX_END]:null;
    }
    
}
