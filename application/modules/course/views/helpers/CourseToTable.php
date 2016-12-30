<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 
 *
 * @author Viktor Ivanov
 */
class Course_View_Helper_CourseToTable extends Zend_View_Helper_Abstract
{
    
    public function courseToTable(array $courses) {
        $xhtml = '<table class="table table-bordered">';
        $listDate = array();
        $listValue = array();
        foreach ($courses as $course) {
            $listDate[] = $course->getDateFormatDM();
            $listValue[] = Core_Math::roundMoney($course->getValue());
        }
        $xhtml .= '<tr><td>'.implode('</td><td>', $listDate).'</td></tr>';
        $xhtml .= '<tr><td>'.implode('</td><td>', $listValue).'</td></tr>';
        $xhtml .= '</table>';
        return $xhtml;
    }
        
}
