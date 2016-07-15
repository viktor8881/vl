<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Form_Decorator_NameDecor
 *
 * @author Victor
 */
class Core_Form_Decorator_ErrorsLabel extends Zend_Form_Decorator_Errors {
    
    protected $_options=array(
            'elementStart'=>'<div class="text-danger small">', 
            'elementEnd'=>'</div>', 
            'elementSeparator'=>"<br />"
            );           
    
    
    /**
     * Render errors
     *
     * @param  string $content
     * @return string
     */
    public function render($content)
    {
        $element = $this->getElement();
        $view    = $element->getView();
        if (null === $view) {            
            return $content;
        }

        $errors = $element->getMessages();
        if (empty($errors)) {            
            return $content;
        }
        $separator = $this->getSeparator();        
        $placement = $this->getPlacement();
        
        foreach ($errors as $key => $error) {
            $errors[$key] = $view->escape($error);
        }
        // выводим одну ошибку
        $errors = current($errors);
        
        $html  = $this->getOption('elementStart')
               . implode($this->getOption('elementSeparator'), (array) $errors)
               . $this->getOption('elementEnd');
 
        switch ($placement) {
            case self::APPEND:
                return $content . $separator . $html;
            case self::PREPEND:
                return $html . $separator . $content;
        }
    }

    
}

