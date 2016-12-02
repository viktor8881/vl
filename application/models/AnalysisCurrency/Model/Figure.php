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
class AnalysisCurrency_Model_Figure extends AnalysisCurrency_Model_Abstract {
    
    const SEPARATE = ';';
    
    const FIGURE_DOUBLE_TOP              = 1;
    const FIGURE_DOUBLE_BOTTOM           = 2;
    const FIGURE_TRIPLE_TOP              = 3;
    const FIGURE_TRIPLE_BOTTOM           = 4;
    const FIGURE_HEADS_HOULDERS          = 5;
    const FIGURE_RESERVE_HEADS_HOULDERS  = 6;
    
    private $figure;
    private $cache_courses_list_id;
    
    private $_cache_courses;

      

    public function __construct(array $options = null) {
        $this->_aliases += ['investment_id'=>'investmentId',
                'courses_list_id'=>'cacheCoursesListIdToArray',
                'cache_courses_list_id'=>'cacheCoursesListIdFromDb'];
        parent::__construct($options);
    }
    
    
    public function getCacheCoursesListId() {
        return $this->cache_courses_list_id;
    }

    public function getCacheCoursesListIdToDb() {
        return implode(self::SEPARATE, $this->cache_courses_list_id);
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
    
    public function setCacheCoursesListIdToArray($cache_courses_list_id) {
        $this->cache_courses_list_id = $cache_courses_list_id;
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
    
    /**
     * count of days forming the figure
     * @return int
     */
    public function periodForming() {
        $result = 0;
        foreach ($this->getCacheCourses() as $cache) {
            $result += $cache->countDataValue();
        }
        return $result;
    }
    
    public function getCacheCourses() {
        if (is_null($this->_cache_courses)) {
            $this->_cache_courses = $this->getManager('CacheCourseCurrency')->fetchAllByList($this->getCacheCoursesListId());
        }
        return $this->_cache_courses;
    }

    public function getDateFirst() {
        return $this->getCacheCourses()->getFirstDate();        
    }
    
    public function getDateLast() {
        return $this->getCacheCourses()->getLastDate();
    }
    
    // == abstract methods =
    
    public function getType() {
        return AnalysisMetal_Model_Abstract::TYPE_FIGURE;
    }
    
    public function getBody() {
        $body = array('figure'=>$this->getFigure(),
            'cache_courses_list_id'=>$this->getCacheCoursesListIdToDb());
        return json_encode($body);
    }

    public function setBody($body) {
        $options = json_decode($body, true);
        if (is_null($options)) {
            throw new Exception('Body task can not be empty');
        }
        return $this->setOptions($options);
    }

    
}
