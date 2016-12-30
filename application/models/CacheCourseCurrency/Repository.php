<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CacheCourseCurrency_Repository
 *
 * @author Viktor
 */
class CacheCourseCurrency_Repository extends Core_Domen_Repository_Abstract {
    
    
    public function setOperation(array $listId) {
        return $this->getMapper()->setOperation($listId);
    }
    
}
