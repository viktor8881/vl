<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Viktor Ivanov
 */
interface Core_Domen_IFilter  {
    
    public function __construct($values);
    
    public function getValue();
    
    public function add($values);

}

