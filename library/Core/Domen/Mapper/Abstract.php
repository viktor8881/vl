<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MapperAbstract
 *
 * @author Viktor Ivanov
 */
abstract class Core_Domen_Mapper_Abstract implements Core_Domen_IMapper {
    

    protected $_table=null;
    protected $_primary=null;
    
        
    protected function getConnect() {
        return Zend_Db_Table_Abstract::getDefaultAdapter();
    }
    
    /**
     * получить Select
     * @return Zend_Db_Select
     */
    protected function getSelect() {
        return $this->getConnect()->select();
    }
    
    
    /**
     * выполнение запроса
     * @param Zend_Db_Select $select
     * @return type
     */
    protected function _query(Zend_Db_Select $select=null)
    {
        if (!$select){
            $select = $this->getSelect();            
        }
        if (!count($select->getPart('from'))) {
            $select->from($this->getTableName()); 
        }
        return $this->getConnect()->query($select);
    }        
    
    
    /**
     * получить имя таблицы
     * @param string $nameMap - алиас в карте сервис локатора см. файл config/listMap.php
     * @return string
     */
    protected function getTableName()
    {
        if (!$this->_table){
            throw new RuntimeException('Table not set - '.  get_class($this));
        }
        return $this->_table;
    }
    
    /**
     * получить ключевое поле
     * @return type
     */
    protected function getPrimaryKey()
    {
        if (!$this->_primary){
            throw new RuntimeException('Primary key not set - '.$this->getTableName());
        }
        return $this->_primary;
    }
    
    /**
     * последний вставленный ид
     * @return int
     */
    public function lastInsertId()
    {
        return $this->getConnect()->lastInsertId($this->getTableName());
    }
    
    
    /**
     * =================== реализация базовых методов ========================
     */
    
    public function get($id)
    {        
        $select = $this->getSelect();
        $select->from($this->getTableName())
                ->where($this->getPrimaryKey()."=?", $id)
                ->limit(1);
        return $this->_query($select)->fetch();
    }
    
    public function getByFilter(Core_Domen_Filter_Collection $filters,  Core_Domen_Order_Collection $orders=null) 
    {
        $select = $this->getSelect();
        $select->limitPage(1, 1);
        $this->_addWhereByFilters($filters, $select);
        $rows = $this->_fetchAll($select, null, $orders);
        if ($rows) {
            return current($rows);
        }
        return null;
    }
    
    public function fetchAll(Zend_Paginator $paginator, Core_Domen_Order_Collection $orders=null) {
        return $this->_fetchAll(null, $paginator, $orders);
    }
    
    /**
     * получить все по условию и пагинатору
     * @param Zend_Db_Select $select
     * @param Zend_Paginator $paginator
     * @return array(array(), array(), ...)
     */
    protected function _fetchAll(Zend_Db_Select $select=null, Zend_Paginator $paginator=null, Core_Domen_Order_Collection $orders=null) 
    {
        if (!$select){
            $select = $this->getSelect();
        }
        if ($paginator){
            $select->limitPage((int)$paginator->getCurrentPageNumber(), (int)$paginator->getItemCountPerPage());
        }
        if ($orders) {
            $this->_addOrders($orders, $select);
        }
        return $this->_query($select)->fetchAll();
    }
        
    /**
     * кол-во по условию
     * @param Zend_Db_Select $select
     * @return int
     */
    public function count(Zend_Db_Select $select=null) {
        if (!$select){
            $select = $this->getSelect();
        }
        $select->from($this->getTableName(), array("count"=>"(count(".$this->getPrimaryKey()."))"));        
        $row = $this->_query($select)->fetch();
        return (int)$row['count'];
    }
    
    /**
     * получить все по фильтру и пагинатору
     * @param Core_Domen_Filter_Collection $filters
     * @param Zend_Paginator $paginator
     * @return array(array(), array(), ...)
     */
    public function fetchAllByFilter(Core_Domen_Filter_Collection $filters,  Zend_Paginator $paginator=null, Core_Domen_Order_Collection $orders=null) 
    {
        $select = $this->getSelect();
        $this->_addWhereByFilters($filters, $select);
        return $this->_fetchAll($select, $paginator, $orders);
    }
    
    
    /**
     * кол-во по фильтру
     * @param Core_Domen_Filter_Collection $filters
     * @return int
     */        
    public function countByFilter(Core_Domen_Filter_Collection $filters) {        
        $select = $this->getSelect();        
        $this->_addWhereByFilters($filters, $select);
        return (int)$this->count($select);
    }        
    
    /**
     * добавить новый
     * @param array $values
     * @return int
     */
    public function insert(Core_Domen_IModel $model) {
        $values = $model->getOptions();
        if (array_key_exists($this->getPrimaryKey(), $values)){
            unset($values[$this->getPrimaryKey()]);
        }
        $res = $this->getConnect()->insert($this->getTableName(), $values);
        if ($res) {
            // set ID                        
            $model->setId($this->lastInsertId());            
        }
        return $res;
    }
    
    /**
     * обновить
     * @param array $values
     * @return int
     */
    public function update(Core_Domen_IModel $model) 
    {
        $values = $model->getOptions();
        if (isset($values[$this->getPrimaryKey()])){
            $where = $this->getConnect()->quoteInto($this->getPrimaryKey()."=?", $values[$this->getPrimaryKey()]);
            $result = $this->getConnect()->update($this->getTableName(), $values, $where);
            return $result;
        }
        return 0;
    }
    
    /**
     * удалить
     * @param int $id
     * @return int
     */
    public function delete(Core_Domen_IModel $model) {
        $values = $model->getOptions();
        $where = $this->getConnect()->quoteInto($this->getPrimaryKey()."=?", (int)$values[$this->getPrimaryKey()]);
        $return = $this->getConnect()->delete($this->getTableName(), $where);
        return $return;
    }
    
    public function beginTransaction() {
        Zend_Db_Table_Abstract::getDefaultAdapter()->beginTransaction();
    }

    public function commit() {
        Zend_Db_Table_Abstract::getDefaultAdapter()->commit();
    }

    public function rollBack() {
        Zend_Db_Table_Abstract::getDefaultAdapter()->rollBack();
    }    
    
   
    /**
     * наложение фильтров на запрос
     * @param Core_Domen_Filter_Collection $filters
     * @param Zend_Db_Select $select
     */
    protected function _addWhereByFilters(Core_Domen_Filter_Collection $filters, Zend_Db_Select $select) {
        foreach ($filters as $filter) {
            if (!count($filter->getValue())) { 
                continue;                 
            }
            $this->addWhereByFilter($filter, $select);
        }
    }
    
    protected function _addOrders(Core_Domen_Order_Collection $orders, Zend_Db_Select $select) {
        foreach ($orders as $order) {            
            $this->addOrder($order, $select);
        }
    }
    
    
    abstract public function addOrder(Core_Domen_Order_Abstract $order, Zend_Db_Select $select);
    
    abstract public function addWhereByFilter(Core_Domen_Filter_Abstract $filter, Zend_Db_Select $select);

    
    
}

