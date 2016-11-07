<?php

class IndexController extends Core_Controller_Action
{

    public function indexAction() {
        exit;
//        pr(PUBLIC_PATH.';;');
        $paths = $this->getInvokeArg('bootstrap')->getOption('path');
        
        $analisys = $this->getManager('AnalysisMetal')->getLastByType(AnalysisMetal_Model_Abstract::TYPE_FIGURE);
        $coll = $analisys->getCasheCourses();
        
        $pchart = new Core_PChart(PUBLIC_PATH.$paths['public_img_pchart'], PUBLIC_PATH.$paths['public_fonts']);
        $fileName = $pchart->renderMetal($coll);
        echo $this->view->analysisMetal_Figure_Name($analisys->getFigure());
        echo $this->view->analysisMetal_Figure_Period($analisys);
        echo '<img src="/'.$paths['public_img_pchart'].$fileName.'" />';
        pr($fileName);
        exit;
    }
    
}