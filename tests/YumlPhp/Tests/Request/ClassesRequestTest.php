<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Tests\Application;

use YumlPhp\Request\ClassesRequest;

/**
 * FileRequestTest
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 *
 * @covers YumlPhp\Request\ClassesRequest<extended>
 */
class ClassesRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testGetProperties()
    {
        $request = new ClassesRequest();
        $request->setPath(__DIR__ . '/../Fixtures');

        $properties = $request->buildProperties(new \ReflectionClass('YumlPhp\Tests\Fixtures\Bar'));
        $this->assertInternalType('array', $properties);
        $this->assertCount(0, $properties);

        $request->configure(array('withProperties'=> true));
        $properties = $request->buildProperties(new \ReflectionClass('YumlPhp\Tests\Fixtures\Bar'));
        $this->assertInternalType('array', $properties);
        $this->assertCount(2, $properties);
    }

    public function testGetMethods()
    {
        $request = new ClassesRequest();
        $request->setPath(__DIR__ . '/../Fixtures');

        $methods = $request->buildMethods(new \ReflectionClass('YumlPhp\Tests\Fixtures\Bar'));
        $this->assertInternalType('array', $methods);
        $this->assertCount(0, $methods);

        $request->configure(array('withMethods'=> true));
        $methods = $request->buildMethods(new \ReflectionClass('YumlPhp\Tests\Fixtures\Bar'));
        $this->assertInternalType('array', $methods);
        $this->assertCount(2, $methods);
    }

    public function testGetInterfaces()
    {
        $request = new ClassesRequest();
        $request->setPath(__DIR__ . '/../Fixtures');

        $methods = $request->buildInterfaces(new \ReflectionClass('YumlPhp\Tests\Fixtures\BarWithInterface'));
        $this->assertInternalType('array', $methods);
        $this->assertCount(1, $methods);
    }

    public function testGetClasses()
    {
        $request = new ClassesRequest();
        $request->setPath(__DIR__ . '/../Fixtures');

        $classes = $request->getClasses();
        $this->assertInternalType('array', $classes);
        $this->assertGreaterThan(0, count($classes));
    }

    public function testBuildName()
    {
        $request = new ClassesRequest();
        $request->setPath(__DIR__ . '/../Fixtures');

        $name = $request->buildName(new \ReflectionClass('YumlPhp\Tests\Fixtures\Bar'));
        $this->assertEquals('YumlPhp/Tests/Fixtures/Bar', $name);

        $name = $request->buildName(new \ReflectionClass('YumlPhp\Tests\Fixtures\BarInterface'));
        $this->assertEquals('<<YumlPhp/Tests/Fixtures/BarInterface>>', $name);

        $name = $request->buildName(new \ReflectionClass('YumlPhp\Tests\Fixtures\BarInterface'), '__', '__');
        $this->assertEquals('__YumlPhp/Tests/Fixtures/BarInterface__', $name);
    }

    public function testBuildParent()
    {
        $request = new ClassesRequest();
        $request->setPath(__DIR__ . '/../Fixtures');

        $name = $request->buildParent(new \ReflectionClass('YumlPhp\Tests\Fixtures\Foo'));
        $this->assertEquals('YumlPhp/Tests/Fixtures/Bazz', $name);

        $name = $request->buildParent(new \ReflectionClass('YumlPhp\Tests\Fixtures\FooInterface'));
        $this->assertNull($name);
        //$this->assertEquals('<<YumlPhp/Tests/Fixtures/BazzInterface>>',$name);

        //$name = $request->buildName(new \ReflectionClass('YumlPhp\Tests\Fixtures\BarInterface'),'__','__');
        //$this->assertEquals('__YumlPhp/Tests/Fixtures/BarInterface__',$name);
    }
}
