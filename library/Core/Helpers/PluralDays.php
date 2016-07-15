<?php

/**
 * Helper для опредения окончания для множественного числа
 *
 * @author Victor Ivanov
 */
class Core_Helper_PluralDays extends Zend_View_Helper_Abstract {

    private static $_ext = array('день', 'дня', 'дней');
    
    public function pluralDays($n, $viewCount=true) {
        return $this->view->plural($n, self::$_ext, $viewCount);
    }
}
