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
class Core_Form_Decorator_File extends Zend_Form_Decorator_File {
    
    
    const WIDTH = 100;
    const HEIGHT = 75;
    
    const CLASS_ADD = 'glyphicon-plus';
    const CLASS_SUB = 'glyphicon-minus';
    
    private static $_mess = array(
        self::CLASS_ADD => 'Добавить фото',
        self::CLASS_SUB => 'Удалить фото',
    );
    
    private static $_classAction = array(
        self::CLASS_ADD => 'add-images',
        self::CLASS_SUB => 'sub-images',
    );




    public function render($content) 
    {
        $xhtml = '';
        $element = $this->getElement();
        if ( $element->isRequired() && strlen($element->getLabel()) ) {
            $element->setLabel($element->getLabel().' *');
        }
        
        $view = $element->getView();
        if (!$view instanceof Zend_View_Interface) {
            return $content;
        }
//        $view->headScript()->appendFile($view->baseUrl().'/js/bootstrap-fileupload.js');
        $view->headScript()->appendFile('js/bootstrap-fileupload.min.js');
        $view->headLink()->appendStylesheet($view->baseUrl().'/css/bootstrap-fileupload.min.css');
        $value = null;
        if ($element->hasValueImg()) {
            $valuesImg = $element->getValueImg();
            $count = count($valuesImg);
            $i=1;
            foreach ($valuesImg as $img) {
                $classButton = ($i++ == $count)?self::CLASS_ADD:self::CLASS_SUB;
                $xhtml .= $this->_getBlockElement($content, '<img src="'.$img.'" />', $classButton);
            }
            $view->headScript()->captureStart();
            echo '$(function(){
                    $(".fileupload").fileupload("view");
                })';
            $view->headScript()->captureEnd();
        }else{
            $xhtml .= $this->_getBlockElement($content);
        }
        $this->_scripts();
        return $xhtml;
    }
    
    
    private function _scripts()
    {
        $view = $this->getElement()->getView();
        
        $view->headScript()->captureStart();
        echo '$(function() {                
                $("#element-images").on("click", ".fileupload .'.self::$_classAction[self::CLASS_ADD].'",
                    function(){
                        var block = $(this).parents("div.fileupload");
                        $(block).after( $(block).clone() );
                        $(".fileupload:last").fileupload("clear");
                        $(this).find("span")
                            .removeClass("'.self::CLASS_ADD.'")
                            .addClass("'.self::CLASS_SUB.'");
                        $(this).attr("title", "'.self::$_mess[self::CLASS_SUB].'")
                            .removeClass("'.self::$_classAction[self::CLASS_ADD].'")
                            .addClass("'.self::$_classAction[self::CLASS_SUB].'");
                    }
                );
                
                $("#element-images").on("click", ".fileupload .'.self::$_classAction[self::CLASS_SUB].'",
                    function(){
                        $(this).parents("div.fileupload").remove();
                    }
                );
                
            })';
        $view->headScript()->captureEnd();
    }
    
    
    
    private function _getBlockElement($content, $value=null, $class=self::CLASS_ADD)
    {
        $xhtml = '
         <div class="fileupload fileupload-new" data-provides="fileupload">
            <div class="fileupload-new thumbnail" style="width: '.self::WIDTH.'px; height: '.self::HEIGHT.'px;">
                <img src="img/nonepic_s.png" />  
            </div>
            <div class="fileupload-preview fileupload-exists thumbnail" style="width: '.self::WIDTH.'px; height: '.self::HEIGHT.'px;">'
            .$value.
            '</div>
            <span class="btn btn-file btn-default">
                <span class="fileupload-new">'._('Выберите фото').'</span>
                <span class="fileupload-exists">'._('Сменить').'</span>'
                .parent::render($content).
                '</span>
                <button type="button" title="'.self::$_mess[$class].'" class="btn btn-default pull-right '.self::$_classAction[$class].'" style="margin-top:20px;">
                    <span class="glyphicon '.$class.'" ></span>
                </button>
            <a href="#" class="btn btn-warning fileupload-exists" data-dismiss="fileupload">'._('Удалить').'</a>
        </div>';
        return $xhtml;
    }
    
    
}

