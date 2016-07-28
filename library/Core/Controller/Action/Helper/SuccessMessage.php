<?php

/**
 * ViewHelper вывод сообщений на экран как Helper_FlashMessage
 * так и простых (установленных при формировании страницы через переменные view
 * $view->errorMessage = 'Текст ошибки'
 * $view->infoMessage = 'Какая либа инфа для пользователя. '
 * $view->successMessage = 'Все хорошо. сохранилось отредактировалось и т.п.'
 * )
 */

class Core_Controller_Action_Helper_SuccessMessage extends Zend_Controller_Action_Helper_Abstract {
     
    
    public function direct($mess=null)
    {
        if ($mess){
            $this->addMessage($mess);
        }
        return $this;
    }
    
    public function addMessage($mess)
    {
        if (is_string($mess)){
            $helperFlash = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');//$this->_actionController->getHelper('FlashMessenger');
            $helperFlash->setNamespace('success');        
            $helperFlash->addMessage($mess);
        }        
    }
    
}
