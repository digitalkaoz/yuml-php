<?php

namespace YumlPhp\Builder;

/**
 * Description of ClassDiagramBuilder
 *
 * @author caziel
 */
class ConsoleClassDiagramBuilder extends Builder
{
    public function buildRequest()
    {
        foreach ($this->classes as $class) {
            /** @var $class \ReflectionClass */
            if(in_array($class->getNamespaceName(), $this->namespaces)){
               $this->request[] = $class->getNamespaceName();
               $this->namespaces[$class->getNamespaceName()] = null;
            }
            
            $name = $this->buildName($class);
            $parent = $this->buildParent($class);
            $interfaces = $this->buildInterfaces($class);
            $props = $this->buildProperties($class);
            $methods = $this->buildMethods($class);
                                             
            //build pattern
            $pattern = "\t<info>%s</info><note>%s</note>%s";
            $this->request[] = sprintf($pattern, $name, $parent, ($interfaces ? ' '.join(' ',$interfaces) : null));
            
            if(count($props)) {
                $pattern = "\t\t%s";
                $this->request[] = sprintf($pattern, join(';',$props));
            }
            if(count($methods)) {
                $pattern = "\t\t%s";
                $this->request[] = sprintf($pattern, join(';',$methods));
            }            
        }
        
        return $this;
    }

    protected function buildParent(\ReflectionClass $class)
    {
        if(!$class->getParentClass()) {
          return null;  
        } elseif($class->isInterface() && $class->getParentClass()->isInterface()) {
            return ' <<'.$this->prepare($class->getParentClass()).'>>';
        }else {
            return ' '.$this->prepare($class->getParentClass());
        }
    }
    
    protected function buildProperties(\ReflectionClass $class, $public = '+', $private = '-')
    {
        return parent::buildProperties($class, '<info>+</info>', '<highlight>-</highlight>');
    }

    protected function buildMethods(\ReflectionClass $class, $public = '+', $private = '-')
    {
        return parent::buildMethods($class, '<info>+</info>', '<highlight>-</highlight>');
    }
    
    protected function requestDiagram()
    {
        return join("\n",$this->request);
    }

}
