<?php

namespace YumlPhp\Builder;

use Symfony\Component\Finder\Finder;
use YumlPhp\Analyzer\File;

/**
 * Description of Builder
 *
 * @author caziel
 */
abstract class Builder implements BuilderInterface
{
    protected $path, $classes = array(), $request = array(), $configuration = array(), $namespaces = array();

    abstract protected function buildRequest();    
    abstract protected function requestDiagram();
    
    /**
     * @inheritDoc 
     */
    public function configure($config)
    {
        $this->configuration = $config;

        return $this;
    }

    /**
     * sets the path to crawl for classes
     * 
     * @param string $path
     * @return ClassDiagramBuilder 
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function build()
    {
        $this->classes = $this->findClasses();
        
        return $this
            ->buildRequest()            
            ->requestDiagram()
        ;
    }

    /**
     * find all classes in given path
     * 
     * @return array
     */
    protected function findClasses()
    {
        $files = Finder::create()->files()->name('*.php')->in($this->path);
        $classes = array();
        $map = array();

        foreach ($files as $file) {
            @require_once($file);
            $classes = array_merge($classes, array_keys(File::getClassesInFile($file->getRealPath())));
        }

        sort($classes);
        
        foreach($classes as $name)
        {
            $class = new \ReflectionClass($name);;
            $map[$name] = $class;
            $this->namespaces[$class->getNamespaceName()] = $class->getNamespaceName();
        }

        return $map;
    }
        
    protected function buildName(\ReflectionClass $class)
    {
        $name = $this->prepare($class);

        if ($class->isInterface()) {
            $name = '<<'.$this->prepare($class).'>>';
        }
        
        return $name;
    }
    
    protected function buildProperties(\ReflectionClass $class, $public = '+', $private = '-')
    {
        $props = array();
        
        if (!$this->configuration['withProperties'] || $class->isInterface()) {
            return $props;
        }
        
        foreach ($class->getProperties() as $property) {                    
            if ($property->getDeclaringClass() == $class) {
                $props[] = ($property->isPublic() ? $public : $private).$property->getName();
            }
        }
        
        return $props;
    }

    protected function buildMethods(\ReflectionClass $class, $public = '+', $private = '-')
    {
        $methods = array();
        
        if (!$this->configuration['withMethods']) {
            return $methods;
        }
        
        foreach ($class->getMethods() as $method) {                    
            if ($method->getDeclaringClass() == $class || $class->isInterface()) {
                $methods[] = (!$class->isInterface() ? ($method->isPublic() ? $public : $private) : null).$method->getName().'()';
            }
        }
        
        return $methods;
    }
    
    protected function buildInterfaces(\ReflectionClass $class)
    {        
        if($class->isInterface()){
            return array();
        }
                
        $interfaces = array_diff($class->getInterfaces(),($class->getParentClass() ? $class->getParentClass()->getInterfaces() : array()));
             
        foreach ($interfaces as $key => $interface) {
            $interfaces[$key] ='<<'.$this->prepare($interface) . '>>';
        }
        
        return $interfaces;
    }
    
    /**
     * prepares a class name into its FQDN if namespace not found in folder
     * 
     * @param \ReflectionClass $class
     * @return string 
     */
    protected function prepare(\ReflectionClass $class)
    {
        $name = $class->getName();
        
        return substr($name, strripos($name, '\\')+1);
        
        if (in_array($class->getNamespaceName(), array_keys($this->namespaces))) {            
            return substr($name, strripos($name, '\\')+1);
        }
        
        return $name;
    }     
}
