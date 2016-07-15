<?php

/**
* 
*/
class Core_Mail extends Zend_Mail
{
    // email админа
    private static $_adminEmail=null;
    // email сайта
    private static $_siteEmail=null;        
    private static $_defaultCharset = 'utf-8';

    
    public static function setCharset($charset) {
        self::$_defaultCharset = (string)$charset;
    }
    
    public static function setAdminEmail($email) {
        self::$_adminEmail = (string)$email;
    }
    
    
    public static function getAdminEmail() {
        return self::$_adminEmail;
    }
    

    public static function setSiteEmail($email) {
        self::$_siteEmail = (string)$email;
    }

    
    public static function getSiteEmail() {
        return self::$_siteEmail;
    }
    
    public static function valuesUpMail(Mail_Model $item, $name) {
        $mail = new Zend_Mail(self::$_defaultCharset);
        $mail->setBodyHtml(self::_setBodyView('quotations-up', array('mess'=>$item)));
        $mail->setFrom(self::getSiteEmail());
        $mail->addTo(self::getAdminEmail());
        $mail->setSubject('Курс '.$name.' вырос '.$item->getDay().' д > '.$item->getPercent().'%');
//        if (APPLICATION_ENV == 'production') {
            $mail->send();
//        }
        return true;
    }
    
    public static function quotationsUpMail(Mail_Model $item) {
        return self::valuesUpMail($item, 'валюты');
    }
    
    public static function metalsUpMail(Mail_Model $item) {
        return self::valuesUpMail($item, 'металла');
    }
    
    public static function valuesDownMail(Mail_Model $item, $name) {
        $mail = new Zend_Mail(self::$_defaultCharset);
        $mail->setBodyHtml(self::_setBodyView('quotations-down', array('mess'=>$item)));
        $mail->setFrom(self::getSiteEmail());
        $mail->addTo(self::getAdminEmail());
        $mail->setSubject('Курс '.$name.' упал '.$item->getDay().' д > '.$item->getPercent().'%');
//        if (APPLICATION_ENV == 'production') {
            $mail->send();
//        }
        return true;
    }
    
    public static function quotationsDownMail(Mail_Model $item) {
        return self::valuesDownMail($item, 'валюты');
    }
    
    public static function metalsDownMail(Mail_Model $item) {
        return self::valuesDownMail($item, 'металла');
    }

    protected static function _setBodyView($script, $params = array()) {
        $layout = new Zend_Layout(array('layoutPath' => APPLICATION_PATH . '/layouts/scripts'));
        $layout->setLayout('email');

        $view = new Zend_View();
        $view->setScriptPath(APPLICATION_PATH . '/views/scripts/email');
        $view->setHelperPath('Core/Helpers', 'Core_Helper');

        foreach ($params as $key => $value) {
            $view->assign($key, $value);
        }

        $layout->content = $view->render($script . '.phtml');
        $layout->footer = $view->render('footer.phtml');
        return $layout->render();
    }
    
}
