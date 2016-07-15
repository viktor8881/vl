<?php



class Core_Helper_FormatInt extends Zend_View_Helper_Abstract 
{    
    
    /**
     * вывод формата целых числовых величин
     * @param type $number      -   форматируемое число
     * @param int $numFraction - сколько знаков после запятой оставить
     * @return string
     */
    public function formatInt($number )
    {
        return $this->view->formatNumber((int)$number, 0);
    }
    
    
}
