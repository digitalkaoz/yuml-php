<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Request;

use TokenReflection\Broker;
use TokenReflection\Exception\BaseException;
use TokenReflection\IReflectionClass;
use TokenReflection\IReflectionMethod;
use TokenReflection\IReflectionParameter;
use TokenReflection\IReflectionProperty;

/**
 * The console application that handles the commands
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
abstract class ClassesRequest implements RequestInterface
{
    private $config = array(
        'filter' => array(),
        'withProperties' => false,
        'withMethods' => false,
        'debug' => false
    );

    private $path;

    /**
     * @inheritDoc
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function configure(array $config)
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    /**
     * reflects given classes
     *
     * @return IReflectionClass[]
     */
    protected function getClasses()
    {
        try {
            $broker = new Broker(new Broker\Backend\Memory());
            $broker->processDirectory(realpath($this->path), $this->config['filter']);
            $classes = $broker->getClasses();

            natcasesort($classes);

            return $classes;
        } catch (BaseException $e) {
            throw new \RuntimeException($e->getDetail());
        }
    }

    /**
     * builds the name for a class
     *
     * @param  IReflectionClass $class
     * @param  string           $prefix
     * @param  string           $suffix
     * @return string
     */
    protected function buildName(IReflectionClass $class, $prefix = '<<', $suffix = '>>')
    {
        return $prefix . $this->prepare($class) . $suffix;
    }

    /**
     * builds the parent for a class
     *
     * @param  IReflectionClass $class
     * @param  string           $prefix
     * @param  string           $suffix
     * @param  string           $interfacePrefix
     * @param  string           $interfaceSuffix
     * @param  string           $interfacesGlue
     * @return string
     */
    protected function buildParent(IReflectionClass $class, $prefix = null, $suffix = null, $interfacePrefix = '<<', $interfaceSuffix = '>>', $interfacesGlue = null)
    {
        if (!$class->getParentClass()) {
            return;
        }

        $interfaces = null;
        if ($interfacesGlue) {
            $interfaces = $this->buildInterfaces($class->getParentClass());
            $interfaces = count($interfaces) == 1 ? join($interfacesGlue, ($interfaces)) . $interfacesGlue : join($interfacesGlue, $interfaces);
        }

        $prefix = $class->getParentClass()->isInterface() ? $interfacePrefix : $prefix;
        $suffix = $class->getParentClass()->isInterface() ? $interfaceSuffix : $suffix;

        return $prefix . $interfaces . $this->prepare($class->getParentClass()) . $suffix;
    }

    /**
     * collects all properties for current class (only self defined ones)
     *
     * @param  IReflectionClass $class
     * @param  string           $public
     * @param  string           $private
     * @return array
     */
    protected function buildProperties(IReflectionClass $class, $public = '+', $private = '-')
    {
        $props = array();

        if (!$this->config['withProperties'] || $class->isInterface()) {
            return $props;
        }

        foreach ($class->getProperties() as $property) {
            /** @var IReflectionProperty $property */
            if ($property->getDeclaringClass() == $class) {
                $props[] = ($property->isPublic() ? $public : $private) . $property->getName();
            }
        }

        natcasesort($props);

        return $props;
    }

    /**
     * collects all methods for current class (only self defined ones)
     *
     * @param  IReflectionClass $class
     * @param  string           $public
     * @param  string           $private
     * @param  string           $suffix
     * @return array
     */
    protected function buildMethods(IReflectionClass $class, $public = '+', $private = '-', $suffix = '()')
    {
        $methods = array();

        if (!$this->config['withMethods']) {
            return $methods;
        }

        foreach ($class->getMethods() as $method) {
            /** @var IReflectionMethod $method */
            if (!$method->isAbstract() && $method->getDeclaringClass() == $class && !$class->isInterface()) {
                $methods[] = (!$class->isInterface() ? ($method->isPublic() ? $public : $private) : null) . $method->getName() . $suffix;
            }
        }

        natcasesort($methods);

        return $methods;
    }

    /**
     * extracts usages to other classes
     *
     * @param  IReflectionClass   $class
     * @return IReflectionClass[]
     */
    protected function buildUsages(IReflectionClass $class)
    {
        $usages = array();
        foreach ($class->getMethods() as $method) {
            /** @var IReflectionMethod $method */
            if ($method->getDeclaringClass() !== $class) {
                continue;
            }
            foreach ($method->getParameters() as $parameter) {
                /** @var IReflectionParameter $parameter */
                if ($parameter->getClass()) {
                    $usages[$parameter->getClassName()] = $parameter->getClass();
                }
            };
        }

        return array_unique($usages);
    }

    /**
     * collects all interfaces for current class (only self implemented ones)
     *
     * @param  IReflectionClass $class
     * @param  string           $prefix
     * @param  string           $suffix
     * @return array
     */
    protected function buildInterfaces(IReflectionClass $class, $prefix = '<<', $suffix = '>>')
    {
        $interfaces = array_diff($class->getInterfaces(), ($class->getParentClass() ? $class->getParentClass()->getInterfaces() : array()));

        foreach ($interfaces as $key => $interface) {
            $interfaces[$key] = $prefix . $this->prepare($interface) . $suffix;
        }

        natcasesort($interfaces);

        return $interfaces;
    }

    /**
     * prepares a class name into its FQDN with namespace if not found in current class namespaces
     *
     * @param  IReflectionClass $class
     * @return string
     */
    protected function prepare(IReflectionClass $class)
    {
        return str_replace('\\', '/', $class->getName());
    }
}
