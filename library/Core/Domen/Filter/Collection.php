<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FilterAbstract
 *
 * @author Viktor Ivanov
 */
class Core_Domen_Filter_Collection extends Core_Domen_CollectionAbstract {
    
    
    public function __construct($filters=null) 
    {
        if ($filters) {
            if (!is_array($filters)) {
                $filters = array($filters);
            }
            foreach ($filters as $filter) {
                $this->addFilter($filter);
            }
        }
    }

    /**
     * получить коллекцию фильтров
     * @return ArrayIterator
     */
    public function getFilters() {
        return parent::getIterator();
    }
    
    
    /**
     * установить фильтр
     * @param Core_Domen_IFilter $filter
     * @return \Core_Domen_Filter_Collection
     */
    public function addFilter(Core_Domen_IFilter $filter=null) 
    {
        if ($filter) {
            $key = get_class($filter);
            $fItem = parent::getValue($key);
            if ($fItem) {
                $fItem->add($filter->getValue());
            }else{
                parent::add($key, $filter);
            }
        }
        return $this;
    }
    
    
    /**
     * получить фильтр по ключу
     * @param string $key
     * @return Core_Domen_Filter_Abstract || null
     */
    public function getFilter($key) {        
        return parent::getValue($key);        
    }
    
    /**
     * есть ли значения в фильтрах
     * @return type
     */
    public function isSetValue()
    {
        $count = 0;
        foreach ($this->getIterator() as $filter) {
            $count += count($filter->getValue());
        }
        return $count;
    }
    
    
}
