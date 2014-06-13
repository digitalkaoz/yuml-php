<?php

namespace YumlPhp\Request\Http;

use TokenReflection\IReflectionClass;
use YumlPhp\Request\ClassesRequest as BaseRequest;

/**
 * HttpClassesRequest
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
            $this->addAssociations($class, $request);
        }

        natcasesort($request);

        return $request;
    }

    /**
     * @param IReflectionClass $class
     * @param array            $request
     */
    private function addClass(IReflectionClass $class, array &$request)
    {
        $parent = $this->buildParent($class, '[', ']^');
        $interfaces = $this->buildInterfaces($class, '<<', '>>{bg:orange}]^-.-[');
        $props = $this->buildProperties($class);
        $methods = $this->buildMethods($class);
        $pattern = $this->determinePattern($methods, $props);

        $line = sprintf($pattern, $parent, join(';', $interfaces), $this->buildName($class), join(';', $props), join(';', $methods));

        if ($class->isInterface()) {
            array_unshift($request, $line);
        } else {
            $request[] = $line;
        }
    }

    /**
     * @param IReflectionClass $class
     * @param array            $request
     */
    private function addAssociations(IReflectionClass $class, array &$request)
    {
        $usages = $this->buildUsages($class);

        if ($class->isInterface()) {
            return;
        }

        foreach ($usages as $usage) {
            $request[] = sprintf('[%s]-.->[%s]', $this->buildName($class), $this->buildName($usage));
        }
    }

    /**
     * @param array $methods
     * @param array $props
     * @return string
     */
    private function determinePattern(array $methods, array $props)
    {
        $pattern = "%s[%s%s%s%s]";

        //rebuild pattern
        if (count($methods) || count($props)) {
            $pattern = "%s[%s%s|%s%s]";
        }
        if (count($props) && count($methods)) {
            $pattern = "%s[%s%s|%s|%s]";
        }

        return $pattern;
    }
}
