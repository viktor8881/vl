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


class Core_Form_Helper_PasswordGen extends Zend_View_Helper_FormText
{
        
    
    
    public function passwordGen($name, $value = null, $attribs = null)
    {
        $this->view->headScript()->captureStart();
        echo 
        '$(function(){
                $(".gen-password").click(
                    function(){
                        $(this).parents(".input-group:eq(0)").find("input").val(password(8));
                    }
                );
            });            
        ';
        echo 'function password(length) {
            var iteration = 0;
            var password = "";
            var randomNumber;

            while(iteration < length){
                randomNumber = (Math.floor((Math.random() * 100)) % 94) + 33;
                if ((randomNumber >=33) && (randomNumber <=47)) {continue;}
                if ((randomNumber >=58) && (randomNumber <=64)) {continue;}
                if ((randomNumber >=91) && (randomNumber <=96)) {continue;}
                if ((randomNumber >=123) && (randomNumber <=126)) {continue;}
                iteration++;
                password += String.fromCharCode(randomNumber);
            }
            return password;}';
        $this->view->headScript()->captureEnd();
        
        $xhtml = '<div class="input-group">'.$this->formText($name, $value, $attribs);
        $xhtml .= '<span class="input-group-btn"><button class="btn btn-default gen-password" type="button" title="'._('Сгенерировать пароль').'">'._('Сгенерировать пароль').'</button></span></div>';
        return $xhtml;
    }
    
    
}
