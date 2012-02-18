<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Builder\Http;

use YumlPhp\Request\ClassesRequest;
use YumlPhp\Builder\HttpBuilder;

/**
 * the Yuml Builder generates a ClassDiagram via the http://yuml.me API
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class ClassesBuilder extends HttpBuilder
{
    protected $inspectorClass = 'YumlPhp\Request\ClassesRequest';
        
    /**
     * builds a request array for the yuml API
     * 
     * @return YumlClassDiagramBuilder
     */
    protected function doBuild()
    {
        $inspector = $this->getInspector();
        foreach ($inspector->getClasses() as $class) {

            /** @var $class \ReflectionClass */
            $name = $inspector->buildName($class);
            $parent = $inspector->buildParent($class, '[', ']^');
            $interfaces = $inspector->buildInterfaces($class, '<<', '>>]^-.-[');
            $props = $inspector->buildProperties($class);
            $methods = $inspector->buildMethods($class);

            $prefix = null;
            $suffix = null;
            if ($class->isInterface()) {
                $prefix = '';
                $suffix = '{bg:orange}';
            }
            
            //build pattern
            $pattern = "%s[%s%s%s%s]";
            if (count($methods) || count($props)) {
                $pattern = "%s[%s%s|%s%s]";
            }
            if (count($props) && count($methods)) {
                $pattern = "%s[%s%s|%s|%s]";
            }

            $line = sprintf($pattern, $parent, $prefix.join(';', $interfaces), $name, join(';', $props), join(';', $methods).$suffix);

            if ($class->isInterface()) {
                array_unshift($this->request, $line);
            } else {
                $this->request[] = $line;
            }
        }
        
        return $this;
    }
}
