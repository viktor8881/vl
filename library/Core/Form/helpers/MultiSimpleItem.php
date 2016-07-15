<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Helper_MultiSimpleItem
 *
 * @author Viktor Ivanov
 */


class Core_Form_Helper_MultiSimpleItem extends Zend_View_Helper_FormElement
{
    
    const ITEM_ELEMENT = '<li>%datavalue%<input type="hidden" name="%name%" value="%value%"><span title="Удалить" onclick="removeResponce(this)" class="form-el-multi-item__delete glyphicon glyphicon-remove"></span></li>';
        
    private $postFix = '';


    public function multiSimpleItem($name, $value = null, $attribs = null)
    {
        if (!is_array($value)) {
            $value = array();
        }
        if (!empty($attribs['data-postfix'])) {
            $this->postFix = $attribs['data-postfix'];
        }
//        $attribs['class'] = isset($attribs['class'])?$attribs['class'].' input-sm':'input-sm';
        $xhtml = '<div class="form-el-multi-item">
            <div class="input-group">
            '.$this->formText($name, '', $attribs).'
              <span class="input-group-btn">
                <button class="btn btn-default " type="button">
                    <span class="glyphicon glyphicon-plus"></span> Добавить
                </button>
              </span>
            </div>';
        $xhtml .= '<ul class="list-unstyled form-el-multi-item__list">';
            foreach($value as $val) {
                $xhtml .= str_replace(array('%name%', '%datavalue%', '%value%'), array($name.'[]', $val.$this->postFix, $val), self::ITEM_ELEMENT);
            }
        $xhtml .= '</ul></div>';
        $this->_jScript();
        return $xhtml;
    }
    
    
    public function formText($name, $value = null, $attribs = null) {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable
        // build the element
        $disabled = '';
        if ($disable) {
            // disabled
            $disabled = ' disabled="disabled"';
        }

        $xhtml = '<input type="text"'
                . ' name="' . $this->view->escape($name).'[]"'
                . ' id="' . $this->view->escape($id) . '"'
                . ' value="' . $this->view->escape($value) . '"'
                . $disabled
                . $this->_htmlAttribs($attribs)
                . $this->getClosingBracket();

        return $xhtml;
    }

    

    private function _jScript()
    {
        $this->view->headScript()->captureStart();
            echo 
        '$(function(){ '
            . '$(".form-el-multi-item button").click('
                    . 'function(){ '
                        . 'var el = $(".form-el-multi-item");'
                        . 'var input = $(el).find("input.form-control");'
                        . 'var inputVal = $(input).val().trim();'
                        . 'var allVal = new Array();'
                        . '$.each($(".form-el-multi-item__list input:hidden"), function(){ '
                        . '     allVal[allVal.length] = $(this).val(); '
                        . '}); '
                        . 'if(inputVal != "" && $.inArray(inputVal, allVal) == -1) {'
                            . '$(el).find("ul").append("<li>"+inputVal+"'.$this->postFix.'<input type=\"hidden\" value=\""+inputVal+"\" name=\""+$(input).attr("name")+"\"><span class=\"form-el-multi-item__delete glyphicon glyphicon-remove\" onclick=\"removeResponce(this)\" title=\"Удалить\"></span></li>");'
                        . '}'
                        . '$(input).val("");'
                    . '}); '
                    
        . '});'
        . 'function removeResponce(obj) {'
            . '$(obj).parent().remove();'
            . 'return false;'        
        . '}';
        $this->view->headScript()->captureEnd();
    }
    
    
}
