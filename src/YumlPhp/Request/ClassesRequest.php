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
use YumlPhp\Request\RequestInterface as BaseRequestInterface;


/**
 * The console application that handles the commands
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class ClassesRequest implements BaseRequestInterface
{
    private $classes = array(), $namespaces = array(), $config = array(), $path;

    public function __construct()
    {
        spl_autoload_register(array($this, 'loadClass'), true, false);
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function configure($config)
    {
        $this->config = $config;
    }

    /**
     * reflects given classes
     *
     * @param array $config
     * @param       string path
     */
    public function getClasses()
    {
        foreach ($this->findClasses() as $class) {
            if (!is_object($class)) {
                try {
                    $class = new \ReflectionClass($class);
                } catch (\ReflectionException $e) {
                    continue;
                }
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
            $classes = array_merge($classes, array_keys(File::getClassesInFile($file->getRealPath())));
        }

        return $classes;
    }

    /**
     * Loads the given class or interface.
     *
     * @param string $class The name of the class
     * @return Boolean|null True, if loaded
     */
    public function loadClass($class)
    {
        if ($file = $this->findFile($class)) {
            require $file;
            return true;
        }
    }

    /**
     * Finds the path to the file where the class is defined.
     *
     * @param string $class The name of the class
     *
     * @return string|null The path, if found
     */
    public function findFile($class)
    {
        if ('\\' == $class[0]) {
            $class = substr($class, 1);
        }

        if (false !== $pos = strrpos($class, '\\')) {
            // namespaced class name
            $classPath = str_replace('\\', DIRECTORY_SEPARATOR, substr($class, 0, $pos)) . DIRECTORY_SEPARATOR;
            $className = substr($class, $pos + 1);
        } else {
            // PEAR-like class name
            $classPath = null;
            $className = $class;
        }

        $classPath .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        $path = realpath(isset($this->config['autoload_path']) ? $this->config['autoload_path'] : $this->path);

        if (file_exists($path . DIRECTORY_SEPARATOR . $classPath)) {
            return $path . DIRECTORY_SEPARATOR . $classPath;
        } elseif(file_exists($this->path . DIRECTORY_SEPARATOR . $classPath)) {
            return $this->path . DIRECTORY_SEPARATOR . $classPath;
        }

        if ($file = stream_resolve_include_path($classPath)) {
            return $file;
        }
    }

    /**
     * builds the name for a class
     *
     * @param \ReflectionClass $class
     * @param string           $prefix
     * @param string           $suffix
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
     * @param string           $prefix
     * @param string           $suffix
     * @param string           $interfacePrefix
     * @param string           $interfaceSuffix
     * @param string           $interfacesGlue
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
     * @param string           $public
     * @param string           $private
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
     * @param string           $public
     * @param string           $private
     * @param string           $suffix
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
     * @param string           $prefix
     * @param string           $suffix
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
