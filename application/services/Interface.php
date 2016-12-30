<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Service_Analysis
 *
 * @author Viktor
 */
interface Service_Interface {
    
    public function runByTask(Task_Model_Abstract $task, Core_Date $date);
    
}
