<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Core_Log
 *
 * @author Viktor Ivanov
 */
class Core_Log extends Zend_Log {

    private static $_log=null;
    
    
    public function __construct(\Zend_Log_Writer_Abstract $writer = null) {
        parent::__construct($writer);
        self::$_log = $this;
    }
    
    /**
     * 
     * @return Core_Log
     */
    public static function getLog() {
        return self::$_log;
    }
    

    /**
     * запись исключений
     * @param type $message  - сообщение
     * @param type $priority - приоритет   
     * @param Exception $exc
     * @param Zend_Controller_Request_Abstract $request
     */
    public static function logExc($message, $priority, Exception $exc, Zend_Controller_Request_Abstract $request)
    {
        $message .= " \n Произошло исключение - ".$exc->getMessage().
                    "\n".$exc->getFile()." - LINE".$exc->getLine().
                    "\n".$exc->getTraceAsString();        
        $message .= "\n Параметры запроса\n";
        $params = array();
        $params[] = 'url => '.$request->getServer('REQUEST_URI');
        foreach ($request->getParams() as $key=>$param){
            if (is_resource($param) or is_object($param)){
                continue;
            }
            if (is_array($param)){
                $param = serialize($param);
            }
            $params[] = $key." => ".$param;
        }
        $message .= implode("\n", $params);
        return self::$_log->log($message, $priority);
    }
    
}

