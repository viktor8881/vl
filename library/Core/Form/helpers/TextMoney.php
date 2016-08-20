<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Helper_LoginAzs
 *
 * @author Viktor Ivanov
 */
require_once 'Core/Form/helpers/TextAppend.php';


class Core_Form_Helper_TextMoney extends Core_Form_Helper_TextAppend
{
    
    public function textMoney($name, $value = null, $attribs = null)
    {
        return 
        '<div class="row">'
            . '<div class="col-sm-3">'.parent::textAppend($name, $value, $attribs).'</div></div>';
    }
    
    
}
