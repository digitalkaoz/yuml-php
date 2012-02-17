<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Builder;

/**
 * the Console Builder generates a ClassDiagram for Console Output
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class ConsoleClassDiagramBuilder extends Builder
{

    /**
     * builds a request array
     * 
     * @return ConsoleClassDiagramBuilder 
     */
    protected function buildRequest()
    {
        foreach ($this->classes as $class) {
            /** @var $class \ReflectionClass */
            if (in_array($class->getNamespaceName(), $this->namespaces)) {
                $this->request[] = $class->getNamespaceName();
                $this->namespaces[$class->getNamespaceName()] = null;
            }

            $name = $this->buildName($class);
            $parent = $this->buildParent($class, ' ', '', ' <<', '>>', '');
            $interfaces = $this->buildInterfaces($class);
            $props = $this->buildProperties($class, '<info>+</info>', '<highlight>-</highlight>');
            $methods = $this->buildMethods($class, '<info>+</info>', '<highlight>-</highlight>');

            //build pattern
            $pattern = "\t<info>%s</info><note>%s</note>%s";
            $this->request[] = sprintf($pattern, $name, $parent, ($interfaces ? ' ' . join(' ', $interfaces) : null));

            if (count($props)) {
                $pattern = "\t\t%s";
                $this->request[] = sprintf($pattern, join(';', $props));
            }
            if (count($methods)) {
                $pattern = "\t\t%s";
                $this->request[] = sprintf($pattern, join(';', $methods));
            }
        }

        return $this;
    }

    /**
     * returns the class diagram as concatenated string
     * 
     * @return string
     */
    protected function requestDiagram()
    {
        return join("\n", $this->request);
    }

}
