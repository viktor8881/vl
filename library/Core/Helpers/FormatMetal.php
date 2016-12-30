<?php
/**
 * вывод метал величин в форматированном виде
 */


class Core_Helper_FormatMetal extends Zend_View_Helper_Abstract 
{    
    
    /**
     * вывод формата числовых величин
     * @param type $number      -   форматируемое число
     * @param int $numFraction - сколько знаков после запятой оставить
     * @return string
     */
    public function formatMetal($sum) {
        return $this->view->formatNumber((float)$sum, Core_Container::getManager('setting')->getRoundMetal());
    }
    
    
}
