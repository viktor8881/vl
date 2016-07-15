<?php
/**
 * Service Setting_Mapper
 *
 * Service Setting_Mapper
 */
class Setting_Mapper extends Core_Domen_Mapper_Abstract
{

    protected $_table = 'settings';

    protected $_primary = 'id';

   

    public function addOrder(\Core_Domen_Order_Abstract $order, \Zend_Db_Select $select) {
        
    }

    public function addWhereByFilter(\Core_Domen_Filter_Abstract $filter, \Zend_Db_Select $select) {
        
    }


}
