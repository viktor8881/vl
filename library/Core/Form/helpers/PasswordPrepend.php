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


class Core_Form_Helper_PasswordPrepend extends Zend_View_Helper_FormPassword
{
        
    
    
    public function passwordPrepend($name, $value = null, $attribs = null)
    {
        $text = '...';
        if (isset($attribs['text'])) {
            if (!isset($attribs['text-noescape'])) {
                unset($attribs['text-noescape']);
                $text = $this->view->escape($attribs['text']);
            }else{
                $text = $attribs['text'];
            }
            unset($attribs['text']);
        }
        return '<div class="input-group"><span class="input-group-addon">'.$text.'</span>'.$this->formPassword($name, $value, $attribs).'</div>';
    }
    
    
}
