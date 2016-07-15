<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Element_Hidden
 *
 * @author Victor
 */
class Core_Form_Element_Hash extends Zend_Form_Element_Hash {
    
    
    public function   __construct($spec, $options = null) {
        parent::__construct($spec, $options);
        $this->setDecorators(array('ViewHelper'));
    }

}
