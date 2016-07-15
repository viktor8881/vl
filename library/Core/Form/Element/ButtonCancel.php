<?php

/**
 * наш сабмит
 */
class Core_Form_Element_ButtonCancel extends Zend_Form_Element_Button 
{

    public function   __construct($spec, $options = null) {        
        if (!isset($options['class'])) {
            $options['class'] = 'btn';
        }
        if (!isset($options['label'])) {
            $options['label'] = 'Отмена';
        }
        if (!isset($options['onclick'])) {
            if (empty($options['returnUrl'])) {
                $url = Core_Acr::getParentUrl();
            }else{
                $url = $options['returnUrl'];
                unset($options['returnUrl']);
            }
            $options['onclick'] = 'window.location.href = "'.$url.'";';
        }
        parent::__construct($spec, $options);
        if (!isset($options['decorators'])) {
            $this->setDecorators(array('ViewHelper'));
            $this->addDecorator(new Core_Form_Decorator_WrapperElement(array('class'=>'col-sm-offset-3 col-sm-9')))
                    ->addDecorator(new Core_Form_Decorator_BootstrapControl());
        }
    }
    

}
