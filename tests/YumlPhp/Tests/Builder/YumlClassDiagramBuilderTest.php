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

use YumlPhp\Builder\YumlClassDiagramBuilder;
use Buzz\Browser;
/**
 * Description of YumlClassDiagramBuilderTest
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 * 
 * @covers YumlPhp\Builder\YumlClassDiagramBuilder<extended>
 */
class YumlClassDiagramBuilderTest extends BaseBuilder
{

    private $builderClass = 'YumlPhp\Builder\YumlClassDiagramBuilder';
    private $namespace = 'YumlPhp\Tests\Fixtures';

    public function testYumlApi()
    {
        $classes = array($this->namespace . '\FooBazzWithInterface');
        $config = array(
          'withMethods' => true,
          'withProperties' => true,
          'url' => 'http://yuml.me/diagram/plain;dir:TB/class/',
          'debug' => false
        );

        $browser = new Browser();
        $browser->getClient()->setTimeout(5);
        $builder = $this->getBuilder($this->builderClass, array('findClasses'), $classes, $config, array($browser));
        $result = $builder->build();

        $this->assertInternalType('array', $result);
        $this->assertGreaterThan(0, count($result));

        foreach ($result as $message) {
            $url = explode(' ', $message);
            $response = $browser->get($url[1]);

            switch ($url[0]) {
                case '<info>PNG</info>' : $contentType = 'image/png';
                    break;
                case '<info>PDF</info>' : $contentType = 'application/pdf';
                    break;
                case '<info>URL</info>' : $contentType = 'text/html; charset=utf-8';
                    break;
            }
            $this->assertEquals($contentType, $response->getHeader('Content-Type'));
        }
    }

    public function ClassesProvider()
    {
        $return = array();

        $map = array(
          '[Bar]' => array($this->namespace . '\Bar'),
          '[Symfony\Component\Console\Input\StringInput{bg:white}]^[BarWithExternal]' => array($this->namespace . '\BarWithExternal'),
          '[Bar]' => array($this->namespace . '\Bar', $this->namespace . '\Bar'),
          '[<<BarInterface>>{bg:orange}],[Bar]' => array($this->namespace . '\Bar', $this->namespace . '\BarInterface'),
          '[<<BarInterface>>{bg:orange}]' => array($this->namespace . '\BarInterface'),
          '[<<BarInterface>>]^-.-[BarWithInterface]' => array($this->namespace . '\BarWithInterface'),
          '[Bazz]^[Foo]' => array($this->namespace . '\Foo'),
          '[<<BazzInterface>>]^-.-[<<FooInterface>>{bg:orange}]' => array($this->namespace.'\FooInterface'),
          '[Bazz]^[<<BazzInterface>>]^-.-[FooBazzWithInterface]' => array($this->namespace . '\FooBazzWithInterface'),
        );

        $config = array(
          'withMethods' => false,
          'withProperties' => false,
          'url' => '',
          'debug' => true
        );

        foreach ($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, array('findClasses'), $classes, $config, array($this->getMock('\Buzz\Browser'))));
        }

        return $return;
    }

    public function ClassesAndPropertiesProvider()
    {
        $return = array();

        $map = array(
          '[Bar|-foo;+bar]' => array($this->namespace . '\Bar'),
          '[<<BarInterface>>{bg:orange}]' => array($this->namespace . '\BarInterface'),
          '[<<BarInterface>>]^-.-[BarWithInterface|-foo;+bar]' => array($this->namespace . '\BarWithInterface'),
          '[Bazz]^[Foo|-foo;+bar]' => array($this->namespace . '\Foo'),
          //'[<<BazzInterface>>]^-.-[<<FooInterface>>]'                   => array($this->namespace.'\FooInterface'),
          '[Bazz]^[<<BazzInterface>>]^-.-[FooBazzWithInterface|-foo;+bar]' => array($this->namespace . '\FooBazzWithInterface'),
        );

        $config = array(
          'withMethods' => false,
          'withProperties' => true,
          'url' => '',
          'debug' => true
        );

        foreach ($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, array('findClasses'), $classes, $config, array($this->getMock('\Buzz\Browser'))));
        }

        return $return;
    }

    public function ClassesAndMethodsProvider()
    {
        $return = array();

        $map = array(
          '[Bar|-foo();+bar()]' => array($this->namespace . '\Bar'),
          '[<<BazzInterface>>]^-.-[<<FooInterface>>{bg:orange}]' => array($this->namespace . '\FooInterface'),
          '[<<BarInterface>>]^-.-[BarWithInterface|-foo();+bar()]' => array($this->namespace . '\BarWithInterface'),
          '[Bazz]^[Foo|-foo();+bar()]' => array($this->namespace . '\Foo'),
          //'[<<BazzInterface>>]^-.-[<<FooInterface>>]'                     => array($this->namespace.'\FooInterface'),
          '[Bazz]^[<<BazzInterface>>]^-.-[FooBazzWithInterface|-foo();+bar()]' => array($this->namespace . '\FooBazzWithInterface'),
        );

        $config = array(
          'withMethods' => true,
          'withProperties' => false,
          'url' => '',
          'debug' => true
        );

        foreach ($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, array('findClasses'), $classes, $config, array($this->getMock('\Buzz\Browser'))));
        }

        return $return;
    }

    public function ClassesPropertiesAndMethodsProvider()
    {
        $return = array();

        $map = array(
          '[Bar|-foo;+bar|-foo();+bar()]' => array($this->namespace . '\Bar'),
          '[<<BazzInterface>>]^-.-[<<FooInterface>>{bg:orange}]' => array($this->namespace . '\FooInterface'),
          '[<<BarInterface>>]^-.-[BarWithInterface|-foo;+bar|-foo();+bar()]' => array($this->namespace . '\BarWithInterface'),
          '[Bazz]^[Foo|-foo;+bar|-foo();+bar()]' => array($this->namespace . '\Foo'),
          //'[<<BazzInterface>>]^-.-[<<FooInterface>>]'       => array($this->namespace.'\FooInterface'),
          '[Bazz]^[<<BazzInterface>>]^-.-[FooBazzWithInterface|-foo;+bar|-foo();+bar()]' => array($this->namespace . '\FooBazzWithInterface'),
        );

        $config = array(
          'withMethods' => true,
          'withProperties' => true,
          'url' => '',
          'debug' => true
        );

        foreach ($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, array('findClasses'), $classes, $config, array($this->getMock('\Buzz\Browser'))));
        }

        return $return;
    }

}
