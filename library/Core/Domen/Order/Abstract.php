<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Domen_Order_Abstract
 *
 * @author Viktor Ivanov
 */
class Core_Domen_Order_Abstract {
    
    const ASC='ASC';
    const DESC='DESC';
    
    protected $_typeOrder=null;


    public function __construct($typeOrder=null) {
        $this->_typeOrder = self::ASC;
        $this->setTypeOrder($typeOrder);
    }
    
    /**
     * тип сортировки
     * @param string $typeOrder
     * @return \Core_Domen_Order_Abstract
     */
    public function setTypeOrder($typeOrder=null)
    {        
        if ($typeOrder==self::DESC) {
            $this->_typeOrder = self::DESC;
        }elseif ($typeOrder==self::ASC) {
            $this->_typeOrder = self::ASC;
        }
        return $this;
    }
    
    /**
     * получить тип сортировки
     * @return string
     */
    public function getTypeOrder()
    {
        return $this->_typeOrder;
    }
    
}

