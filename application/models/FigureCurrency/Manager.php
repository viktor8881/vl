<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FigureCurrency_Manager
 *
 * @author Viktor
 */
class FigureCurrency_Manager extends Core_Domen_Manager_Abstract {
    
    
    public function insert(\Core_Domen_IModel $model) {
        if ($model instanceof FigureCurrency_Model) {
            // пишем что произвели операцию
            $this->getManager('CacheCourseCurrency')->setOperation($model->getCasheCoursesListId());
            return parent::insert($model);
        }
        return false;
    }
    
    
}
