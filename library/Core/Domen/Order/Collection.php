<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Domen_Order_Collection
 *
 * @author Viktor Ivanov
 */
class Core_Domen_Order_Collection extends Core_Domen_CollectionAbstract {
    
    public function __construct($orders=null) 
    {
        if ($orders) {
            if (!is_array($orders)) {
                $orders = array($orders);
            }
            foreach ($orders as $order) {
                $this->add($order);
            }
        }
    }
    
   /**
    * 
    * @param Core_Domen_Order_Abstract $order
    * @return \Core_Domen_Order_Collection
    */
    public function add(Core_Domen_Order_Abstract $order) {
        $key = get_class($order);        
        parent::add($key, $order);
        return $this;
    }
    
}

