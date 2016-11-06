<?php

class IndexController extends Core_Controller_Action
{

    public function indexAction() {
        exit;
//        pr(PUBLIC_PATH.';;');
        $analisys = $this->getManager('AnalysisMetal')->getLastByType(AnalysisMetal_Model_Abstract::TYPE_FIGURE);
        $coll = $analisys->getCasheCourses();
        
        $paths = $this->getInvokeArg('bootstrap')->getOption('path');
//        
        $pchart = new Core_PChart(PUBLIC_PATH.$paths['public_img_phart'], PUBLIC_PATH.$paths['public_fonts']);
        $fileName = $pchart->renderMetal($coll);
        pr($fileName);
        exit;
    }
    
}