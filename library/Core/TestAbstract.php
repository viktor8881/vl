<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TestAbstract
 *
 * @author Viktor
 */
use PHPUnit\Framework\TestCase;


class Core_TestAbstract extends TestCase {
    
    
    protected static function callProtectedMethod($className, $methodName) {
      $class = new ReflectionClass($className);
      $method = $class->getMethod($methodName);
      $method->setAccessible(true);
      return $method;
    }
    
    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    protected function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
