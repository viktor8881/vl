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
class View_Helper_FigureMetal_FigureName extends Zend_View_Helper_Abstract
{
    
    public function figureMetal_FigureName($figure)  {
        $name = '';
        switch ($figure) {
            case FigureMetal_Model::FIGURE_DOUBLE_TOP:
                $name = 'двойная вершина';
                break;
            case FigureMetal_Model::FIGURE_DOUBLE_BOTTOM:
                $name = 'двойное дно';
                break;
            case FigureMetal_Model::FIGURE_TRIPLE_TOP:
                $name = 'тройная вершина';
                break;
            case FigureMetal_Model::FIGURE_TRIPLE_BOTTOM:
                $name = 'тройное дно';
                break;
            case FigureMetal_Model::FIGURE_HEADS_HOULDERS:
                $name = 'голова и плечи';
                break;
            case FigureMetal_Model::FIGURE_RESERVE_HEADS_HOULDERS:
                $name = 'перевернутая голова и плечи';
                break;
            default:
                break;
        }
        return $name;
    }
        
}
