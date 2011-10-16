<?php
/*
 * Copyright Cameron Manderson (c) 2011 All rights reserved.
 * Date: 16/10/11
 */
namespace JMS\AopBundle\Tests\Configuration;
use JMS\AopBundle\Configuration\Pointcut;

/**
 *
 * @author cammanderson (cameronmanderson@gmail.com)
 */
class PointcutTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test a match of a method
     */
    public function testMethodMatch()
    {
        $pointcut = new Pointcut(array('value' => 'execute(public JMS\AopBundle\Tests\Configuration\ExampleClass::foo(..))'));

        $class = new \ReflectionClass('JMS\AopBundle\Tests\Configuration\ExampleClass');
        $method = $class->getMethod('foo');
        $this->assertTrue($pointcut->matchesMethod($method), 'Should have matched our execute method ' . $method . ' for ' . $pointcut);

        $method = $class->getMethod('bar');
        $this->assertFalse($pointcut->matchesMethod($method), 'Should not have matched our execute method ' . $method . ' for ' . $pointcut);

        $pointcut = new Pointcut(array('value' => 'call(* JMS\AopBundle\Tests\Configuration\ExampleClass::*Bar(..))'));
        $method = $class->getMethod('fooBar');
        $this->assertTrue($pointcut->matchesMethod($method), 'Should have matched our execute method ' . $method . ' for ' . $pointcut);

    }

    /**
     * We don't support intercepting private method calls
     * @return void
     */
    public function testPrivateMethodMatch()
    {
        $pointcut = new Pointcut(array('value' => 'execute(private JMS\AopBundle\Tests\Configuration\ExampleClass::myFooBar(..))'));
        $class = new \ReflectionClass('JMS\AopBundle\Tests\Configuration\ExampleClass');
        $method = $class->getMethod('foo');

        $this->setExpectedException('\Exception');
        $pointcut->matchesMethod($method);
    }

    /**
     * Test wildcard matches on classes
     */
    public function testClassMatch()
    {
        $pointcut = new Pointcut(array('value' => 'execute(public JMS\*::foo(..))'));

        $method = new \ReflectionMethod('JMS\AopBundle\Tests\Configuration\ExampleClass', 'foo');
        $this->assertTrue($pointcut->matchesMethod($method));
    }
}

class ExampleClass
{
    public function foo()
    {
        return true;
    }

    public function bar()
    {
        return true;
    }
    public function fooBar()
    {
        return true;
    }
    private function myFooBar()
    {
        return true;
    }
}