<?php

class IndexController extends Core_Controller_Action
{

    
    private static $DATA_DEF;
    
    public function init() {
        parent::init();
        $dateNow = new Core_Date();
        self::$DATA_DEF = $dateNow->sub(new DateInterval('P1Y'))->formatDMY();
    }
    
    public function indexAction() {
        
        exit;
    }
    
}