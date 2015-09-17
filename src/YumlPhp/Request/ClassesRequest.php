<?php

namespace YumlPhp\Request;

use BetterReflection\Reflection\ReflectionClass;
use BetterReflection\Reflection\ReflectionMethod;
use BetterReflection\Reflection\ReflectionParameter;
use BetterReflection\Reflection\ReflectionProperty;
use BetterReflection\Reflector\ClassReflector;
use BetterReflection\Reflector\Exception\IdentifierNotFound;
use BetterReflection\SourceLocator\StringSourceLocator;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * The console application that handles the commands.
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
abstract class ClassesRequest implements RequestInterface
{
    private $config = [
        'filter'         => [],
        'withProperties' => false,
        'withMethods'    => false,
        'debug'          => false,
    ];

    private $path;

    /**
     * {@inheritdoc}
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function configure(array $config)
    {
        $this->config = array_merge($this->config, $config);

        return $this;
    }

    /**
     * reflects given classes.
     *
     * @return ReflectionClass[]
     */
    protected function getClasses()
    {
        $finder = Finder::create()->files()->name('*.php');

        foreach ($this->config['filter'] as $filter) {
            $finder->notName($filter);
        }

        $files = $finder->in(realpath($this->path));

        $content = null;
        foreach ($files as $file) {
            /* @var SplFileInfo $file */
            if (!is_null($content)) {
                $fileContent = str_replace(['<?php', '<?', '?>'], '', $file->getContents());
            } else {
                $fileContent = $file->getContents();
            }
            $content .= $fileContent;
        }
        $locator = new StringSourceLocator($content);
        $classes = (new ClassReflector($locator))->getAllClasses();

        return $classes;
    }

    /**
     * builds the name for a class.
     *
     * @param string $className
     * @param string $prefix
     * @param string $suffix
     *
     * @return string
     */
    protected function buildName($className, $prefix = '<<', $suffix = '>>')
    {
        return $prefix . $this->prepareClassName($className) . $suffix;
    }

    /**
     * builds the parent for a class.
     *
     * @param ReflectionClass $class
     * @param string          $prefix
     * @param string          $suffix
     *
     * @return string
     */
    protected function buildParent(ReflectionClass $class, $prefix = null, $suffix = null)
    {
        try {
            if ($class->getParentClass()) {
                return $prefix . $this->prepare($class->getParentClass()) . $suffix;
            }
        } catch (IdentifierNotFound $e) {
            return $prefix . $this->prepareClassName($e->getClassName()) . $suffix;
        }
    }

    /**
     * collects all properties for current class (only self defined ones).
     *
     * @param ReflectionClass $class
     * @param string          $public
     * @param string          $private
     *
     * @return array
     */
    protected function buildProperties(ReflectionClass $class, $public = '+', $private = '-')
    {
        $props = [];

        if (!$this->config['withProperties'] || $class->isInterface()) {
            return $props;
        }

        foreach ($class->getProperties() as $property) {
            /* @var ReflectionProperty $property */
            $props[] = ($property->isPublic() ? $public : $private) . $property->getName();
        }

        natcasesort($props);

        return $props;
    }

    /**
     * collects all methods for current class (only self defined ones).
     *
     * @param ReflectionClass $class
     * @param string          $public
     * @param string          $private
     * @param string          $suffix
     *
     * @return array
     */
    protected function buildMethods(ReflectionClass $class, $public = '+', $private = '-', $suffix = '()')
    {
        $methods = [];

        if (!$this->config['withMethods'] || $class->isInterface()) {
            return $methods;
        }

        foreach ($class->getImmediateMethods() as $method) {
            /** @var ReflectionMethod $method */
            if (!$method->isAbstract()) {
                $methods[] = (!$class->isInterface() ? ($method->isPublic() ? $public : $private) : null) . $method->getName() . $suffix;
            }
        }

        natcasesort($methods);

        return $methods;
    }

    /**
     * extracts usages to other classes.
     *
     * @param ReflectionClass $class
     *
     * @return ReflectionClass[]
     */
    protected function buildUsages(ReflectionClass $class)
    {
        $usages = [];
        foreach ($class->getImmediateMethods() as $method) {
            /** @var ReflectionMethod $method */
            foreach ($method->getParameters() as $parameter) {
                /* @var ReflectionParameter $parameter */

                try {
                    if ($parameter->getClass()) {
                        $usages[$parameter->getClass()->getName()] = $parameter->getClass()->getName();
                    }
                } catch (IdentifierNotFound $e) {
                    $usages[$e->getClassName()] = $e->getClassName();
                }
            };
        }

        return array_unique($usages);
    }

    /**
     * collects all interfaces for current class (only self implemented ones).
     *
     * @param ReflectionClass $class
     * @param string          $prefix
     * @param string          $suffix
     *
     * @return array
     */
    protected function buildInterfaces(ReflectionClass $class, $prefix = '<<', $suffix = '>>')
    {
        try {
            $parentInterfaces = $class->getParentClass() ? $class->getParentClass()->getInterfaces() : [];
        } catch (IdentifierNotFound $e) {
            $parentInterfaces = [];
        }

        $interfaces = array_diff($class->getImmediateInterfaces(), $parentInterfaces);

        foreach ($interfaces as $key => $interface) {
            $interfaces[$key] = $prefix . $this->prepare($interface) . $suffix;
        }

        natcasesort($interfaces);

        return $interfaces;
    }

    /**
     * prepares a class name into its FQDN with namespace if not found in current class namespaces.
     *
     * @param ReflectionClass $class
     *
     * @return string
     */
    protected function prepare(ReflectionClass $class)
    {
        return $this->prepareClassName($class->getName());
    }

    /**
     * prepares a class name into its FQDN with namespace if not found in current class namespaces.
     *
     * @param string $className
     *
     * @return string
     */
    protected function prepareClassName($className)
    {
        return str_replace('\\', '/', $className);
    }

    /**
     * @param ReflectionClass $class
     *
     * @return array
     */
    protected function determinePrefixAndSuffix(ReflectionClass $class)
    {
        $prefix = null;
        $suffix = null;

        if ($this->isInterface($class)) {
            $suffix = '>>';
            $prefix = '<<';
        }

        return [$prefix, $suffix];
    }

    /**
     * @param ReflectionClass $class
     *
     * @return bool
     */
    protected function isInterface(ReflectionClass $class)
    {
        return $class->isInterface() || substr($class->getName(), -strlen('Interface')) === 'Interface';
    }
}
