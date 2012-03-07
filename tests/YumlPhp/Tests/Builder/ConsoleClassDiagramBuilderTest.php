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

use YumlPhp\Builder\Console\ClassesBuilder;

/**
 * ConsoleClassDiagramBuilderTest
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 *
 * @covers YumlPhp\Builder\Console\ClassesBuilder<extended>
 */
class ConsoleClassDiagramBuilderTest extends BaseBuilder
{
    private $builderClass = 'YumlPhp\Builder\Console\ClassesBuilder';
    protected $ns = 'YumlPhp\Tests\Fixtures';

    public function testGetInspector()
    {
        $builder = new ClassesBuilder();
        $builder->configure(array(
            'debug'         => false
        ));

        $this->assertInstanceOf('YumlPhp\Request\RequestInterface', $builder->getInspector());
    }

    public function testType()
    {
        $builder = new ClassesBuilder();

        $this->assertEquals('class',$builder->getType());
    }

    public function ClassesProvider()
    {
        $return = array();

        $map = array(
            "Bar"                                                         => array($this->ns . '\Bar'),
            "Bar"                                                         => array($this->ns . '\Bar', $this->ns . '\Bar'),
            "BarWithExternal Symfony/Component/Console/Input/StringInput" => array($this->ns . '\BarWithExternal'),
            "<<BarInterface>>"                                            => array($this->ns . '\BarInterface'),
            "BarWithInterface <<BarInterface>>"                           => array($this->ns . '\BarWithInterface'),
            "Foo Bazz"                                                    => array($this->ns . '\Foo'),
            '<<FooInterface>> <<BazzInterface>>'                          => array($this->ns . '\FooInterface'),
            "FooBazzWithInterface Bazz <<BazzInterface>>"                 => array($this->ns . '\FooBazzWithInterface'),
        );

        $config = array(
            'withMethods'    => false,
            'withProperties' => false,
            'url'            => '',
            'debug'          => true
        );

        foreach ($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, $classes, $config, array()));
        }

        return $return;
    }

    public function ClassesAndPropertiesProvider()
    {
        $return = array();

        $map = array(
            "Bar-foo;+bar"                                         => array($this->ns . '\Bar'),
            "<<BarInterface>>"                                     => array($this->ns . '\BarInterface'),
            "BarWithInterface <<BarInterface>>-foo;+bar"           => array($this->ns . '\BarWithInterface'),
            "Foo Bazz-foo;+bar"                                    => array($this->ns . '\Foo'),
            //'[<<BazzInterface>>]^-.-[<<FooInterface>>]'                   => array($this->ns.'\FooInterface'),
            "FooBazzWithInterface Bazz <<BazzInterface>>-foo;+bar" => array($this->ns . '\FooBazzWithInterface'),
        );

        $config = array(
            'withMethods'    => false,
            'withProperties' => true,
            'url'            => '',
            'debug'          => true
        );

        foreach ($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, $classes, $config, array()));
        }

        return $return;
    }

    public function ClassesAndMethodsProvider()
    {
        $return = array();

        $map = array(
            "Bar-foo();+bar()"                                         => array($this->ns . '\Bar'),
            "<<BarInterface>>"                                         => array($this->ns . '\BarInterface'),
            "BarWithInterface <<BarInterface>>-foo();+bar()"           => array($this->ns . '\BarWithInterface'),
            "Foo Bazz-foo();+bar()"                                    => array($this->ns . '\Foo'),
            //'[<<BazzInterface>>]^-.-[<<FooInterface>>]'                   => array($this->ns.'\FooInterface'),
            "FooBazzWithInterface Bazz <<BazzInterface>>-foo();+bar()" => array($this->ns . '\FooBazzWithInterface'),
        );

        $config = array(
            'withMethods'    => true,
            'withProperties' => false,
            'url'            => '',
            'debug'          => true
        );

        foreach ($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, $classes, $config, array()));
        }

        return $return;
    }

    public function ClassesPropertiesAndMethodsProvider()
    {
        $return = array();

        $map = array(
            "Bar-foo;+bar-foo();+bar()"                                         => array($this->ns . '\Bar'),
            "<<BarInterface>>"                                                  => array($this->ns . '\BarInterface'),
            "BarWithInterface <<BarInterface>>-foo;+bar-foo();+bar()"           => array($this->ns . '\BarWithInterface'),
            "Foo Bazz-foo;+bar-foo();+bar()"                                    => array($this->ns . '\Foo'),
            //'[<<BazzInterface>>]^-.-[<<FooInterface>>]'                   => array($this->ns.'\FooInterface'),
            "FooBazzWithInterface Bazz <<BazzInterface>>-foo;+bar-foo();+bar()" => array($this->ns . '\FooBazzWithInterface'),
        );

        $config = array(
            'withMethods'    => true,
            'withProperties' => true,
            'url'            => '',
            'debug'          => true
        );

        foreach ($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, $classes, $config, array()));
        }

        return $return;
    }

}