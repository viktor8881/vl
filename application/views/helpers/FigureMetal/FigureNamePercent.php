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
class View_Helper_FigureMetal_FigureNamePercent extends Zend_View_Helper_Abstract
{
    
    public function figureMetal_FigureNamePercent( $figure)  {
        $name = $this->view->figureMetal_FigureNamePercent($figure->getFigure());
        return $name.' ('.$figure->getPercentCacheCources().')';
    }
        
}
