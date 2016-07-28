<?php
/**
 * математические действия
 * 
 */
class Core_Math {

    /**
     * правильное округление чисел с плав точкой
     * @param float||string||integer $number      - число для округления
     * @param integer $scale   - сколько знаков после запятой оставить
     * @param boolean $typeRound- тип округления false - если с отбросом, true- если математич. По умолчанию TRUE
     * @return float
     */
    public static function round($number, $scale=6, $typeRound=true ) {
        $number = (float)$number;
        $valRound=($typeRound)?0.5:0;
        $pow = pow(10, $scale);
        if ($number>0){
            $number = (floor(($number*$pow) + $valRound + 1/pow(10, $scale+2)))/$pow;   // прибавим 0.0001 если округление до двух знаков
        }elseif($number<0){
            $number = (ceil(($number*$pow) - $valRound -  1/pow(10, $scale+2)))/$pow;   // убиваем 0.0001 если округление до двух знаков
        }
        return sprintf("%.".$scale."f", $number);
    }
    
    /**
     * сравнение двоичных чисел
     * @param <type> $left
     * @param <type> $right
     * @param integer $scale   - сколько знаков после запятой сравнивать
     * @return результат сравнения <br /> 1 - левое > правого <br /> -1 - правое > левое <br /> 0 - равны
     */
    public static function compare($left, $right, $scale=2) {
        if (!is_float($left)){
            $left = (float)$left;
        }
        $left = self::round($left, $scale);
        if (!is_float($right)){
            $right = (float)$right;
        }
        $right = self::round($right, $scale);
        
        if ($left > $right) {
            return 1;
        }
        return ($left == $right)?0:-1;        
    }
    
    /**
     * сравнение денежных величин в зависимости от настойки разрядности
     * @param type $left
     * @param type $right
     * @return результат сравнения <br /> 1 - левое > правого <br /> -1 - правое > левое <br /> 0 - равны
     */
    public static function compareMoney($left, $right) {
        return self::compare($left, $right, Core_Container::getService('setting')->fractMoney());
    }    
    
}
