<?php



class Core_Helper_FormatNumber extends Zend_View_Helper_Abstract 
{    
    
    /**
     * вывод формата числовых величин
     * @param type $number      -   форматируемое число
     * @param int $numFraction - сколько знаков после запятой оставить
     * @return string
     */
    public function formatNumber($number, $numFraction=2 )
    {        
        return '<span class="nowrap">'.number_format((float)$number, (int)$numFraction, '.', ' ').'</span>';
    }
    
    
}
