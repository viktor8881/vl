<?php
/**
 * вывод процентных величин в форматированном виде
 */


class Core_Helper_FormatPercent extends Zend_View_Helper_Abstract 
{    
    
    /**
     * 
     * @param numeric $sum
     * @param boolean $itemName
     * @return string
     */
    public function formatPercent($sum, $itemName=false)
    {
        $formatSum = $this->view->formatNumber((float)$sum, Core_Container::getManager('setting')->getRoundPercent());
        if ($itemName) {
            $formatSum.=' %';
        }
        return $formatSum;
    }
    
    
}
