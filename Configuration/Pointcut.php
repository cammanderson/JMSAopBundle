<?php
/*
 * Copyright 2011 Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace JMS\AopBundle\Configuration;

use JMS\AopBundle\Aop\PointcutInterface;

/**
 * An annotation based declaration of pointcuts supporting a similar format to AspectJ
 * TODO: move out method pattern matching
 * @author cammanderson (camm@flintinteractive.com.au)
 */
class Pointcut
    extends \Doctrine\Common\Annotations\Annotation
    implements PointcutInterface
{
    /* Annotation Value */
    public $value;

    /* Passed vars */
    private $signature;
    private $access;
    private $type;
    private $pattern;

    /**
     * Configure this pointcut
     * @return void
     */
    private function init()
    {
        // Determine if we have our signature parsed yet
        if(empty($this->signature) && trim($this->value)) {
            if(preg_match('/^(execution)[\s]*\((.*)\)$/', $this->value, $matches)) {
                $this->type = $matches[1];
                $this->signature = $matches[2];
                if(preg_match('/^([^\s]+)[\s]+([^\(]*)/', $this->signature, $matches)) {
                    $this->access = $matches[1];
                    if(!preg_match('/(\*|public)/', $this->access)) {
                        throw new \Exception('AOP only can intercept public method calls');
                    }
                    $this->pattern = $matches[2];
                    // Look for class namespacing, escape for regex
                    if(strpos($this->pattern, '\\') > -1) {
                        $this->pattern = str_replace('\\', '\\\\', $this->pattern);
                    }
                    if(strpos($this->pattern, '*') > -1) {
                        $this->pattern = str_replace('*', '.*', $this->pattern);
                    }
                } else {
                    throw new Exception('Unable to parse AOP pointcut signature');
                }
            } else {
                throw new Exception('Unable to configure pointcut, pointcut format not supported');
            }
        }
    }


    /**
     * Determines whether the advice applies to instances of the given class.
     *
     * There are some limits as to what you can do in this method. Namely, you may
     * only base your decision on resources that are part of the ContainerBuilder.
     * Specifically, you may not use any data in the class itself, such as
     * annotations.
     *
     * @param \ReflectionClass $class
     * @return boolean
     */
    function matchesClass(\ReflectionClass $class)
    {
        return true;
    }

    /**
     * Determines whether the advice applies to the given method.
     *
     * This method is not limited in the way the matchesClass method is. It may
     * use information in the associated class to make its decision.
     *
     * @param \ReflectionMethod $method
     * @return boolean
     */
    function matchesMethod(\ReflectionMethod $method)
    {
        $this->init();
        if('private' == strtolower($this->access) && !$method->isPrivate()) return false;
        return 0 < preg_match('#'.$this->pattern.'#', sprintf('%s::%s', $method->class, $method->name));
    }

    public function __toString()
    {
        $str = '@Pointcut[';
        $str .= 'value="' . $this->value .'"';
        $str .= ', pattern="' . $this->pattern . '"';
        $str .= ']';
        return $str;
    }
}
