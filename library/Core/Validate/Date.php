<?php

/**
 * валидация даты
 */
class Core_Validate_Date extends Zend_Validate_Date
{

    
    protected $_options = array('format'=>'dd.MM.YYYY');    
    
    
    public function __construct(array $options=array() )
    {
        parent::setMessage(_('Не верный формат даты'), Zend_Validate_Date::FALSEFORMAT);
        $options = array_merge($this->_options, $options);
        parent::__construct($options);
    }
    
    
    public function isValid($value)
    {
        return parent::isValid($value);
    }

}
