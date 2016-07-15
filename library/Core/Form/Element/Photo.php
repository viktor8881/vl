<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Element_Photo
 *
 * @author victor
 */
class Core_Form_Element_Photo extends Zend_Form_Element_Multi {
    
    /**
     * Имя хелпера
     * @var string
     */
    public $helper = 'photo';
    
    public $options=array(
        'url-upload'=>'/',
        'path-img'   =>'/img/'
        );
    
    
    public function __construct($spec, $options = null) {
        
        $options['class'] = isset($options['class'])?$options['class'].' form-control':'form-control';
        parent::__construct($spec, $options);
        if (!isset($options['decorators'])) {
            $this->setDecorators(array(
                'ViewHelper',
                'Errors',
                array('Description'=>new Core_Form_Decorator_Description(array('class'=>'text-center small'))),
                array('HtmlTag'=>new Core_Form_Decorator_HtmlTag(array('class'=>'col-sm-9'))),
                array('Label'=>new Core_Form_Decorator_Label(array('class'=>'col-sm-3'))),
                ));
            $this->addDecorator(new Core_Form_Decorator_BootstrapControl());
        }
        $this->setRegisterInArrayValidator(false);
        $this->setIsArray(true);
    }
    
    /**
     * Высчитывает только указанные пользователем опции и их устанавливает
     *
     * @param  array $options
     * @return Core_Form_Element_HiddenData
     */
    public function setOptions(array $options)
    {
        $diff = array_intersect_key($options, $this->options);
        $this->options = array_merge($this->options, $diff);
        foreach ($diff as $key => $option) {
            unset ($options[$key]);
        }        
        parent::setOptions($options);
        return $this;
    }
    
    
}
