<?php

/**
 * ViewHelper вывод сообщений на экран как Helper_FlashMessage
 * так и простых (установленных при формировании страницы через переменные view
 * $view->errorMessage = 'Текст ошибки'
 * $view->infoMessage = 'Какая либа инфа для пользователя. '
 * $view->successMessage = 'Все хорошо. сохранилось отредактировалось и т.п.'
 * )
 */

class Core_Helper_Message extends Zend_View_Helper_Abstract {

    /**
     * нэйм спэйсы обрабатываемых сообщений
     * и порядок их вывода на экран
     * @var array
     */
    private $_nameSpaceMessage = array('error'=>array(), 'warning'=>array(), 'success'=>array(), 'info'=>array());        
    
    public function message() {
        $html='';
        // смотрим ошибки в FlashMessenger
        $helper = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        foreach ($this->_nameSpaceMessage as $nameSpace=>&$arrMess){
            $helper->setNamespace($nameSpace);
            if ($helper->hasMessages()){                
                $mess = $helper->getMessages();
                if (is_array($mess)){
                    $arrMess = array_merge($arrMess, $mess);
                }else{
                    $arrMess[] = $mess;
                }
            }
        }        
        // смотрим ошибки в переменных view
        if (!empty($this->view->errorMessage)) {
            if (is_string($this->view->errorMessage)) {
                $this->view->errorMessage=array($this->view->errorMessage);
            }
            $this->_nameSpaceMessage['error'] += $this->view->errorMessage;
        }
        if(!empty($this->view->infoMessage)) {
            if (is_string($this->view->infoMessage)) {
                $this->view->infoMessage=array($this->view->infoMessage);
            }
            $this->_nameSpaceMessage['info'] += $this->view->infoMessage;
        }
        if(!empty($this->view->successMessage)) {
            if (is_string($this->view->successMessage)) {
                $this->view->successMessage = array($this->view->successMessage);
            }
            $this->_nameSpaceMessage['success'] += $this->view->successMessage;
        }
        
        if(!empty($this->view->warningMessage)) {
            if (is_string($this->view->warningMessage)) {
                $this->view->warningMessage = array($this->view->warningMessage);
            }
            $this->_nameSpaceMessage['warning'] += $this->view->warningMessage;
        }
        // вывод сообщений        
        foreach ($this->_nameSpaceMessage as $nameSpace=>$listMess){
            if (count($listMess)){
                $nameSpace = ($nameSpace=='error')?'danger':$nameSpace;
                foreach ($listMess as $mess){
                    $html .= '<div class="row-fluid">
                        <div class="alert alert-'.$nameSpace.' fade in">                
                            <button class="close" data-dismiss="alert">×</button>
                            '.$mess.'
                        </div></div>';
                }
            }
        }
        return $html;
    }            
    
    
}
