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
class Service_GraphAnalisis {
    
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
    public static function isUpTrend(array $courses, $percent=5) {
        $i=0;
        $prevCourse = null;
        foreach ($courses as $course) {
            if (++$i==1) {
                $prevCourse = $course;
                continue;
            }
            $lowCritery = $prevCourse*(1+($percent/100));
            if (Core_Math::compareMoney($lowCritery, $course)==1 ) {
                return false;
            }
            $prevCourse = $lowCritery;  
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
    public static function isDownTrend(array $courses, $percent=5) {
        $i=0;
        $prevCourse = null;
        foreach ($courses as $course) {
            if (++$i==1) {
                $prevCourse = $course;
                continue;
            }
            $hightCritery = $prevCourse*(1-($percent/100));
            if (Core_Math::compareMoney($course, $hightCritery)==1 ) {
                return false;
            }
            $prevCourse = $hightCritery;
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
    public static function isEqualChannel(array $courses, $percent=5) {
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
    
    public static function isUpChannel(array $courses, $percent=5) {
        $i=0;
        $prevCourse = null;
        foreach ($courses as $course) {
            if (++$i==1) {
                $hightCritery = $course*(1+($percent/100));
                $lowCritery = $course*(1-($percent/100));
                continue;
            }
            $hightCritery = $hightCritery*(1+($percent/100));
            $lowCritery = $lowCritery*(1+($percent/100));
            if (Core_Math::compareMoney($course, $hightCritery)==1 or Core_Math::compareMoney($lowCritery, $course)==1) {
                return false;
            }
        }
        return true;
    }
    
    public static function isDownChannel(array $courses, $percent=5) {
        $i=0;
        $prevCourse = null;
        foreach ($courses as $course) {
            if (++$i==1) {
                $hightCritery = $course*(1+($percent/100));
                $lowCritery = $course*(1-($percent/100));
                continue;
            }
            $hightCritery = $hightCritery*(1-($percent/100));
            $lowCritery = $lowCritery*(1-($percent/100));
            if (Core_Math::compareMoney($course, $hightCritery)==1 or Core_Math::compareMoney($lowCritery, $course)==1) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Двойная вершина double Top. Разворотная фигура
     * для повышательного тренда имеет вид буквы М. Двойная вершина является
     * сигналом более слабым, чем тройная вершина.
     * @param array $courses
     * @param float $percent
     * @param float $percentDiffPeak разница в процентах между верхом и низом волны
     * @return boolean
     */
    public static function isDoubleTop(array $courses, $percent=5, $percentDiffPeak=20) {
        if (Core_Math::compareMoney($courses[1], $courses[0])==1
            && Core_Math::compareMoney($courses[1], $courses[2])==1
            && Core_Math::compareMoney($courses[2], $courses[4])>=0
            && Core_Math::compareMoney($courses[3], $courses[2])==1
            && Core_Math::compareMoney($courses[3], $courses[4])==1 ) {
                        
            $hight1 = $courses[1]*(1+($percent/100));
            $low1   = $courses[1]*(1-($percent/100));
            $hight2 = $courses[2]*(1+($percentDiffPeak/100));
            $low2   = $courses[2]*(1-($percentDiffPeak/100));
            if (Core_Math::compareMoney($courses[1], $hight2)==1
                && Core_Math::compareMoney($hight1, $courses[3])>=0
                && Core_Math::compareMoney($courses[3], $low1)  >=0
                && Core_Math::compareMoney($low2, $courses[0])  >=0
                    ) {
                
                return true;
            }
        }
        return false;
    }
    
    public static function isDoubleBottom(array $courses, $percent=5, $percentDiffPeak=20) {
        if (Core_Math::compareMoney($courses[0], $courses[1])==1
            && Core_Math::compareMoney($courses[2], $courses[1])==1
            && Core_Math::compareMoney($courses[2], $courses[3])==1
            && Core_Math::compareMoney($courses[4], $courses[3])==1
            && Core_Math::compareMoney($courses[4], $courses[2])>=0 ) {
                        
            $diffPeak = $courses[2]*(1-($percentDiffPeak/100));
            $hight1 = $courses[1]*(1+($percent/100));
            $low1 = $courses[1]*(1-($percent/100));
            $low2 = $courses[2]*(1-($percent/100));
            if (Core_Math::compareMoney($diffPeak, $courses[1])==1
                && Core_Math::compareMoney($hight1, $courses[3])>=0
                && Core_Math::compareMoney($courses[3], $low1)>=0
                && Core_Math::compareMoney($courses[0], $low2)>=0
                    ) {
                
                return true;
            }
        }
        return false;
    }
    
    public static function isHeadShoulders(array $courses, $percent=5) {
        if (count($courses) != 7) {
            return false;
        }
        $hight2 = $courses[2]*(1+($percent/100));
        $low2 = $courses[2]*(1-($percent/100));        
        
        if (Core_Math::compareMoney($courses[1], $courses[0])==1 
            && Core_Math::compareMoney($courses[2], $courses[0])==1
            && Core_Math::compareMoney($courses[3], $courses[0])==1
            && Core_Math::compareMoney($courses[4], $courses[0])==1
            && Core_Math::compareMoney($courses[5], $courses[0])==1
            && Core_Math::compareMoney($courses[6], $courses[0])==1
                
            && Core_Math::compareMoney($courses[1], $courses[2])==1
            && Core_Math::compareMoney($courses[3], $courses[1])==1
            && Core_Math::compareMoney($courses[1], $courses[4])==1
            && Core_Math::compareMoney($courses[5], $courses[1])==1
            && Core_Math::compareMoney($courses[1], $courses[6])==1
                
            && Core_Math::compareMoney($courses[3], $courses[2])==1
            && Core_Math::compareMoney($hight2,     $courses[4])==1
            && Core_Math::compareMoney($courses[4], $low2)==1
            && Core_Math::compareMoney($courses[5], $courses[2])==1
            && Core_Math::compareMoney($courses[2], $courses[6])==1
                
            && Core_Math::compareMoney($courses[3], $courses[4])==1
            && Core_Math::compareMoney($courses[3], $courses[5])==1
            && Core_Math::compareMoney($courses[3], $courses[6])==1
            
            && Core_Math::compareMoney($courses[5], $courses[4])==1
            && Core_Math::compareMoney($courses[4], $courses[6])==1
                
            && Core_Math::compareMoney($courses[5], $courses[6])==1
                                                                    ) {            
            return true;
        }
        return false;
    }
    
//    public static function isHeadShoulders(array $courses, $percent=5, $percentDiffPeak=20) {
//        if (Core_Math::compareMoney($courses[1], $courses[0])==1
//            && Core_Math::compareMoney($courses[1], $courses[2])==1
//            && Core_Math::compareMoney($courses[3], $courses[1])==1
//            && Core_Math::compareMoney($courses[3], $courses[5])==1
//            && Core_Math::compareMoney($courses[5], $courses[1])==1
//            && Core_Math::compareMoney($courses[5], $courses[4])==1
//            && Core_Math::compareMoney($courses[5], $courses[6])==1) {
//                        
//            $hight1 = $courses[1]*(1+($percentDiffPeak/100));
//            $low1   = $courses[1]*(1-($percentDiffPeak/100));
//            $hight2 = $courses[2]*(1+($percent/100));
//            $low2   = $courses[2]*(1-($percent/100));
//            $hight5 = $courses[5]*(1+($percentDiffPeak/100));            
//            if (Core_Math::compareMoney($courses[5], $hight1)>=0
//                    && Core_Math::compareMoney($low1, $courses[2])>=0
//                    && Core_Math::compareMoney($courses[2]*(1-($percentDiffPeak/100)), $courses[0])>=0
//                    && Core_Math::compareMoney($courses[4], $low2)>=0
//                    && Core_Math::compareMoney($hight2, $courses[4])>=0
//                    && Core_Math::compareMoney($courses[3], $hight5)>=0 ) {
//                
//                return true;
//            }
//        }
//        return false;
//    }
//    
    /**
     *  Двойная вершина (дно) double Top. Разворотная фигура
     * для повышательного тренда имеет вид буквы М. Двойная вершина является
     * сигналом более слабым, чем тройная вершина.
     * @param array $courses
     * @param float $percent
     * @return boolean
     */
//    public static function isDoubleTop(array $courses, $percent=null, $sureTrend=null) {
//        if (!$percent) {
//            $percent = self::PERSENT_EQUAL_TREND;
//        }
//        if (!$sureTrend) {
//            $sureTrend = self::SURE_TREND;
//        }
//        $mode = 1;
//        $i=0;
//        $tmpArr = [];
//        $prevCourse = reset($courses);
//        $lineNeck = reset($courses)*(1-($percent/100));
//        foreach ($courses as $key=>$course) {
//            if (++$i==1) {
//                $tmpArr[] = $course;
//                continue;
//            }
//            $tmpArr[] = $course;
//            if ($mode == 1) {
//                if (self::isUpTrend($tmpArr, $percent) or self::isEqualTrend($tmpArr, $percent) ) {
//                    $prevCourse = $course;
//                    continue;
//                }else{
//                    if ($i <= $sureTrend){
//                        // не найден уверенный повышенный тренд
//                        return false;
//                    }
//                    // цена пробивает линию шеи
//                    if (Core_Math::compareMoney($lineNeck, $course)==1) {
//                        return false;
//                    }
//                    $mode = 2;
//                    $firsTop = $prevCourse*(1+($percent/100));
//                    $tmpArr = [$prevCourse, $course];
//                    $prevCourse = $course;
//                    continue;
//                }
//            }elseif($mode == 2) {
//                // цена пробивает линию шеи
//                if (Core_Math::compareMoney($lineNeck, $course)==1) {
//                    return false;
//                }
//                // ищем понижение
//                if (self::isDownTrend($tmpArr, $percent) or self::isEqualTrend($tmpArr, $percent) ) {
//                    $prevCourse = $course;
//                    continue;
//                }else{
//                    // цена пробивает верхнюю линию поддержки $firsTop
//                    if (Core_Math::compareMoney($course, $firsTop)==1) {
//                        return false;
//                    }
//                    $mode = 3;
//                    $firsBottom = min($tmpArr);
//                    $tmpArr = [$prevCourse, $course];
//                    $prevCourse = $course;
//                    continue;
//                }
//            }elseif ($mode == 3) {
//                // цена пробивает верхнюю линию поддержки $firsTop
//                if (Core_Math::compareMoney($course, $firsTop)==1) {
//                    return false;
//                }
//                // цена пробивает линию шеи
//                if (Core_Math::compareMoney($lineNeck, $course)==1) {
//                    return false;
//                }
//                if (self::isUpTrend($tmpArr, $percent) or self::isEqualTrend($tmpArr, $percent) ) {
//                    $prevCourse = $course;
//                    continue;
//                }else{
//                    $mode = 4;
//                    $secondTop = $prevCourse*(1+($percent/100));
//                    $tmpArr = [$prevCourse, $course];
//                    $prevCourse = $course;
//                    continue;
//                }
//            }elseif ($mode == 4) {
//                // цена пробивает верхнюю линию сопротивления $firsTop
//                if (Core_Math::compareMoney($course, $secondTop)==1) {
//                    return false;
//                }
//                if (self::isDownTrend($tmpArr, $percent) or self::isEqualTrend($tmpArr, $percent) ) {
//                    // цена пробивает нижнюю линию поддержки
//                    if (Core_Math::compareMoney($firsBottom, $course)==1) {
//                        return true;
//                    }else{
//                        continue;
//                    }
//                }else{
//                    return false;
//                }
//            }
//        }
//        return false;
//    }
    
}
