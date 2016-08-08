<?php

/**
 * Helper для опредения окончания для множественного числа в родительном падеже (кого? чего?)
 *
 * @author Victor Ivanov
 */
class Core_Helper_PluralDaysGenitive extends Zend_View_Helper_Abstract {

    private static $_ext = array('дня', 'дней', 'дней');
    
    public function pluralDaysGenitive($n, $viewCount=true) {
        return $this->view->plural($n, self::$_ext, $viewCount);
    }
}
