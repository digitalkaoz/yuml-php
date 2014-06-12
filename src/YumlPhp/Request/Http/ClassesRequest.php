<?php

namespace YumlPhp\Request\Http;

use YumlPhp\Request\ClassesRequest as BaseRequest;

/**
 * HttpClassesRequest
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
            $name = $this->buildName($class);
            $parent = $this->buildParent($class, '[', ']^');
            $interfaces = $this->buildInterfaces($class, '<<', '>>]^-.-[');
            $props = $this->buildProperties($class);
            $methods = $this->buildMethods($class);
            $prefix = null;
            $suffix = null;
            $pattern = "%s[%s%s%s%s]";

            if ($class->isInterface()) {
                $prefix = '';
                $suffix = '{bg:orange}';
            }

            //rebuild pattern
            if (count($methods) || count($props)) {
                $pattern = "%s[%s%s|%s%s]";
            }
            if (count($props) && count($methods)) {
                $pattern = "%s[%s%s|%s|%s]";
            }

            $line = sprintf($pattern, $parent, $prefix . join(';', $interfaces), $name, join(';', $props), join(';', $methods) . $suffix);

            if ($class->isInterface()) {
                array_unshift($request, $line);
            } else {
                $request[] = $line;
            }
        }

        natcasesort($request);

        return $request;
    }
}
