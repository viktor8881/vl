<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Hidden
 *
 */
class Core_Form_Helper_HiddenData extends Zend_View_Helper_FormHidden
{
    
    public function hiddenData($name, $value = '', $attribs = null, $options = null)
    {
        if (!empty($options['data'])) {
            $data = $options['data'];
        }else{
            $data = $value;
        }
        $data = $this->view->escape($data);
        return '<p class="form-control-static">'.$data.'</p>'.parent::formHidden($name, $value, $attribs);
    }
    
    
}

