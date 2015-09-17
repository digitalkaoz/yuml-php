<?php

namespace YumlPhp\Request\Http;

use BetterReflection\Reflection\ReflectionClass;
use YumlPhp\Request\ClassesRequest as BaseRequest;

/**
 * HttpClassesRequest.
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
            $this->addAssociations($class, $request);
        }

        natcasesort($request);

        return $request;
    }

    /**
     * @param ReflectionClass $class
     * @param array           $request
     */
    private function addClass(ReflectionClass $class, array &$request)
    {
        list($prefix, $suffix) = $this->determinePrefixAndSuffix($class);

        $parent     = $this->buildParent($class, '[', ']^');
        $interfaces = $this->buildInterfaces($class, '<<', '>>{bg:orange}]^-.-[');
        $props      = $this->buildProperties($class);
        $methods    = $this->buildMethods($class);
        $pattern    = $this->determinePattern($methods, $props);

        $line = sprintf($pattern, $parent, implode(';', $interfaces), $this->buildName($class->getName(), $prefix, $suffix), implode(';', $props), implode(';', $methods));

        if ($class->isInterface()) {
            array_unshift($request, $line);
        } else {
            $request[] = $line;
        }
    }

    /**
     * @param ReflectionClass $class
     * @param array           $request
     */
    private function addAssociations(ReflectionClass $class, array &$request)
    {
        $usages                      = $this->buildUsages($class);
        list($ownPrefix, $ownSuffix) = $this->determinePrefixAndSuffix($class);

        if ($class->isInterface()) {
            return;
        }

        foreach ($usages as $usage) {
            if ($usage instanceof ReflectionClass) {
                list($prefix, $suffix) = $this->determinePrefixAndSuffix($usage);
                $usage                 = $usage->getName();
            } else {
                $prefix = null;
                $suffix = '{bg:yellow}';
            }

            $request[] = sprintf('[%s]-.->[%s]', $this->buildName($class->getName(), $ownPrefix, $ownSuffix), $this->buildName($usage, $prefix, $suffix));
        }
    }

    /**
     * @param ReflectionClass $class
     *
     * @return array
     */
    protected function determinePrefixAndSuffix(ReflectionClass $class)
    {
        list($prefix, $suffix) = parent::determinePrefixAndSuffix($class);

        if ($this->isInterface($class)) {
            $suffix .= '{bg:orange}';
        } elseif ($class->isAbstract()) {
            $suffix = '{bg:blue}';
        }

        return [$prefix, $suffix];
    }

    /**
     * @param array $methods
     * @param array $props
     *
     * @return string
     */
    private function determinePattern(array $methods, array $props)
    {
        $pattern = '%s[%s%s%s%s]';

        //rebuild pattern
        if (count($methods) || count($props)) {
            $pattern = '%s[%s%s|%s%s]';
        }
        if (count($props) && count($methods)) {
            $pattern = '%s[%s%s|%s|%s]';
        }

        return $pattern;
    }
}
