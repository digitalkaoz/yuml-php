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

use YumlPhp\Builder\Http\ClassesBuilder;
use Buzz\Browser;

/**
 * Description of YumlClassDiagramBuilderTest
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 *
 * @covers YumlPhp\Builder\Http\ClassesBuilder<extended>
 */
class YumlClassDiagramBuilderTest extends BaseBuilder
{
    private $builderClass = 'YumlPhp\Builder\Http\ClassesBuilder';
    protected $ns = 'YumlPhp\Tests\Fixtures';

    public function testType()
    {
        $builder = new ClassesBuilder($this->getMock('Buzz\\Browser'));

        $this->assertEquals('class',$builder->getType());
    }

    public function ClassesProvider()
    {
        $return = array();

        $map = array(
            '[Bar]'                                                           => array($this->ns . '\Bar'),
            '[Symfony/Component/Console/Input/StringInput]^[BarWithExternal]' => array($this->ns . '\BarWithExternal'),
            '[Bar]'                                                           => array($this->ns . '\Bar', $this->ns . '\Bar'),
            '[<<BarInterface>>{bg:orange}],[Bar]'                             => array($this->ns . '\Bar', $this->ns . '\BarInterface'),
            '[<<BarInterface>>{bg:orange}]'                                   => array($this->ns . '\BarInterface'),
            '[<<BarInterface>>]^-.-[BarWithInterface]'                        => array($this->ns . '\BarWithInterface'),
            '[Bazz]^[Foo]'                                                    => array($this->ns . '\Foo'),
            '[<<BazzInterface>>]^-.-[<<FooInterface>>{bg:orange}]'            => array($this->ns . '\FooInterface'),
            '[Bazz]^[<<BazzInterface>>]^-.-[FooBazzWithInterface]'            => array($this->ns . '\FooBazzWithInterface'),
        );

        $config = array(
            'withMethods'    => false,
            'withProperties' => false,
            'url'            => '',
            'debug'          => true,
            'autoload_path'  => __DIR__ . '/../Fixtures'
        );

        foreach ($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, $classes, $config, array($this->getMock('\Buzz\Browser'))));
        }

        return $return;
    }

    public function ClassesAndPropertiesProvider()
    {
        $return = array();

        $map = array(
            '[Bar|-foo;+bar]'                                                        => array($this->ns . '\Bar'),
            '[<<BarInterface>>{bg:orange}]'                                          => array($this->ns . '\BarInterface'),
            '[<<BarInterface>>]^-.-[BarWithInterface|-foo;+bar]'                     => array($this->ns . '\BarWithInterface'),
            '[Bazz]^[Foo|-foo;+bar]'                                                 => array($this->ns . '\Foo'),
            '[<<BazzInterface>>]^-.-[<<FooInterface>>{bg:orange}]'                   => array($this->ns . '\FooInterface'),
            '[Bazz]^[<<BazzInterface>>]^-.-[FooBazzWithInterface|-foo;+bar]'         => array($this->ns . '\FooBazzWithInterface'),
        );

        $config = array(
            'withMethods'    => false,
            'withProperties' => true,
            'url'            => '',
            'debug'          => true,
            'autoload_path'  => __DIR__ . '/../Fixtures'
        );

        foreach ($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, $classes, $config, array($this->getMock('\Buzz\Browser'))));
        }

        return $return;
    }

    public function ClassesAndMethodsProvider()
    {
        $return = array();

        $map = array(
            '[Bar|-foo();+bar()]'                                                => array($this->ns . '\Bar'),
            '[<<BazzInterface>>]^-.-[<<FooInterface>>{bg:orange}]'               => array($this->ns . '\FooInterface'),
            '[<<BarInterface>>]^-.-[BarWithInterface|-foo();+bar()]'             => array($this->ns . '\BarWithInterface'),
            '[Bazz]^[Foo|-foo();+bar()]'                                         => array($this->ns . '\Foo'),
            '[Bazz]^[<<BazzInterface>>]^-.-[FooBazzWithInterface|-foo();+bar()]' => array($this->ns . '\FooBazzWithInterface'),
        );

        $config = array(
            'withMethods'    => true,
            'withProperties' => false,
            'url'            => '',
            'debug'          => true,
            'autoload_path'  => __DIR__ . '/../Fixtures'
        );

        foreach ($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, $classes, $config, array($this->getMock('\Buzz\Browser'))));
        }

        return $return;
    }

    public function ClassesPropertiesAndMethodsProvider()
    {
        $return = array();

        $map = array(
            '[Bar|-foo;+bar|-foo();+bar()]'                                                => array($this->ns . '\Bar'),
            '[<<BazzInterface>>]^-.-[<<FooInterface>>{bg:orange}]'                         => array($this->ns . '\FooInterface'),
            '[<<BarInterface>>]^-.-[BarWithInterface|-foo;+bar|-foo();+bar()]'             => array($this->ns . '\BarWithInterface'),
            '[Bazz]^[Foo|-foo;+bar|-foo();+bar()]'                                         => array($this->ns . '\Foo'),
            '[Bazz]^[<<BazzInterface>>]^-.-[FooBazzWithInterface|-foo;+bar|-foo();+bar()]' => array($this->ns . '\FooBazzWithInterface'),
        );

        $config = array(
            'withMethods'    => true,
            'withProperties' => true,
            'url'            => '',
            'debug'          => true,
            'autoload_path'  => __DIR__ . '/../Fixtures'
        );

        foreach ($map as $expect => $classes) {
            $return[] = array($expect, $this->getBuilder($this->builderClass, $classes, $config, array($this->getMock('\Buzz\Browser'))));
        }

        return $return;
    }

    public function testYumlApi()
    {
        //$this->markTestSkipped('not testing the web api');
        $classes = array($this->ns . '\FooBazzWithInterface');
        $config = array(
            'withMethods'    => true,
            'withProperties' => true,
            'url'            => 'http://yuml.me/diagram/plain;dir:TB/class/',
            'debug'          => false,
            'autoload_path'  => __DIR__ . '/../Fixtures'
        );

        $browser = new Browser();
        $browser->getClient()->setTimeout(5);
        $builder = $this->getBuilder($this->builderClass, $classes, $config, array($browser));
        $result = $builder->build();

        $this->assertInternalType('array', $result);
        $this->assertGreaterThan(0, count($result));

        foreach ($result as $message) {
            $url = explode(' ', $message);
            $response = $browser->get($url[1]);

            switch ($url[0]) {
                case '<info>PNG</info>' :
                    $contentType = 'image/png';
                    break;
                case '<info>PDF</info>' :
                    $contentType = 'application/pdf';
                    break;
                case '<info>URL</info>' :
                    $contentType = 'text/html; charset=utf-8';
                    break;
            }
            $this->assertEquals($contentType, $response->getHeader('Content-Type'));
        }
    }
}
