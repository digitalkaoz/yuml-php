<?php

namespace YumlPhp\Tests\Builder;

use YumlPhp\Builder\ConsoleClassDiagramBuilder;

/**
 * Description of YumlClassDiagramBuilderTest
 *
 * @author caziel
 * @covers YumlPhp\Builder\ConsoleClassDiagramBuilder
 * @covers YumlPhp\Builder\Builder
 * @covers YumlPhp\Builder\BuilderInterface
 */
class ConsoleClassDiagramBuilderTest extends BaseBuilder
{
    private $builderClass = 'YumlPhp\Builder\ConsoleClassDiagramBuilder';
    private $ns = 'YumlPhp\Tests\Fixtures';
        
    public function ClassesProvider()
    {        
        $return = array();
        
        $map = array(
          "Bar"     => array($this->ns.'\Bar'),
          "Bar"     => array($this->ns.'\Bar',$this->ns.'\Bar'),
          "BarWithExternal Symfony\\Component\\Console\\Input\\StringInput" => array($this->ns.'\BarWithExternal'),
          "<<BarInterface>>"                             => array($this->ns.'\BarInterface'),
          "BarWithInterface <<BarInterface>>"            => array($this->ns.'\BarWithInterface'),
          "Foo Bazz"                                     => array($this->ns.'\Foo'),
          '<<FooInterface>> <<BazzInterface>>'       => array($this->ns.'\FooInterface'),
          "FooBazzWithInterface Bazz <<BazzInterface>>"  => array($this->ns.'\FooBazzWithInterface'),
        );

        $config = array(
          'withMethods' => false,
          'withProperties' => false,
          'url' => '',
          'debug' => true
        );
        
        foreach($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass,array('findClasses'),$classes, $config,array()));
        }
                       
        return $return;
    }
        
    public function ClassesAndPropertiesProvider()
    {        
        $return = array();
        
        $map = array(
          "Bar-foo;+bar"                                             => array($this->ns.'\Bar'),
          "<<BarInterface>>"                                          => array($this->ns.'\BarInterface'),
          "BarWithInterface <<BarInterface>>-foo;+bar"                => array($this->ns.'\BarWithInterface'),
          "Foo Bazz-foo;+bar"                                      => array($this->ns.'\Foo'),
          //'[<<BazzInterface>>]^-.-[<<FooInterface>>]'                   => array($this->ns.'\FooInterface'),
          "FooBazzWithInterface Bazz <<BazzInterface>>-foo;+bar"  => array($this->ns.'\FooBazzWithInterface'),
        );

        $config = array(
          'withMethods' => false,
          'withProperties' => true,
          'url' => '',
          'debug' => true
        );
        
        foreach($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, array('findClasses'),$classes, $config,array()));
        }
                       
        return $return;
    }    
    public function ClassesAndMethodsProvider()
    {        
        $return = array();
        
        $map = array(
          "Bar-foo();+bar()"                                             => array($this->ns.'\Bar'),
          "<<BarInterface>>"                                          => array($this->ns.'\BarInterface'),
          "BarWithInterface <<BarInterface>>-foo();+bar()"                => array($this->ns.'\BarWithInterface'),
          "Foo Bazz-foo();+bar()"                                      => array($this->ns.'\Foo'),
          //'[<<BazzInterface>>]^-.-[<<FooInterface>>]'                   => array($this->ns.'\FooInterface'),
          "FooBazzWithInterface Bazz <<BazzInterface>>-foo();+bar()"  => array($this->ns.'\FooBazzWithInterface'),
        );

        $config = array(
          'withMethods' => true,
          'withProperties' => false,
          'url' => '',
          'debug' => true
        );
        
        foreach($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, array('findClasses'),$classes, $config,array()));
        }
                       
        return $return;
    }
    
    public function ClassesPropertiesAndMethodsProvider()
    {        
        $return = array();
        
        $map = array(
          "Bar-foo;+bar-foo();+bar()"                                             => array($this->ns.'\Bar'),
          "<<BarInterface>>"                                          => array($this->ns.'\BarInterface'),
          "BarWithInterface <<BarInterface>>-foo;+bar-foo();+bar()"                => array($this->ns.'\BarWithInterface'),
          "Foo Bazz-foo;+bar-foo();+bar()"                                      => array($this->ns.'\Foo'),
          //'[<<BazzInterface>>]^-.-[<<FooInterface>>]'                   => array($this->ns.'\FooInterface'),
          "FooBazzWithInterface Bazz <<BazzInterface>>-foo;+bar-foo();+bar()"  => array($this->ns.'\FooBazzWithInterface'),
        );

        $config = array(
          'withMethods' => true,
          'withProperties' => true,
          'url' => '',
          'debug' => true
        );
        
        foreach($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, array('findClasses'),$classes, $config,array()));
        }
                       
        return $return;
    }    
}