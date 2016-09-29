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
        $courses = array_values($courses);
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
        $courses = array_values($courses);
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
        $courses = array_values($courses);
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
        $courses = array_values($courses);
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
        $courses = array_values($courses);
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
        if (count($courses) != 5) {
            return false;
        }
        $courses = array_values($courses);
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
        if (count($courses) != 5) {
            return false;
        }
        $courses = array_values($courses);
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
        $courses = array_values($courses);
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
    
    
    public static function isReverseHeadShoulders(array $courses, $percent=5) {
        if (count($courses) != 7) {
            return false;
        }
        $courses = array_values($courses);
        $hight2 = $courses[2]*(1+($percent/100));
        $low2 = $courses[2]*(1-($percent/100));        
        
        if (Core_Math::compareMoney($courses[0], $courses[1])==1 
            && Core_Math::compareMoney($courses[0], $courses[2])==1 
            && Core_Math::compareMoney($courses[0], $courses[3])==1 
            && Core_Math::compareMoney($courses[0], $courses[4])==1 
            && Core_Math::compareMoney($courses[0], $courses[5])==1 
            && Core_Math::compareMoney($courses[0], $courses[6])==1 
                
            && Core_Math::compareMoney($courses[2], $courses[1])==1 
            && Core_Math::compareMoney($courses[1], $courses[3])==1 
            && Core_Math::compareMoney($courses[4], $courses[1])==1 
            && Core_Math::compareMoney($courses[1], $courses[5])==1 
            && Core_Math::compareMoney($courses[6], $courses[1])==1 
                
            && Core_Math::compareMoney($courses[2], $courses[3])==1 
            && Core_Math::compareMoney($courses[2], $courses[5])==1 
            && Core_Math::compareMoney($hight2, $courses[4])==1 
            && Core_Math::compareMoney($courses[4], $low2)==1 
            && Core_Math::compareMoney($courses[6], $courses[2])==1
                
            && Core_Math::compareMoney($courses[4], $courses[3])==1 
            && Core_Math::compareMoney($courses[5], $courses[3])==1 
            && Core_Math::compareMoney($courses[6], $courses[3])==1 
                
            && Core_Math::compareMoney($courses[4], $courses[5])==1 
            && Core_Math::compareMoney($courses[6], $courses[4])==1 
                
            && Core_Math::compareMoney($courses[6], $courses[5])==1 
                                                                    ) {
            
            return true;
        }
        return false;
    }
    
    
    public static function isTripleTop(array $courses, $percentBottom=5, $percentTop=10) {
        if (count($courses) != 7) {
            return false;
        }
        $courses = array_values($courses);
        
        $hightTop = $courses[1]*(1+($percentTop/100));
        $lowTop = $courses[1]*(1-($percentTop/100));        
        
        $hightBottom = $courses[2]*(1+($percentBottom/100));
        $lowBottom = $courses[2]*(1-($percentBottom/100));  
        
        if (Core_Math::compareMoney($courses[1], $courses[0])==1 
            && Core_Math::compareMoney($courses[2], $courses[0])==1 
            && Core_Math::compareMoney($courses[3], $courses[0])==1 
            && Core_Math::compareMoney($courses[4], $courses[0])==1 
            && Core_Math::compareMoney($courses[5], $courses[0])==1 
            && Core_Math::compareMoney($courses[6], $courses[0])==1 

            && Core_Math::compareMoney($courses[1], $courses[2])==1 
            && Core_Math::compareMoney($hightTop, $courses[3])==1 
            && Core_Math::compareMoney($courses[3], $lowTop)==1
            && Core_Math::compareMoney($courses[1], $courses[4])==1 
            && Core_Math::compareMoney($hightTop, $courses[5])==1 
            && Core_Math::compareMoney($courses[5], $lowTop)==1
            && Core_Math::compareMoney($courses[1], $courses[6])==1 

            && Core_Math::compareMoney($courses[3], $courses[2])==1 
            && Core_Math::compareMoney($hightBottom, $courses[4])==1 
            && Core_Math::compareMoney($courses[4], $lowBottom)==1
            && Core_Math::compareMoney($courses[5], $courses[2])==1 
            && Core_Math::compareMoney($courses[2], $courses[6])==1 

            && Core_Math::compareMoney($courses[3], $courses[4])==1 
            && Core_Math::compareMoney($hightTop, $courses[5])==1 
            && Core_Math::compareMoney($courses[5], $lowTop)==1
            && Core_Math::compareMoney($courses[3], $courses[6])==1 

            && Core_Math::compareMoney($courses[5], $courses[4])==1 
            && Core_Math::compareMoney($courses[4], $courses[6])==1 

            && Core_Math::compareMoney($courses[5], $courses[6])==1                 
                                                                    ) {
            return true;
        }
        return false;
    }


    public static function isTripleBottom(array $courses, $percentBottom=5, $percentTop=10) {
        if (count($courses) != 7) {
            return false;
        }
        $courses = array_values($courses);
        
        $hightBottom = $courses[1]*(1+($percentBottom/100));
        $lowBottom = $courses[1]*(1-($percentBottom/100));        
        
        $hightTop = $courses[2]*(1+($percentTop/100));
        $lowTop = $courses[2]*(1-($percentTop/100));
        
        if (Core_Math::compareMoney($courses[0], $courses[1])==1 
            && Core_Math::compareMoney($courses[0], $courses[2])==1 
            && Core_Math::compareMoney($courses[0], $courses[3])==1 
            && Core_Math::compareMoney($courses[0], $courses[4])==1 
            && Core_Math::compareMoney($courses[0], $courses[5])==1 
            && Core_Math::compareMoney($courses[0], $courses[6])==1 
                
            && Core_Math::compareMoney($courses[2], $courses[1])==1 
            && Core_Math::compareMoney($hightBottom, $courses[3])==1 
            && Core_Math::compareMoney($courses[3], $lowBottom)==1 
            && Core_Math::compareMoney($courses[4], $courses[1])==1 
            && Core_Math::compareMoney($hightBottom, $courses[5])==1 
            && Core_Math::compareMoney($courses[5], $lowBottom)==1 
            && Core_Math::compareMoney($courses[6], $courses[1])==1 
                
            && Core_Math::compareMoney($courses[2], $courses[3])==1 
            && Core_Math::compareMoney($hightTop, $courses[4])==1 
            && Core_Math::compareMoney($courses[4], $lowTop)==1 
            && Core_Math::compareMoney($courses[2], $courses[5])==1 
            && Core_Math::compareMoney($courses[6], $courses[2])==1 
            
            && Core_Math::compareMoney($courses[4], $courses[3])==1 
            && Core_Math::compareMoney($hightBottom, $courses[5])==1 
            && Core_Math::compareMoney($courses[5], $lowBottom)==1 
            && Core_Math::compareMoney($courses[6], $courses[3])==1 
            
            && Core_Math::compareMoney($courses[4], $courses[5])==1 
            && Core_Math::compareMoney($courses[6], $courses[4])==1 
            
            && Core_Math::compareMoney($courses[6], $courses[5])==1 
                                                                    ){
            return true;
        }
        return false;
    }
    
    
    /**
     * «Восходящий» треугольник - верхняя граница треугольника образует 
     * горизонтальную (или почти горизонтальную) линию сопротивления, 
     * нижняя граница треугольника имеет восходящий наклон. Амплитуда колебаний внутри треугольника снижается. 
     * @param array $courses
     * @return boolean
     */
    public static function isAscendingTriangle(array $courses, $percentHorizon=1) {
        if (count($courses) < 5) {
            return false;
        }
        $courses = array_values($courses);
        // определяем точку на горизонт. линии
        if (Core_Math::compareMoney($courses[0], $courses[1]) == 1) {
            $startKey = 2;
        }elseif(Core_Math::compareMoney($courses[1], $courses[0]) == 1) {
            $startKey = 1;
        }else{
            return false;
        }
        $hightHorizon   = $courses[$startKey]*(1+($percentHorizon/100));
        $lowHorizon     = $courses[$startKey]*(1-($percentHorizon/100));
        
        // определяем процент линии сопротивления
        if (Core_Math::compareMoney($courses[$startKey+1], $courses[$startKey+3]) == 1) {
            return false;
        }
        $percentSup = ($courses[$startKey+3]*100 / $courses[$startKey+1])-100;
        
        $i=0;
        $listHorizon = $listSup = array();
        for ($index=$startKey; $index < count($courses); $index++) {
            if ($i++ & 1) {
                $listSup[] = $courses[$index];
            } else { 
                $listHorizon[] = $courses[$index];
            }
        }

        if (!(self::isEqualChannel($listHorizon, $percentHorizon) && self::isUpChannel($listSup, $percentSup))) {
            return false;
        }
        return true;
    }
    
}
