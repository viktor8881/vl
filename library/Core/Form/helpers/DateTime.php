<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Helper_PriceFuel
 *
 * @author Viktor Ivanov
 */


class Core_Form_Helper_DateTime extends Zend_View_Helper_FormElement
{
    
    const DEFAULT_ICON = 'glyphicon-calendar';


    public function dateTime($name, $value = '', $attribs = null, $options = null)
    {
        $mainOptions = $options;
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable
        
        $id = $this->view->escape($id);
        $value = $this->view->escape($value);
        
        $this->view->headLink()->prependStylesheet($this->view->baseUrl().'/css/bootstrap-datetimepicker.min.css');
        $this->view->headScript()->appendFile($this->view->baseUrl().'/js/moment-with-locales.min.js');
        $this->view->headScript()->appendFile($this->view->baseUrl().'/js/bootstrap-datetimepicker.min.js');
        $params = array();
        foreach ($mainOptions as $key=>$val) {
            if(is_null($val) or $key=='format') { continue; }
            if (is_string($val)) {
                $val = '"'.$val.'"';
            }elseif(is_numeric($val)){
                $val = (int)$val;
            }else{
                $val = $val?'true':'false';
            }
            $params[] = $key.':'.$val;
        }
        
        $this->view->headScript()->captureStart();
            echo "$(function () { $('#".$id."').datetimepicker({".implode(',',$params)."}); });";
        $this->view->headScript()->captureEnd();
        
        // build the element
        $disabled = '';
        if ($disable) {
            // disabled
            $disabled = ' disabled="disabled"';
        }

        // XHTML or HTML end tag?
        $endTag = ' />';
        if (($this->view instanceof Zend_View_Abstract) && !$this->view->doctype()->isXhtml()) {
            $endTag= '>';
        }
        if (isset($mainOptions['format'])) {
            $attribs['data-format'] = $mainOptions['format'];
        }
        // определяем иконку
        if (isset($attribs['icon'])) {
            $icon = $attribs['icon'];
            unset($attribs['icon']);
        }else{
            $icon = self::DEFAULT_ICON;
        }
        
        $xhtml = '<div class="input-group datetimepicker" id="' .$id. '" >
                <input type="text" ' 
                . ' name="' . $this->view->escape($name) . '"'
                . ' value="' . $value . '"'
                . ' title="' . _('Для установки даты/времени нажмите на иконку календаря/часов.'). '"'
                . $disabled
                . $this->_htmlAttribs($attribs)
                . $endTag.'<span class="input-group-addon"><span class="glyphicon '.$icon.'"></span></span>'
                .'</div>';

        return $xhtml;
    }
    
    
    
    
}
