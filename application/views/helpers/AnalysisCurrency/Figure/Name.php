<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 
 *
 * @author Viktor Ivanov
 */
class View_Helper_AnalysisCurrency_Figure_Name extends Zend_View_Helper_Abstract
{
    
    public function analysisCurrency_Figure_Name($figure)  {
        $name = '';
        switch ($figure) {
            case AnalysisCurrency_Model_Figure::FIGURE_DOUBLE_TOP:
                $name = _('двойная вершина');
                break;
            case AnalysisCurrency_Model_Figure::FIGURE_DOUBLE_BOTTOM:
                $name = _('двойное дно');
                break;
            case AnalysisCurrency_Model_Figure::FIGURE_TRIPLE_TOP:
                $name = _('тройная вершина');
                break;
            case AnalysisCurrency_Model_Figure::FIGURE_TRIPLE_BOTTOM:
                $name = _('тройное дно');
                break;
            case AnalysisCurrency_Model_Figure::FIGURE_HEADS_HOULDERS:
                $name = _('голова и плечи');
                break;
            case AnalysisCurrency_Model_Figure::FIGURE_RESERVE_HEADS_HOULDERS:
                $name = _('перевернутая голова и плечи');
                break;
            default:
                break;
        }
        return $name;
    }
        
}
