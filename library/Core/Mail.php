<?php

/**
* 
*/
class Core_Mail extends Zend_Mail
{
    // email админа
    private static $_adminEmail;
    // email сайта
    private static $_siteEmail;
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
    
    
    public static function sendAnalysisCurrency($currency, AnalysisCurrency_Model_OverTime $overtime=null, array $percents, array $figures) {
        $mail = new Zend_Mail(self::$_defaultCharset);
        $mail->setFrom(self::getSiteEmail());
        $mail->addTo(self::getAdminEmail());
        $mail->setSubject('Валюта '.$currency->getName());
        $layout = self::getLayout();
        $view = self::getView();
        $layoutСontent = '';
        $layout->investmentName = $currency->getName();
        if ($overtime) {
            $view->assign('overtime', $overtime);
            $layoutСontent .= $view->render('overtime.phtml');
        }
        if (count($percents)) {
            $view->clearVars();
            $view->assign('percents', $percents);
            $layoutСontent .= $view->render('percents.phtml');
        }
        if (count($figures)) {
            $view->clearVars();
            $view->assign('figures', $figures);
            $layoutСontent .= $view->render('figures.phtml');
        }
        
        if ($layoutСontent) {
            $layout->content = $layoutСontent;
            $layout->footer = $view->render('footer.phtml');
            $mail->setBodyHtml($layout->render());
            $mail->send();
        }
    }
    
    public static function sendAnalysisMetal($metal, AnalysisMetal_Model_OverTime $overtime=null, array $percents, array $figures) {
        $mail = new Zend_Mail(self::$_defaultCharset);
        $mail->setFrom(self::getSiteEmail());
        $mail->addTo(self::getAdminEmail());
        $mail->setSubject('Метал '.$metal->getName());
        $layout = self::getLayout();
        $view = self::getView();
        $layoutСontent = '';
        $layout->investmentName = $metal->getName();
        if ($overtime) {
            $view->assign('overtime', $overtime);
            $layoutСontent .= $view->render('overtime.phtml');
        }
        if (count($percents)) {
            $view->clearVars();
            $view->assign('percents', $percents);
            $layoutСontent .= $view->render('percents.phtml');
        }
        if (count($figures)) {
            $view->clearVars();
            $view->assign('figures', $figures);
            $layoutСontent .= $view->render('figures.phtml');
        }
        if ($layoutСontent) {
            $layout->content = $layoutСontent;
            $layout->footer = $view->render('footer.phtml');
            $mail->setBodyHtml($layout->render());
            $mail->send();
        }
    }
    
    public static function sendAutoInvest(BalanceCurrency_Collection $balanceCurrency, 
                                        BalanceMetal_Collection $balanceMetal, 
                                        CourseCurrency_Collection $courseCurrency, 
                                        CourseMetal_Collection $courseMetal, 
                                        Core_Date $date, $currentValue) {
        $mail = new Zend_Mail(self::$_defaultCharset);
        $mail->setFrom(self::getSiteEmail());
        $mail->addTo(self::getAdminEmail());
        $mail->setSubject('Результат анализа на '.$date->formatDMY());
        $layout = self::getLayout('email_autoinvest');
        $view = self::getView();
        
        $view->assign('balanceCurrency', $balanceCurrency);
        $view->assign('balanceMetal', $balanceMetal);
        $view->assign('courseCurrency', $courseCurrency);
        $view->assign('courseMetal', $courseMetal);
        $view->assign('currentValue', $currentValue);
        $layout->currentValue = $view->formatMoney($currentValue, true);
        $layout->content = $view->render('autoinvest.phtml');
        $layout->footer = $view->render('footer.phtml');
        $mail->setBodyHtml($layout->render());
        $mail->send();        
    }

    private static function getLayout($name = 'email') {
        $layout = new Zend_Layout(array('layoutPath' => APPLICATION_PATH . '/layouts/scripts'));
        $layout->setLayout($name);
        return $layout;
    }
    
    private static function getView() {        
        $view = new Zend_View();
        $view->setScriptPath(APPLICATION_PATH . '/views/scripts/email');
        $view->setHelperPath('Core/Helpers', 'Core_Helper');
        $view->addHelperPath(APPLICATION_PATH.'/views/helpers', 'View_Helper');
        $view->clearVars();
        return $view;
    }

    
}
