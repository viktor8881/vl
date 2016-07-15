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
abstract class Core_Domen_Repository_Abstract implements Core_Domen_IRepository {
    
    /**     
     * @var Core_Domen_IMapper 
     */
    protected $_mapper = null;
    
    /**
     * @var Core_Domen_IFactory
     */
    protected $_factory = null;
    
    /**
     * @var Core_Domen_CollectionAbstract
     */
    protected $_collection = null;
    

    
    public function __construct(Core_Domen_IMapper $mapper, Core_Domen_IFactory $factory, Core_Domen_CollectionAbstract $collection) {
        $this->setMapper($mapper)
                ->setFactory($factory)
                ->setCollection($collection);
    }
    
    public function getFactory() {
        return $this->_factory;
    }

    public function getCollection() {
        return $this->_collection;
    }

    public function setFactory(Core_Domen_IFactory $factory) {
        $this->_factory = $factory;
        return $this;
    }

    public function setCollection(Core_Domen_CollectionAbstract $collection) {
        $this->_collection = $collection;
        return $this;
    }
    
    public function setMapper(Core_Domen_IMapper $mapper) {
        $this->_mapper = $mapper;
        return $this;
    }
       
    protected function getMapper() {
        return $this->_mapper;        
    }
            
    public function get($id) {        
        $model=null;
        if (!$this->getCollection()->isExistsKey($id)){
            $mapperValues = $this->getMapper()->find($id);
            if (!$mapperValues){                
                return null;
            }
            $model = $this->createModel($mapperValues);
            $this->getCollection()->addModel($model);
        }else{            
            $model=$this->getCollection()->getValue($id);
        }        
        return clone $model;
    }
    
    public function getByFilter(Core_Domen_Filter_Collection $filters,  Core_Domen_Order_Collection $orders=null) {
        $row = $this->getMapper()->getByFilter($filters, $orders);
        if (!is_null($row)) {
            return $this->createModel( $row );
        }
        return null;
    }
    
    public function fetchAll(Zend_Paginator $paginator=null, Core_Domen_Order_Collection $orders=null) {        
        return $this->createCollection( $this->getMapper()->fetchAll(null, $paginator, $orders) );
    }
        
    public function count() {
        return (int)$this->getMapper()->count();
    }
        
    public function fetchAllByFilter(Core_Domen_Filter_Collection $filters,  Zend_Paginator $paginator=null, Core_Domen_Order_Collection $orders=null) {
        return $this->createCollection( $this->getMapper()->fetchAllByFilter($filters, $paginator, $orders) );
    }
        
    public function countByFilter(Core_Domen_Filter_Collection $filters) {
        return (int)$this->getMapper()->countByFilter($filters);
    }
        
    public function createModel(array $values=array()) {
        return $this->getFactory()->create($values);
    }
        
    public function createCollection(array $rows=array()) {
        $name = get_class($this->getCollection());
        $collection = new $name;
        if(count($rows)){            
            foreach ($rows as $row) {
                $collection->addModel($this->createModel($row));
            }
        }
        return $collection;
    }
            
    public function insert(Core_Domen_IModel $model) {
        $res = $this->getMapper()->insert($model);        
        if ($res){
            $this->getCollection()->addModel($model);
        }
        return $res;
    }
            
    public function update(Core_Domen_IModel $model) {
        $res = $this->getMapper()->update($model);
        if ($res) {
            $this->getCollection()->addModel($model);       
        }
        return $res;
    }
        
    public function delete(Core_Domen_IModel $model) {
        $res = $this->getMapper()->delete($model);
        if ($res){
            $this->getCollection()->removeModel($model);
        }
        return $res;
    }
    
}

