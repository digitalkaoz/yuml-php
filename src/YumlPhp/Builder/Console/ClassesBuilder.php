<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Builder\Console;

use YumlPhp\Request\ClassesRequest;
use YumlPhp\Builder\ConsoleBuilder;

/**
 * the Console Builder generates a ClassDiagram for Console Output
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class ClassesBuilder extends ConsoleBuilder
{
    const TYPE = 'class';
    protected $inspectorClass = 'YumlPhp\Request\ClassesRequest';

    /**
     * builds a request array
     *
     * @return ConsoleClassDiagramBuilder
     */
    protected function doBuild()
    {
        $inspector = $this->getInspector();

        foreach ($inspector->getClasses($this->path) as $class) {
            /** @var $class \ReflectionClass */
            $name = $inspector->buildName($class);
            $parent = $inspector->buildParent($class, ' ', '', ' <<', '>>', '');
            $interfaces = $inspector->buildInterfaces($class);
            $props = $inspector->buildProperties($class, '<info>+</info>', '<highlight>-</highlight>');
            $methods = $inspector->buildMethods($class, '<info>+</info>', '<highlight>-</highlight>');

            //build pattern
            $pattern = "<info>%s</info><note>%s</note>%s";
            $this->request[] = sprintf($pattern, $name, $parent, ($interfaces ? ' ' . join(' ', $interfaces) : null));

            if (count($props)) {
                $pattern = "\t%s";
                $this->request[] = sprintf($pattern, join(';', $props));
            }
            if (count($methods)) {
                $pattern = "\t%s";
                $this->request[] = sprintf($pattern, join(';', $methods));
            }
        }

        return $this;
    }
}