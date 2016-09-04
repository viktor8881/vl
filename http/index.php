<?php
set_time_limit(0);
define('APPLICATION_ENV', 'development');

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

//pr(get_include_path()); exit;

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();


//==============================================================================
if (!function_exists('_')) {
    function _($text=null) {
        if(!$text) {
            return '';
        }
        $registry = Zend_Registry::getInstance();
        if ($registry->offsetExists('Zend_Translate')) {
            $translate = $registry->get('Zend_Translate');    
            $str = $translate->translate($text);
            return $str;
        }
        return $text;
    }
}

function pr($value) {
    echo '<pre>';
    print_r($value);
    echo '</pre>';
}