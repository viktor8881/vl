<?php

require_once 'PChart/class/pData.class.php';
require_once "PChart/class/pDraw.class.php";
require_once "PChart/class/pImage.class.php";

class Core_PChart {
    
    const VOID = 0.123456789;
    
    private $path_img;
    private $path_fonts;
    
    public function __construct($path_img, $path_fonts) {
        $this->path_img = $path_img;
        $this->path_fonts = $path_fonts;
    }
    
    public function renderMetal(CacheCourseMetal_Collection $coll) {
        $dataBase = [];
        $dataFigure = [];
        $dates = [];
        foreach ($coll as $item) {
            $i=0;
            $count = $item->countDataValue();
            foreach ($item->getDataValue() as $value) {
                $data = new DateTime($value['data']);
                $dataBase[] = $value['value'];
                $dates[]    = $data->format('d.m');
                $dataFigure[] = (++$i == $count)?$item->getLastValue():self::VOID;
            }
        }
        $myPicture = $this->factory($dataBase, $dataFigure, $dates);
        $filename = uniqid(rand(), true).'.png';
        $myPicture->Render($this->path_img.$filename);
        return $filename;
    }
    
    public function renderCurrency(CacheCourseCurrency_Collection $coll) {
        
    }
    
    private function factory(array $dataBase, array $dataFigure, array $dataX) {
        $myData = new pData();
        $myData->addPoints($dataBase,"Serie1");
        $myData->setSerieDescription("Serie1", "Serie 1");
        $myData->setSerieOnAxis("Serie1",0);
        //
        //$myData->addPoints(array(-40,-12,43,19,10,-14,-6,-12),"Serie2");
        //$myData->setSerieDescription("Serie2","Serie 2");
        //$myData->setSerieOnAxis("Serie2",0);

        $myData->addPoints($dataFigure, "Serie2");
        $myData->setSerieDescription("Serie2","Serie 2");
        $myData->setSerieOnAxis("Serie2",0);

        $myData->addPoints($dataX, "Absissa");
        $myData->setAbscissa("Absissa");

        $myData->setAxisPosition(0, AXIS_POSITION_LEFT);
        $myData->setAxisName(0,"1st axis");
        $myData->setAxisUnit(0,"");

        $myPicture = new pImage(700,230,$myData);
        $Settings = array("StartR"=>195, "StartG"=>195, "StartB"=>195, "EndR"=>204, "EndG"=>204, "EndB"=>204, "Alpha"=>50);
        $myPicture->drawGradientArea(0,0,700,230,DIRECTION_VERTICAL,$Settings);

//        $myPicture->setFontProperties(array("FontName"=>"fonts/Forgotte.ttf","FontSize"=>14));
        $myPicture->setFontProperties(array("FontName"=>$this->path_fonts."/Forgotte.ttf","FontSize"=>14));
        $TextSettings = array("Align"=>TEXT_ALIGN_MIDDLEMIDDLE, "R"=>0, "G"=>0, "B"=>0);
        $myPicture->drawText(350,25,"My first pChart project",$TextSettings);

        $myPicture->setGraphArea(50,50,675,190);
//        $myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"fonts/pf_arma_five.ttf","FontSize"=>6));
        $myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>$this->path_fonts."/pf_arma_five.ttf","FontSize"=>6));

        $Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
        , "Mode"=>SCALE_MODE_FLOATING
        , "LabelingMethod"=>LABELING_ALL
        , "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50, "TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50, "LabelRotation"=>0, "CycleBackground"=>1, "DrawXLines"=>1, "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50, "DrawYLines"=>ALL);
        $myPicture->drawScale($Settings);

        $Config = array( "BreakVoid"=>0, "BreakR"=>5, "BreakG"=>1, "BreakB"=>1);
        $myPicture->drawLineChart($Config);

        return $myPicture;
    }
    
}