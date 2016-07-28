<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Helper_PaginationControl
 *
 * @author Viktor Ivanov
 */
class Core_Helper_Pagination extends Zend_View_Helper_PaginationControl {
    
    public function pagination(\Zend_Paginator $paginator = null, $hide=false) 
    {
        if (!$paginator or $hide){
            return ;
        }
        $this->view->addScriptPath(APPLICATION_PATH.'/views/scripts');
        return parent::paginationControl($paginator, 'Sliding', 'pagination.phtml');
    }
    
}
