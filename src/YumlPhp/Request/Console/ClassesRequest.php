<?php

namespace YumlPhp\Request\Console;

use TokenReflection\IReflectionClass;
use YumlPhp\Request\ClassesRequest as BaseRequest;

/**
 * ClassesRequest
 *
 * @author Robert Schönthal <seroscho@gmail.com>
 */
class ClassesRequest extends BaseRequest
{
    /**
     * @inheritDoc
     */
    public function build()
    {
        $request = array();

        foreach ($this->getClasses() as $class) {
            $this->addClass($class, $request);
        }

        return $request;
    }

    /**
     * @param IReflectionClass $class
     * @param array            $request
     */
    private function addClass(IReflectionClass $class, array &$request)
    {
        list($prefix, $suffix) = $this->determinePrefixAndSuffix($class);
        $parent = $this->buildParent($class, ' ', '', ' <<', '>>', '');
        $interfaces = $this->buildInterfaces($class);
        $props = $this->buildProperties($class, '<info>+</info>', '<question>-</question>');
        $methods = $this->buildMethods($class, '<info>+</info>', '<question>-</question>');

        //build pattern
        $request[] = sprintf("<info>%s</info><comment>%s</comment>%s", $this->buildName($class, $prefix, $suffix), $parent, ($interfaces ? ' ' . join(' ', $interfaces) : null));

        if (count($props)) {
            $request[] = sprintf("\t%s", join(';', $props));
        }
        if (count($methods)) {
            $request[] = sprintf("\t%s", join(';', $methods));
        }
    }
}
