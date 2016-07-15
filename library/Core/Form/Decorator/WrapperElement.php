<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Decorator_WrapperElement
 *
 * @author Viktor Ivanov
 */
class Core_Form_Decorator_WrapperElement extends Zend_Form_Decorator_Abstract {
    
    private $_tag='div';
    private $_params=array();
    
    public function __construct($options=null) 
    {
        if (isset($options['tag']) && is_string($options['tag'])) {
            $this->_tag = $options['tag'];
        }
        if ($options && is_array($options)) {
            foreach ($options as $key=>$option) {
                if ($key == 'tag') { continue; }
                $this->_params[] = $key.'="'.$option.'"';
            }
        }
        parent::__construct($options);
    }
    
    public function render($content) 
    {
        if ($this->_tag){
            $content = '<'.$this->_tag.' '.implode(' ', $this->_params).'>'.$content.'</'.$this->_tag.'>';
        }
        return $content;
    }
    
}
