<?php
/**
 * вывод денежных величин в форматированном виде
 */


class Core_Helper_FormatMoney extends Zend_View_Helper_Abstract 
{    
    
    /**
     * вывод формата числовых величин
     * @param type $number      -   форматируемое число
     * @param int $numFraction - сколько знаков после запятой оставить
     * @return string
     */
    public function formatMoney($sum, $itemName=false)
    {
        $formatSum = $this->view->formatNumber((float)$sum, Core_Container::getManager('setting')->getRoundMoney());
        if ($itemName) {
            $formatSum.=' '.Core_Container::getManager('setting')->getMoneyUnit();
        }
        return $formatSum;
    }
    
    
}
