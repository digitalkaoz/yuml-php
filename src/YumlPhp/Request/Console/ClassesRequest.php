<?php

namespace YumlPhp\Request\Console;

use BetterReflection\Reflection\ReflectionClass;
use YumlPhp\Request\ClassesRequest as BaseRequest;

/**
 * ClassesRequest.
 *
 * @author Robert Schönthal <seroscho@gmail.com>
 */
class ClassesRequest extends BaseRequest
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        $request = [];

        foreach ($this->getClasses() as $class) {
            $this->addClass($class, $request);
        }

        return $request;
    }

    /**
     * @param ReflectionClass $class
     * @param array           $request
     */
    private function addClass(ReflectionClass $class, array &$request)
    {
        list($prefix, $suffix) = $this->determinePrefixAndSuffix($class);
        $parent                = $this->buildParent($class, ' ', '');
        $interfaces            = $this->buildInterfaces($class);
        $props                 = $this->buildProperties($class, '<info>+</info>', '<question>-</question>');
        $methods               = $this->buildMethods($class, '<info>+</info>', '<question>-</question>');

        //build pattern
        $request[] = sprintf('<info>%s</info><comment>%s</comment>%s', $this->buildName($class->getName(), $prefix, $suffix), $parent, ($interfaces ? ' ' . implode(' ', $interfaces) : null));

        if (count($props)) {
            $request[] = sprintf("\t%s", implode(';', $props));
        }
        if (count($methods)) {
            $request[] = sprintf("\t%s", implode(';', $methods));
        }
    }
}
