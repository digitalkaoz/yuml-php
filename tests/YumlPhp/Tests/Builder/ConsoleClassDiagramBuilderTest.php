<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Tests\Builder;

use YumlPhp\Builder\ConsoleClassDiagramBuilder;

/**
 * ConsoleClassDiagramBuilderTest
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 * 
 * @covers YumlPhp\Builder\ConsoleClassDiagramBuilder<extended>
 */
class ConsoleClassDiagramBuilderTest extends BaseBuilder
{

    private $builderClass = 'YumlPhp\Builder\ConsoleClassDiagramBuilder';
    private $ns = 'YumlPhp\Tests\Fixtures';

    public function ClassesProvider()
    {
        $return = array();

        $map = array(
          $this->ns . "Bar" => array($this->ns . '\Bar'),
          $this->ns . "Bar" => array($this->ns . '\Bar', $this->ns . '\Bar'),
          $this->ns . "BarWithExternal Symfony\\Component\\Console\\Input\\StringInput" => array($this->ns . '\BarWithExternal'),
          $this->ns . "<<BarInterface>>" => array($this->ns . '\BarInterface'),
          $this->ns . "BarWithInterface <<BarInterface>>" => array($this->ns . '\BarWithInterface'),
          $this->ns . "Foo Bazz" => array($this->ns . '\Foo'),
          $this->ns . '<<FooInterface>> <<BazzInterface>>' => array($this->ns . '\FooInterface'),
          $this->ns . "FooBazzWithInterface Bazz <<BazzInterface>>" => array($this->ns . '\FooBazzWithInterface'),
        );

        $config = array(
          'withMethods' => false,
          'withProperties' => false,
          'url' => '',
          'debug' => true
        );

        foreach ($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, array('findClasses'), $classes, $config, array()));
        }

        return $return;
    }

    public function ClassesAndPropertiesProvider()
    {
        $return = array();

        $map = array(
          $this->ns . "Bar-foo;+bar" => array($this->ns . '\Bar'),
          $this->ns . "<<BarInterface>>" => array($this->ns . '\BarInterface'),
          $this->ns . "BarWithInterface <<BarInterface>>-foo;+bar" => array($this->ns . '\BarWithInterface'),
          $this->ns . "Foo Bazz-foo;+bar" => array($this->ns . '\Foo'),
          //'[<<BazzInterface>>]^-.-[<<FooInterface>>]'                   => array($this->ns.'\FooInterface'),
          $this->ns . "FooBazzWithInterface Bazz <<BazzInterface>>-foo;+bar" => array($this->ns . '\FooBazzWithInterface'),
        );

        $config = array(
          'withMethods' => false,
          'withProperties' => true,
          'url' => '',
          'debug' => true
        );

        foreach ($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, array('findClasses'), $classes, $config, array()));
        }

        return $return;
    }

    public function ClassesAndMethodsProvider()
    {
        $return = array();

        $map = array(
          $this->ns . "Bar-foo();+bar()" => array($this->ns . '\Bar'),
          $this->ns . "<<BarInterface>>" => array($this->ns . '\BarInterface'),
          $this->ns . "BarWithInterface <<BarInterface>>-foo();+bar()" => array($this->ns . '\BarWithInterface'),
          $this->ns . "Foo Bazz-foo();+bar()" => array($this->ns . '\Foo'),
          //'[<<BazzInterface>>]^-.-[<<FooInterface>>]'                   => array($this->ns.'\FooInterface'),
          $this->ns . "FooBazzWithInterface Bazz <<BazzInterface>>-foo();+bar()" => array($this->ns . '\FooBazzWithInterface'),
        );

        $config = array(
          'withMethods' => true,
          'withProperties' => false,
          'url' => '',
          'debug' => true
        );

        foreach ($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, array('findClasses'), $classes, $config, array()));
        }

        return $return;
    }

    public function ClassesPropertiesAndMethodsProvider()
    {
        $return = array();

        $map = array(
          $this->ns . "Bar-foo;+bar-foo();+bar()" => array($this->ns . '\Bar'),
          $this->ns . "<<BarInterface>>" => array($this->ns . '\BarInterface'),
          $this->ns . "BarWithInterface <<BarInterface>>-foo;+bar-foo();+bar()" => array($this->ns . '\BarWithInterface'),
          $this->ns . "Foo Bazz-foo;+bar-foo();+bar()" => array($this->ns . '\Foo'),
          //'[<<BazzInterface>>]^-.-[<<FooInterface>>]'                   => array($this->ns.'\FooInterface'),
          $this->ns . "FooBazzWithInterface Bazz <<BazzInterface>>-foo;+bar-foo();+bar()" => array($this->ns . '\FooBazzWithInterface'),
        );

        $config = array(
          'withMethods' => true,
          'withProperties' => true,
          'url' => '',
          'debug' => true
        );

        foreach ($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, array('findClasses'), $classes, $config, array()));
        }

        return $return;
    }

}