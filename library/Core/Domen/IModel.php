<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Viktor
 */
interface Core_Domen_IModel {
    
    
    public function setOptions(array $options = array());
    
    public function getOptions();
    
    public function toArray();        
    
}
