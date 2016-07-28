<?php

/**
 * ПАГИНАТОР
 */

class Core_Controller_Action_Helper_Paginator extends Zend_Controller_Action_Helper_Abstract {

    
    const COUNT_DEFAULT=100;
    
    /**
     * установка пагинатора
     * @param int $count         - всего элементов
     * @param int $pageCurrent   - активная страница
     * @param int $countPerPage  - сколько элементов выводить на странице
     * @return Zend_Paginator
     */
    public function direct($count, $pageCurrent=1, $countPerPage=null)
    {
        if (!$countPerPage or $countPerPage <= 0) {
            $countPerPage = self::COUNT_DEFAULT;
        }
        $paginator = Zend_Paginator::factory((int)$count);
        Zend_Paginator::setDefaultItemCountPerPage($countPerPage);
        $paginator->setCurrentPageNumber($pageCurrent);
        return $paginator;
    }        
    
}
