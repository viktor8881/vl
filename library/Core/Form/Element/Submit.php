<?php

/**
 * наш сабмит
 */
class Core_Form_Element_Submit extends Zend_Form_Element_Submit 
{

    public function   __construct($spec, $options = null) {
        if (!isset($options['class'])) {
            $options['class'] = 'btn btn-primary';
        }
        if (!isset($options['label'])) {
            $options['label'] = 'Сохранить';
        }
        if (!isset($options['data-loading-text'])) {
            $options['data-loading-text'] = 'Сохранение...';
        }
        parent::__construct($spec, $options);
        if (!isset($options['decorators'])) {
            $this->setDecorators(array('ViewHelper'));
            $this->addDecorator(new Core_Form_Decorator_WrapperElement(array('class'=>'col-sm-offset-3 col-sm-9')))
                    ->addDecorator(new Core_Form_Decorator_BootstrapControl());
        }
    }
    

}
