<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Db
 *
 * @author Viktor
 */
class Core_Queue_Adapter_Db extends Zend_Queue_Adapter_Db {
    
    
    public function hasTasksMessage($message) 
    {
        $maxMessages = 1;
        $timeout = self::RECEIVE_TIMEOUT_DEFAULT;        
        $queue = $this->_queue;
        $msgs      = array();
        $info      = $this->_messageTable->info();
        $microtime = microtime(true); // cache microtime
        $db        = $this->_messageTable->getAdapter();

        $query = $db->select();
        if ($this->_options['options'][Zend_Db_Select::FOR_UPDATE]) {
            $query->forUpdate();
        }
        $query->from($info['name'], array('*'))
              ->where('queue_id=?', $this->getQueueId($queue->getName()))
              ->where('handle IS NULL OR timeout+' . (int)$timeout . ' < ' . (int)$microtime)
              ->where('body=?', $message)
              ->limit($maxMessages);
        $rows = $db->fetchAll($query);
        return (boolean) count($rows);
    }
    
}
