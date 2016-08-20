<?php



class Core_Helper_PageHeader extends Zend_View_Helper_Abstract
{
    
    private $_currentTitle;
    
    public function pageHeader($title=null)
    {
        if (!is_null($title)) {
            $this->_currentTitle = $title;
            $this->view->headTitle($title);
        }
        return $this;
    }
    
    public function __toString() {
        return (!is_null($this->_currentTitle))?'<h1 class="page-header" >'._($this->_currentTitle).'</h1>':'';
    }
    
    
}
