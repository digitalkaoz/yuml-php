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

use YumlPhp\Request\RequestInterface;
use YumlPhp\Analyzer\File;

/**
 * The console application that handles the commands
 * 
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class ClassesRequest implements RequestInterface
{
    private $classes = array(), $namespaces = array(),$config = array(), $path;
    
    public function setPath($path)
    {
        $this->path = $path;
    }
    
    public function setConfig($config)
    {
        $this->config = $config;
    }
    
    /**
     * reflects given classes
     * 
     * @param array $config
     * @param string path
     */
    public function getClasses()
    {
        foreach ($this->findClasses() as $class) {
            if (!is_object($class)) {
                $class = new \ReflectionClass($class);
            }
            $this->classes[$class->getName()] = $class;
            $this->namespaces[$class->getNamespaceName()] = $class->getNamespaceName();
        }
        
        return $this->classes;
    }

    /**
     * find all classes in given path
     * 
     * @return array
     */
    public function findClasses()
    {
        $files = Finder::create()->files()->name('*.php')->in($this->path);
        
        $classes = array();

        foreach ($files as $file) {
            @require_once($file);
            $classes = array_merge($classes, array_keys(File::getClassesInFile($file->getRealPath())));
        }

        return $classes;
    }

    /**
     * builds the name for a class
     * 
     * @param \ReflectionClass $class
     * @param string $prefix
     * @param string $suffix
     * @return string 
     */
    public function buildName(\ReflectionClass $class, $prefix = '<<', $suffix = '>>')
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
     * @param \ReflectionClass $class
     * @param string $prefix
     * @param string $suffix
     * @param string $interfacePrefix
     * @param string $interfaceSuffix
     * @param string $interfacesGlue
     * @return string 
     */
    public function buildParent(\ReflectionClass $class, $prefix = null, $suffix = null, $interfacePrefix = '<<', $interfaceSuffix = '>>', $interfacesGlue = null)
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
     * @param \ReflectionClass $class
     * @param string $public
     * @param string $private
     * @return array
     */
    public function buildProperties(\ReflectionClass $class, $public = '+', $private = '-')
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
     * @param \ReflectionClass $class
     * @param string $public
     * @param string $private
     * @param string $suffix
     * @return array
     */
    public function buildMethods(\ReflectionClass $class, $public = '+', $private = '-', $suffix = '()')
    {
        $methods = array();

        if (!isset($this->config['withMethods']) || !$this->config['withMethods']) {
            return $methods;
        }
        
        foreach ($class->getMethods() as $method) {
            if (!$method->isAbstract() && $method->getDeclaringClass() == $class && !$class->isInterface()) {
                $methods[] = (!$class->isInterface() ? ($method->isPublic() ? $public : $private) : null) . $method->getName() . $suffix;
            }
        }

        return $methods;
    }

    /**
     * collects all interfaces for current class (only self implemented ones)
     * 
     * @param \ReflectionClass $class
     * @param string $prefix
     * @param string $suffix
     * @return array
     */
    public function buildInterfaces(\ReflectionClass $class, $prefix = '<<', $suffix = '>>')
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
     * @param \ReflectionClass $class
     * @return string 
     */
    protected function prepare(\ReflectionClass $class)
    {
        return str_replace('\\', '/', $class->getName());
    }    
}
