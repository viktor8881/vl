<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of FigureCurrency_model
 *
 * @author Viktor
 */
class FigureCurrency_Model extends Core_Domen_Model_Abstract {
    
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
    private $cashe_courses_list_id;
    
    protected $_aliases = array('investment_id'=>'investmentId',
        'cashe_courses_list_id'=>'casheCoursesListId');


    public function getOptions() {
        return array('id'       =>$this->getId(),
            'code'              =>$this->getCode(),
            'investment_id'     =>$this->getInvestmentId(),
            'figure'            =>$this->getFigure(),
            'cashe_courses_list_id'=>$this->getCasheCoursesListIdToDb());
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
    
    public function getCasheCoursesListId() {
        return $this->cashe_courses_list_id;
    }
    
    public function getCasheCoursesListIdToDb() {
        return implode(self::SEPARATE, $this->cashe_courses_list_id);
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
    
    public function setCasheCoursesListId(array $cashe_courses_list_id) {
        $this->cashe_courses_list_id = $cashe_courses_list_id;
        return $this;
    }

    public function getFigure() {
        return $this->figure;
    }

    public function setFigure($figure) {
        $this->figure = $figure;
        return $this;
    }

}
