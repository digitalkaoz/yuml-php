<?php

namespace YumlPhp\Request\Console;

use TokenReflection\IReflectionClass;
use YumlPhp\Request\ClassesRequest as BaseRequest;

/**
 * ClassesRequest
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
            /** @var $class IReflectionClass */
            $name = $this->buildName($class);
            $parent = $this->buildParent($class, ' ', '', ' <<', '>>', '');
            $interfaces = $this->buildInterfaces($class);
            $props = $this->buildProperties($class, '<info>+</info>', '<question>-</question>');
            $methods = $this->buildMethods($class, '<info>+</info>', '<question>-</question>');

            //build pattern
            $pattern = "<info>%s</info><comment>%s</comment>%s";
            $request[] = sprintf($pattern, $name, $parent, ($interfaces ? ' ' . join(' ', $interfaces) : null));

            if (count($props)) {
                $pattern = "\t%s";
                $request[] = sprintf($pattern, join(';', $props));
            }
            if (count($methods)) {
                $pattern = "\t%s";
                $request[] = sprintf($pattern, join(';', $methods));
            }
        }

        return $request;
    }
}
