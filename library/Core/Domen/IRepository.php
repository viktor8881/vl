<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Viktor Ivanov
 */
interface Core_Domen_IRepository {
    
    public function get($id);
    
    public function getByFilter(Core_Domen_Filter_Collection $filters, Core_Domen_Order_Collection $order=null);        
    
    public function fetchAll(Zend_Paginator $paginator, Core_Domen_Order_Collection $orders=null);
    
    public function fetchAllByFilter(Core_Domen_Filter_Collection $filters,  Zend_Paginator $paginator, Core_Domen_Order_Collection $orders=null);
    
    public function insert(Core_Domen_IModel $model);
    
    public function update(Core_Domen_IModel $model);
    
    public function delete(Core_Domen_IModel $model);        
    
    public function count();
    
    public function countByFilter(Core_Domen_Filter_Collection $filters);
    
    public function createModel(array $values=array());
}
