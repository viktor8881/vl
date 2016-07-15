<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Element_File
 *
 * @author Victor
 */
class Core_Form_Element_File extends Zend_Form_Element_File {
    
    
    private $_base64 = null;




    public function __construct($spec, $options = null) {
        parent::__construct($spec, $options);
        $this->addPrefixPath(
            'Core_Form_Decorator',
            'Core/Form/Decorator',
            'decorator'
        );
        $this->clearDecorators();
        $this->addDecorator('File2')
                ->addDecorator('Errors')
                ->addDecorator('Description')
                ->addDecorator('HtmlTag', array('class'=>'col-sm-9'))
                ->addDecorator('Label', array('class'=>'col-sm-3'))
                ->addDecorator(new Core_Form_Decorator_BootstrapControl());
    }
    
    /**
     * установить значение картинки в формате base64
     * @param type $value
     * @return \Core_Form_Element_File
     */
    public function setValueBase64($value)
    {
        $this->_base64 = $value;
        return $this;
    }
    
    /**
     * получить значение base64
     * @return type
     */
    public function getValueBase64()
    {
        return $this->_base64;
    }
    
    public function isValid($value, $context = null) {
        if (!count($this->getValidators())) {
            $this->_validated = true;
        }
        return parent::isValid($value, $context);
    }
    
    
    
}
