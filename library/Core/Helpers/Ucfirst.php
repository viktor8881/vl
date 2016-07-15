<?php


/**
 * вывод первой буквы в верхнем регистре
 */
class Core_Helper_Ucfirst extends Zend_View_Helper_Url 
{    
    
    /**
     * Заглавная буква в слове для UTF-8
     * @param string $string
     * @return string
     */
    public function ucfirst($string, $wordFormat="UTF-8" )
    {
        if (!function_exists('mb_ucfirst') && function_exists('mb_substr')) {
          $string = mb_ereg_replace("^[\ ]+","", $string);
          $string = mb_strtoupper(mb_substr($string, 0, 1, $wordFormat), $wordFormat).mb_substr($string, 1, mb_strlen($string), $wordFormat);
          return $string;
        }else{
            return mb_ucfirst($string);
        }
    }
    
    
}
