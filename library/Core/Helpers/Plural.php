<?php

/**
 * Helper для опредения окончания для множественного числа
 *
 * @author Victor Ivanov
 */
class Core_Helper_Plural extends Zend_View_Helper_Abstract {


    // пример использования
    // $this->plural(1, array(‘день’, ‘дня’, ‘дней’));  -   день
    // $this->plural(3, array(‘день’, ‘дня’, ‘дней’));  -   дня
    // $this->plural(10, array(‘день’, ‘дня’, ‘дней’));  -   дней
    
    
    /**
     * кончания для множественного числа
     * @param int $n
     * @param array $array  - массив значений
     * @param bool $viewCount - показывать ли само значение
     * @return string
     */
    public function plural($n, array $array, $viewCount=true) 
    {
        $result = '';        
        if ($viewCount) {
            $result .= $n.' ';
        }
        $n = (int)$n;
        $plural = ($n % 10 == 1 && $n % 100 != 11 ? 0 : ($n % 10 >= 2 && $n % 10 <= 4 && ($n % 100 < 10 or $n % 100 >= 20) ? 1 : 2));
        return $result.$array[$plural];
    }
}
