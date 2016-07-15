<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Decorator_NameDecor
 *
 * @author Victor
 */
class Core_Form_Decorator_File2 extends Zend_Form_Decorator_File {
    
    
    public function render($content) 
    {
        $element = $this->getElement();
        if ( $element->isRequired() && strlen($element->getLabel()) ) {
            $element->setLabel($element->getLabel().' *');
        }
        
        $view = $element->getView();
        if (!$view instanceof Zend_View_Interface) {
            return $content;
        }
        $view->headScript()->appendFile($view->baseUrl().'/js/jasny-bootstrap.js');
        $view->headLink()->appendStylesheet($view->baseUrl().'/css/jasny-bootstrap.min.css');

        $value = null;
        if ($element->getValueBase64()) {
            $value = '<img src="data:image/png;base64,'.$element->getValueBase64().'" />';
            $view->headScript()->captureStart();
            echo '$(function(){
                    $(".fileinput").fileinput("view");
                })';
            $view->headScript()->captureEnd();
        }

        $xhtml = '
            <div class="fileinput fileinput-new" data-provides="fileinput">
              <div class="fileinput-new thumbnail" style="width: 100px; height: 100px;">
                <img src="'.$view->baseUrl().'/img/no-photo-user.jpg" alt="'._('Нет фото.').'">
              </div>
              <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 100px; max-height: 100px;">
              '.$value.'
              </div>
              <div>
                <span class="btn btn-default btn-file">
                    <span class="fileinput-new">'._('Выберите фото').'</span>
                    <span class="fileinput-exists">'._('Сменить').'</span>'
                    .parent::render($content).'
                </span>
                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">'._('Удалить').'</a>
              </div>
            </div>';
         return $xhtml;
    }
    
}

