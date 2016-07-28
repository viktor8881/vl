<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ServiceAbstract
 *
 * @author Viktor Ivanov
 */
abstract class Core_Domen_Manager_Abstract implements Core_Domen_IManager {
    
    /**
     *  репозиторий
     * @var RepositoryInterface 
     */
    protected $_repository=null;


    public function __construct(Core_Domen_IRepository $repository) {
        $this->setRepository($repository);
    }    
    
    /**
     * установка репозитория
     * @param Core_Domen_IRepository $repository
     * @return \Core_Domen_Manager_Abstract
     */
    public function setRepository(Core_Domen_IRepository $repository)
    {
        $this->_repository = $repository;
        return $this;
    }
    
    /**
     * получить объект репозитория
     * @return Core_Domen_IRepository
     */
    public function getRepository()
    {
        return $this->_repository;
    }
    
    public function get($id) {
        return $this->getRepository()->get($id);
    }
    
    public function getByFilter(\Core_Domen_Filter_Collection $filters, \Core_Domen_Order_Collection $orders = null) {
        return $this->getRepository()->getByFilter($filters, $orders);
    }
    
    public function fetchAll(Zend_Paginator $paginator=null, Core_Domen_Order_Collection $orders=null) {
        return $this->getRepository()->fetchAll($paginator, $orders);
    }
        
    public function fetchAllByFilter(Core_Domen_Filter_Collection $filters,  Zend_Paginator $paginator=null, Core_Domen_Order_Collection $orders=null) {
        return $this->getRepository()->fetchAllByFilter($filters, $paginator, $orders);
    }
        
    public function count() {
        return (int)$this->getRepository()->count();
    }
    
    public function countByFilter(Core_Domen_Filter_Collection $filters) {
        return (int)$this->getRepository()->countByFilter($filters);
    }
    
    public function insert(Core_Domen_IModel $model) {
        return $this->getRepository()->insert($model);
    }
    
    public function update(Core_Domen_IModel $model) {
        return $this->getRepository()->update($model);
    }
    
    public function delete(Core_Domen_IModel $model) {
        return $this->getRepository()->delete($model);
    }    
    
    /**
     * 
     * @return Core_Domen_IModel
     */
    public function createModel(array $values=array()) {
        return $this->getRepository()->createModel($values);
    }
    
    /**
     * создать новую коллекцию
     * @return Core_Domen_CollectionAbstract
     */
    public function createCollection() {
        return $this->getRepository()->createCollection();
    }
    
    public function getManager($managerName) {
        return Core_Container::getManager($managerName);
    }
    
}

