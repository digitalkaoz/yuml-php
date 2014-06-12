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

use Symfony\Component\Finder\Finder;
use TokenReflection\Broker;
use TokenReflection\IReflectionClass;
use TokenReflection\IReflectionMethod;

/**
 * The console application that handles the commands
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
abstract class ClassesRequest implements RequestInterface
{
    private $classes = array(), $namespaces = array(), $config = array(), $path;

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function configure(array $config)
    {
        $this->config = $config;
    }

    /**
     * reflects given classes
     *
     * @return array
     */
    protected function getClasses()
    {
        $broker = new Broker(new Broker\Backend\Memory());
        $broker->processDirectory(realpath($this->path));
        $classes = $broker->getClasses();
        sort($classes);

        foreach ($classes as $class) {
            $this->classes[$class->getName()] = $class;
            $this->namespaces[$class->getNamespaceName()] = $class->getNamespaceName();
        }

        return $this->classes;
    }

    /**
     * builds the name for a class
     *
     * @param IReflectionClass $class
     * @param string           $prefix
     * @param string           $suffix
     * @return string
     */
    protected function buildName(IReflectionClass $class, $prefix = '<<', $suffix = '>>')
    {
        $name = $this->prepare($class);

        if ($class->isInterface()) {
            $name = $prefix . $this->prepare($class) . $suffix;
        }

        return $name;
    }

    /**
     * builds the parent for a class
     *
     * @param IReflectionClass $class
     * @param string           $prefix
     * @param string           $suffix
     * @param string           $interfacePrefix
     * @param string           $interfaceSuffix
     * @param string           $interfacesGlue
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
     * @param IReflectionClass $class
     * @param string           $public
     * @param string           $private
     * @return array
     */
    protected function buildProperties(IReflectionClass $class, $public = '+', $private = '-')
    {
        $props = array();

        if (!isset($this->config['withProperties']) || !$this->config['withProperties'] || $class->isInterface()) {
            return $props;
        }

        foreach ($class->getProperties() as $property) {
            if ($property->getDeclaringClass() == $class) {
                $props[] = ($property->isPublic() ? $public : $private) . $property->getName();
            }
        }

        return $props;
    }

    /**
     * collects all methods for current class (only self defined ones)
     *
     * @param IReflectionClass $class
     * @param string           $public
     * @param string           $private
     * @param string           $suffix
     * @return array
     */
    protected function buildMethods(IReflectionClass $class, $public = '+', $private = '-', $suffix = '()')
    {
        $methods = array();

        if (!isset($this->config['withMethods']) || !$this->config['withMethods']) {
            return $methods;
        }

        foreach ($class->getMethods() as $method) {
            /** @var IReflectionMethod $method */
            if (!$method->isAbstract() && $method->getDeclaringClass() == $class && !$class->isInterface()) {
                $methods[] = (!$class->isInterface() ? ($method->isPublic() ? $public : $private) : null) . $method->getName() . $suffix;
            }
        }

        return $methods;
    }

    /**
     * collects all interfaces for current class (only self implemented ones)
     *
     * @param IReflectionClass $class
     * @param string           $prefix
     * @param string           $suffix
     * @return array
     */
    protected function buildInterfaces(IReflectionClass $class, $prefix = '<<', $suffix = '>>')
    {
        $interfaces = array_diff($class->getInterfaces(), ($class->getParentClass() ? $class->getParentClass()->getInterfaces() : array()));

        foreach ($interfaces as $key => $interface) {
            $interfaces[$key] = $prefix . $this->prepare($interface) . $suffix;
        }

        return $interfaces;
    }

    /**
     * prepares a class name into its FQDN with namespace if not found in current class namespaces
     *
     * @param IReflectionClass $class
     * @return string
     */
    protected function prepare(IReflectionClass $class)
    {
        return str_replace('\\', '/', $class->getName());
    }
}
