<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FigureMetal_model
 *
 * @author Viktor
 */
class FigureMetal_Model extends Core_Domen_Model_Abstract {
    
    const SEPARATE = ';';
    
    const FIGURE_DOUBLE_TOP              = 1;
    const FIGURE_DOUBLE_BOTTOM           = 2;
    const FIGURE_TRIPLE_TOP              = 3;
    const FIGURE_TRIPLE_BOTTOM           = 4;
    const FIGURE_HEADS_HOULDERS          = 5;
    const FIGURE_RESERVE_HEADS_HOULDERS  = 6;
    
    private $id;
    private $code;
    private $investment_id;
    private $figure;
    private $cache_courses_list_id;

    protected $_aliases = array('investment_id'=>'investmentId',
        'cache_courses_list_id'=>'cacheCoursesListIdFromDb');
    

    public function getOptions() {
        return array('id'       =>$this->getId(),
            'code'              =>$this->getCode(),
            'investment_id'     =>$this->getInvestmentId(),
            'figure'            =>$this->getFigure(),
            'cache_courses_list_id'=>$this->getCacheCoursesListIdToDb());
    }
    
    public function getId() {
        return $this->id;
    }
    
    public function getCode() {
        return $this->code;
    }

    public function getInvestmentId() {
        return $this->investment_id;
    }

    public function getCacheCoursesListId() {
        return $this->cache_courses_list_id;
    }

    public function getCacheCoursesListIdToDb() {
        return implode(self::SEPARATE, $this->cache_courses_list_id);
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }
    
    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

    public function setInvestmentId($investment_id) {
        $this->investment_id = $investment_id;
        return $this;
    }
    
    public function setCacheCoursesListId(array $cache_courses_list_id) {
        if (!is_null($cache_courses_list_id)) {
            $this->cache_courses_list_id = $cache_courses_list_id;
        }
        return $this;
    }
    
    public function setCacheCoursesListIdFromDb($cache_courses_list_id) {
        $this->cache_courses_list_id = explode(self::SEPARATE, $cache_courses_list_id);
        return $this;
    }

    public function getFigure() {
        return $this->figure;
    }

    public function setFigure($figure) {
        $this->figure = $figure;
        return $this;
    }

    public function getPercentCacheCourses() {
        $list = $this->getCacheCoursesListId();
        if (count($list)) {
            $cacheCourses = $this->getManager('CacheCourseMetal')->get(reset($list));
            if ($cacheCourses) {
                return $cacheCourses->getPercent();
            }
        }
        return null;
    }

}
