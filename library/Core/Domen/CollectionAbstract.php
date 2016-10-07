<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Collections
 *
 * @author Viktor Ivanov
 */
abstract class Core_Domen_CollectionAbstract implements IteratorAggregate,  Countable, ArrayAccess {
    
    protected $_values = array();
 
    /**
     * 
     * @return \ArrayIterator
     */
    public function getIterator() {
        return new ArrayIterator($this->_values);
    }
    
    public function reverse() {
        $name = get_class($this);
        $result = new $name;
        foreach (array_reverse($this->_values, true) as $key=>$item) {
            $result->add($key, $item);
        }
        return $result;
    }
    
    /**
     * вернуть в виде массива
     * @return array
     */
    public function toArray() {
        return $this->_values;
    }
    
    public function addModel(Core_Domen_IModel $model) {
        return $this->add($model->getId(), $model);
    }
    
    public function removeModel(Core_Domen_IModel $model) {
        return $this->remove($model->getId());
    }

    /**
     * добавить значение
     * @param type $key
     * @param type $value
     * @return \CollectionAbstract
     */
    public function add($key, $value) {
        if (!is_null($key) or is_string($key)){
            $this->_values[$key] = $value;
        }else{
            $this->_values[] = $value;
        }
        return $this;
    }
    
    /**
     * существует ли элемент с данным ключем
     * @param type $key
     * @return boolean
     */
    public function isExistsKey ($key) {
        if (isset($this->_values[$key])) {
            return true;
        }
        return false;        
    }
    
    /**
     * получить элемент по ключу
     * @param type $key
     * @return boolean || Core_Domen_Model_Abstract
     */
    public function getValue ($key) {
        if ($this->isExistsKey ($key)){
            return $this->_values[$key];
        }
        return null;
    }
        
    /**
     * удалить элемент из коллекции
     * @param type $offset
     */
    public function remove ($key) {
        if ($this->isExistsKey ($key)){
            unset($this->_values[$key]);
        }
        return $this;
    }
    
    public function count() {
        return count($this->_values);
    }
    
    
    public function current() {
        reset($this->_values);
        return current($this->_values);
    }
    
    /**
     * очистить коллекцию
     * @return \Abstract_Collection
     */
    public function clear() {
        $this->_values=array();
        return $this;
    }
    
    /**
     * клонируем. и все что внутри тоже подвергаем клонированию
     */
    public function __clone()  {
        foreach ($this->_values as $key => $item) {
            if (is_object($item)) {
                $this->_values[$key] = clone $item;
            }
        }
    }
    
    /**
     * получить массив ключевых полей
     * @return array
     */
    public function getListId() {
        return array_keys($this->_values);
    }
    
    /**
     * получить последний элемент коллекции
     * @return null
     */
    public function last() {
        $i=0;
        $count = count($this->_values);
        foreach ($this->_values as $key => $item) {
            if (++$i==$count) {
                return $this->_values[$key];
            }
        }
        return null;
    }
    
    /**
     * получить первый элемент коллекции
     * @return null
     */
    public function first() {
        foreach ($this->_values as $key => $item) {            
            return $this->_values[$key];
        }
        return null;
    }
    
    // == for as array
    public function offsetSet($offset, $value) {
        return $this->add($offset, $value);
    }
        
    public function offsetExists($offset) {
        return $this->isExistsKey($offset);        
    }
    
    public function offsetUnset($offset) {
        return $this->remove($offset);        
    }
    
    public function offsetGet($offset) {
        return $this->getValue($offset);        
    }
    
    
}
