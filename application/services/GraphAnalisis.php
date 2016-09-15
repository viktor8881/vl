<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GraphAnalisis
 *
 * @author Viktor
 */
class GraphAnalisis {
    
    const PERSENT_UP_TREND = 5;
    const PERSENT_DOWN_TREND = 5;
    
    /**
     * Возрастающий, или «бычий», тренд (uptrend, upward, bullish trend) 
     * характеризуется тем, что нижние колебания цены рынка повышаются.
     * Нижний критерий устанавливается на процент self::PERSENT_UP_TREND ниже предыдущего минимума.
     * @param CourseCurrency_Collection $courses
     * @param float $persent - self::PERSENT_UP_TREND
     * @return boolean
     */
    public static function IsUpTrend(CourseCurrency_Collection $courses, $percent=null) {
        if (!$percent) {
            $percent = self::PERSENT_UP_TREND;
        }
        $i=0;
        $prevCourse = null;
        foreach ($courses as $course) {
            if (++$i==1) {
                $prevCourse = $course;
                continue;
            }
            $lowCritery = $prevCourse->getValue()*(1-($percent/100));
            if (Core_Math::compareMoney($lowCritery, $course->getValue())==1 ) {
                return false;
            }
        }
        return true;
    }
    
    
    
    public static function IsDownTrend(CourseCurrency_Collection $courses, $percent=null) {
        if (!$percent) {
            $percent = self::PERSENT_DOWN_TREND;
        }
        $i=0;
        $prevCourse = null;
        foreach ($courses as $course) {
            if (++$i==1) {
                $prevCourse = $course;
                continue;
            }
            $hightCritery = $prevCourse->getValue()*(1+($percent/100));
            if (Core_Math::compareMoney($course->getValue(), $hightCritery)==1 ) {
                return false;
            }
        }
        return true;
    }
    
}
