<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form
 *
 * @author Victor
 */
class Core_Form extends Zend_Form {
    
    public function __construct($options = null) 
    {
        $this->addElementPrefixPath('Core_Form_Decorator',
                            'Core/Form/Decorator',
                            'decorator');
        if (!isset($options['class'])) {
            $this->setAttrib('class', 'form-horizontal');
        }        
        $this->setAttrib('autocomplete',"off");
        
        // устанавливаем свой класс для группировки элементов
        $this->setDefaultDisplayGroupClass('Core_Form_DisplayGroup');
        
        parent::__construct($options);
        
        
        if (!isset($options['token']) or false!==$options['token'] ){
            // добавление токкена к форме
            $salt = Zend_Crypt::hash('MD5', uniqid() . microtime());        
            $csrfToken = new Core_Form_Element_Hash('token');
            $csrfToken->setTimeout(1200);
            $csrfToken->setSalt($salt);
            $this->addElement($csrfToken);
        }
        $htmlTag = $this->getDecorator('HtmlTag');
        if ($htmlTag) {
            $htmlTag->setTag('div');
        }
    }
    
    /**
     * получить описание первой ошибки
     * @return string | null
     */
    public function getErrorMessageFirst()
    {
        if ($this->isErrors()) {
            return $this->firstMessage($this->getMessages());
        }
        return null;
    }
    
    private function firstMessage($messages) {
        if (is_array($messages)) {
            return $this->firstMessage(current($messages));
        }else{
            return $messages;
        }
    }

    

    /**
     * Добавить кнопки "Отмена" и "Сохранить"
     * @param array $options
     * @return Core_Form
     */
    public function addButtonsAction(array $options=array())
    {
        // кнопка субмит
        if (isset($options['submit']['name'])) {
            $buttonSubmitName = $options['submit']['name'];
            unset($options['submit']['name']);
        }else{
            $buttonSubmitName = 'buttonsubmit';
        }
        if (!isset($options['submit']['decorators'])) {
            $options['submit']['decorators'] = array('decorators'=>array('ViewHelper'));
        }
        $buttonSubmit = new Core_Form_Element_Submit($buttonSubmitName, $options['submit']);
        
        // кнопка отмены
        if (isset($options['cancel']['name'])) {
            $buttonCancelName = $options['cancel']['name'];
            unset($options['cancel']['name']);
        }else{
            $buttonCancelName = 'buttoncancel';
        }
        if (!isset($options['cancel']['decorators'])) {
            $options['cancel']['decorators'] = array('decorators'=>array('ViewHelper'));
        }
        $options['cancel']['class'] = 'btn btn-link';
        $buttonCancel = new Core_Form_Element_ButtonCancel($buttonCancelName, $options['cancel']);
                        
        $this->addDisplayGroup(array($buttonCancel, $buttonSubmit), 'groupButton');        
        $this->getDisplayGroup('groupButton')
                ->setOrder(1000000)
                ->addDecorator(new Core_Form_Decorator_WrapperElement(array('class'=>'col-sm-offset-3 col-sm-9')))
                    ->addDecorator(new Core_Form_Decorator_BootstrapControl());
        return $this;
    }

    
}
