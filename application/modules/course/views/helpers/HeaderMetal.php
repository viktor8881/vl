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
class Course_View_Helper_HeaderMetal extends Zend_View_Helper_Abstract
{
    
    public function headerMetal(Metal_Collection $currencies, Metal_Model $currentCurrency) {
        $xhtml = '<h3>';
            $xhtml .= _('Курс металла');
            $xhtml .= ' <select name="id" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);">';
            foreach($currencies as $currency) {
                if ($currency->getId() == $currentCurrency->getId()) {
                    $selected = 'selected="selected"';
                    $value = '';
                }else{
                    $selected ='';
                    $value = '/course/metal/index/id/'.$currency->getId();
                }
                $xhtml .= '<option value="'.$value.'" '.$selected.'>'.$this->view->escape($currency->getName()).'</option>';
            }
            $xhtml .= '</select>';
        $xhtml .= '</h3>';
        return $xhtml;
    }
        
}
