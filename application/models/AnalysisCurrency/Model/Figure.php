<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AnalysisCurrency_Model_Figure
 *
 * @author Viktor
 */
class AnalysisCurrency_Model_Figure extends AnalysisMetal_Model_Abstract {
    
    const SEPARATE = ';';
    
    const FIGURE_DOUBLE_TOP              = 1;
    const FIGURE_DOUBLE_BOTTOM           = 2;
    const FIGURE_TRIPLE_TOP              = 3;
    const FIGURE_TRIPLE_BOTTOM           = 4;
    const FIGURE_HEADS_HOULDERS          = 5;
    const FIGURE_RESERVE_HEADS_HOULDERS  = 6;
    
    private $figure;
    private $cashe_courses_list_id;

    protected $_aliases = array('investment_id'=>'investmentId',
        'cashe_courses_list_id'=>'casheCoursesListIdFromDb');
    

    
    public function getCasheCoursesListId() {
        return $this->cashe_courses_list_id;
    }

    public function getCasheCoursesListIdToDb() {
        return implode(self::SEPARATE, $this->cashe_courses_list_id);
    }

    
    public function setCasheCoursesListId(array $cashe_courses_list_id) {
        if (!is_null($cashe_courses_list_id)) {
            $this->cashe_courses_list_id = $cashe_courses_list_id;
        }
        return $this;
    }
    
    public function setCasheCoursesListIdFromDb($cashe_courses_list_id) {
        $this->cashe_courses_list_id = explode(self::SEPARATE, $cashe_courses_list_id);
        return $this;
    }

    public function getFigure() {
        return $this->figure;
    }

    public function setFigure($figure) {
        $this->figure = $figure;
        return $this;
    }

    public function getPercentCacheCources() {
        $list = $this->getCasheCoursesListId();
        if (count($list)) {
            $cacheCources = $this->getManager('CacheCourseMetal')->get(reset($list));
            if ($cacheCources) {
                return $cacheCources->getPercent();
            }
        }
        return null;
    }
    
    // == abstract methods =
    
    public function getType() {
        return AnalysisMetal_Model_Abstract::TYPE_FIGURE;
    }
    
    public function getBody() {
        $body = array('figure'=>$this->getFigure(),
            'cashe_courses_list_id'=>$this->getCasheCoursesListIdToDb());
        return json_encode($body);
    }

    public function setBody($body) {
        $options = json_decode($body, true);
        if (is_null($options)) {
            throw new RuntimeException('Body task can not be empty');
        }
        return $this->setOptions($options);
    }

    
}
