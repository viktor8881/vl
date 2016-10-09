<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FigureMetal_Manager
 *
 * @author Viktor
 */
class FigureMetal_Manager extends Core_Domen_Manager_Abstract {
    
    
    public function fetchAllByInvestmentId($listId) {
        $filters = new Core_Domen_Filter_Collection();
        $filters->addFilter(new FigureMetal_Filter_InvestId($listId));
        return $this->fetchAllByFilter($filters);
    }

    public function insert(\Core_Domen_IModel $model) {
        if ($model instanceof FigureMetal_Model) {
            // пишем что произвели операцию
            $this->getManager('CacheCourseMetal')->setOperation($model->getCasheCoursesListId());
            return parent::insert($model);
        }
        return false;
    }
    
}
