<?php

/**
 * фильтр заменяет запятые на точки
 */
class Core_Filter_Float implements Zend_Filter_Interface
{

    public function filter($value)
    {        
        $value = str_replace(',', '.', $value);        
        return $value;
    }

}