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
    const PERSENT_EQUAL_TREND = 5;
    // количество подряд для понимания уверенного тренда.
    const SURE_TREND = 5;
    
    /**
     * Продолжающийся тренд.
     * Возрастающий, или «бычий», тренд (uptrend, upward, bullish trend) 
     * характеризуется тем, что нижние колебания цены рынка повышаются.
     * Нижний критерий устанавливается на процент self::PERSENT_UP_TREND ниже предыдущего минимума.
     * @param array $courses
     * @param float $persent - self::PERSENT_UP_TREND
     * @return boolean
     */
    public static function isUpTrend(array $courses, $percent=null) {
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
            $lowCritery = $prevCourse*(1-($percent/100));
            if (Core_Math::compareMoney($lowCritery, $course)==1 ) {
                return false;
            }
            $prevCourse = $course;  
        }
        return true;
    }
    
    
    /**
     * Продолжающийся тренд.
     * Убывающий, или «медвежий», тренд (downtrend, downward, bearish trend) 
     * характеризуется тем, что максимальные цены колебаний рынка понижаются
     * Нижний критерий устанавливается на процент self::PERSENT_DOWN_TREND выше предыдущего минимума.
     * @param array $courses
     * @param type $percent
     * @return boolean
     */
    public static function isDownTrend(array $courses, $percent=null) {
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
            $hightCritery = $prevCourse*(1+($percent/100));
            if (Core_Math::compareMoney($course, $hightCritery)==1 ) {
                return false;
            }
            $prevCourse = $course;
        }
        return true;
    }
    
    /**
     * Горизонтальный тренд, он показывает, что цены колеблются в
     * горизонтальном диапазоне (sideways, fl at market, trendless). 
     * Нижний/верхний критерий устанавливается на процент self::PERSENT_EQUAL_TREND выше/ниже предыдущего минимума.
     * @param array $courses
     * @param float $percent
     * @return boolean
     */
    public static function isEqualTrend(array $courses, $percent=null) {
        if (!$percent) {
            $percent = self::PERSENT_EQUAL_TREND;
        }
        $i=0;
        $prevCourse = null;
        foreach ($courses as $course) {
            if (++$i==1) {
                $lowCritery = $course*(1-($percent/100));
                $hightCritery = $course*(1+($percent/100));
                continue;
            }
            if (Core_Math::compareMoney($course, $hightCritery)==1 or Core_Math::compareMoney($lowCritery, $course)==1) {
                return false;
            }
        }
        return true;
    }
    
    /**
     *  Двойная вершина (дно) double Top. Разворотная фигура
     * для повышательного тренда имеет вид буквы М. Двойная вершина является
     * сигналом более слабым, чем тройная вершина.
     * @param array $courses
     * @param float $percent
     * @return boolean
     */
    public static function isDoubleTop(array $courses, $percent=null, $sureTrend=null) {
        if (!$percent) {
            $percent = self::PERSENT_EQUAL_TREND;
        }
        if (!$sureTrend) {
            $sureTrend = self::SURE_TREND;
        }
        $mode = 1;
        $i=0;
        $tmpArr = [];
        $prevCourse = reset($courses);
        $lineNeck = reset($courses)*(1-($percent/100));;
        foreach ($courses as $key=>$course) {
            if (++$i==1) {
                $tmpArr[] = $course;
                continue;
            }
            $tmpArr[] = $course;
            if ($mode == 1) {
                if (self::isUpTrend($tmpArr, $percent) or self::isEqualTrend($tmpArr, $percent) ) {
                    $prevCourse = $course;
                    continue;
                }else{
                    if ($i <= $sureTrend){
                        // не найден уверенный повышенный тренд
                        return false;
                    }
                    // цена пробивает линию шеи
                    if (Core_Math::compareMoney($lineNeck, $course)==1) {
                        return false;
                    }
                    $mode = 2;
                    $firsTop = $prevCourse*(1+($percent/100));
                    $tmpArr = [$prevCourse, $course];
                    $prevCourse = $course;
                    continue;
                }
            }elseif($mode == 2) {
                // цена пробивает линию шеи
                if (Core_Math::compareMoney($lineNeck, $course)==1) {
                    return false;
                }
                // ищем понижение
                if (self::isDownTrend($tmpArr, $percent) or self::isEqualTrend($tmpArr, $percent) ) {
                    $prevCourse = $course;
                    continue;
                }else{
                    // цена пробивает верхнюю линию поддержки $firsTop
                    if (Core_Math::compareMoney($course, $firsTop)==1) {
                        return false;
                    }
                    $mode = 3;
                    $firsBottom = min($tmpArr);
                    $tmpArr = [$prevCourse, $course];
                    $prevCourse = $course;
                    continue;
                }
            }elseif ($mode == 3) {
                // цена пробивает верхнюю линию поддержки $firsTop
                if (Core_Math::compareMoney($course, $firsTop)==1) {
                    return false;
                }
                // цена пробивает линию шеи
                if (Core_Math::compareMoney($lineNeck, $course)==1) {
                    return false;
                }
                if (self::isUpTrend($tmpArr, $percent) or self::isEqualTrend($tmpArr, $percent) ) {
                    $prevCourse = $course;
                    continue;
                }else{
                    $mode = 4;
                    $secondTop = $prevCourse*(1+($percent/100));
                    $tmpArr = [$prevCourse, $course];
                    $prevCourse = $course;
                    continue;
                }
            }elseif ($mode == 4) {
                // цена пробивает верхнюю линию поддержки $firsTop
                if (Core_Math::compareMoney($course, $secondTop)==1) {
                    return false;
                }
                if (self::isDownTrend($tmpArr, $percent) or self::isEqualTrend($tmpArr, $percent) ) {
                    // цена пробивает нижнюю линию поддержки
                    if (Core_Math::compareMoney($firsBottom, $course)==1) {
                        return true;
                    }else{
                        continue;
                    }
                }else{
                    return false;
                }
            }
        }
        return false;
    }
    
}
