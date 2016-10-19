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
                $name = 'двойная вершина';
                break;
            case AnalysisCurrency_Model_Figure::FIGURE_DOUBLE_BOTTOM:
                $name = 'двойное дно';
                break;
            case AnalysisCurrency_Model_Figure::FIGURE_TRIPLE_TOP:
                $name = 'тройная вершина';
                break;
            case AnalysisCurrency_Model_Figure::FIGURE_TRIPLE_BOTTOM:
                $name = 'тройное дно';
                break;
            case AnalysisCurrency_Model_Figure::FIGURE_HEADS_HOULDERS:
                $name = 'голова и плечи';
                break;
            case AnalysisCurrency_Model_Figure::FIGURE_RESERVE_HEADS_HOULDERS:
                $name = 'перевернутая голова и плечи';
                break;
            default:
                break;
        }
        return $name;
    }
        
}
