<?php

/**
 * проверка что введенные данные целое число и положительное
 */
class Core_Validate_FloatPositive extends Zend_Validate_Float
{

    const NOT_POSITIVE = 'notPositive';
    
    private $_zero=null;


    /**
     * 
     * @param bollean $zero - раздрешен ли ноль
     * @param type $locale
     */
    public function  __construct($zero=false) 
    {
        $this->_zero = $zero;
        $this->_messageTemplates[self::NOT_POSITIVE]= "Значение должно быть положительным";
        // установим локаль в en_EN чтоб разделителем была точка '.'
        parent::__construct('en_EN');
    }



    public function isValid($value)
    {
        if (parent::isValid($value)===true ){
            if ($this->_zero && $value==0){ return true; }
            if ($value <= 0){
                parent::_error(self::NOT_POSITIVE);
                return false;
            }else{
                return true;
            }
        }
        return false;                
    }

}
