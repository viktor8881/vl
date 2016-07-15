<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_SubForm
 *
 * @author Viktor Ivanov
 */
class Core_Form_DisplayGroup  extends Zend_Form_DisplayGroup {
    
    
    public function __construct($name, Zend_Loader_PluginLoader $loader, $options = null)
    {
        parent::__construct($name, $loader,$options);
        $this->addPrefixPath(
            'Core_Form_Decorator',
            'Core/Form/Decorator'
        );
        $this->removeDecorator('DtDdWrapper');
        $this->removeDecorator('HtmlTag');
    }
    
    
}

