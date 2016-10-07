<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cron_GraphAnalysist2Controller
 *
 * @author Viktor
 */

class Cron_TechnicalAnalysisController extends Core_Controller_Action {

    public function indexAction() {
        
        
        
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout()->disableLayout(); 
    }
    
}
