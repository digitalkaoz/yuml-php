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

use YumlPhp\Builder\BuilderInterface;

/**
 * BaseBuilder
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 * 
 * @covers YumlPhp\Builder\Builder<extended>
 */
class BaseBuilder extends \PHPUnit_Framework_TestCase
{

    /**
     * @dataProvider ClassesProvider 
     */
    public function testClasses($expected, BuilderInterface $builder)
    {
        $result = $builder->build();

        $this->assertCorrectLine($expected, $result);
    }

    /**
     * @dataProvider ClassesAndPropertiesProvider 
     */
    public function testClassesAndProperties($expected, BuilderInterface $builder)
    {
        $result = $builder->build();

        $this->assertCorrectLine($expected, $result);
    }

    /**
     * @dataProvider ClassesAndMethodsProvider 
     */
    public function testClassesAndMethods($expected, BuilderInterface $builder)
    {
        $result = $builder->build();

        $this->assertCorrectLine($expected, $result);
    }

    /**
     * @dataProvider ClassesPropertiesAndMethodsProvider 
     */
    public function testClassesPropertiesAndMethods($expected, BuilderInterface $builder)
    {
        $result = $builder->build();

        $this->assertCorrectLine($expected, $result);
    }

    protected function assertCorrectLine($expected, $result)
    {
        $this->assertEquals($expected, str_replace(array('<info>', '</info>', '<note>', '</note>', '<highlight>', '</highlight>', "\t", "\n"), null, $result));
    }

    /**
     *
     * @return BuilderInterface
     */
    protected function getBuilder($builderClass, $methods, $classes, $config, $constructArgs)
    {
        $mock = $this->getMockBuilder($builderClass)
            ->setMethods($methods)
            ->setMockClassName('Mock' . str_replace('YumlPhp\\Tests\\Fixtures\\', '_', join('_', $classes)) . '_' . rand(0, 999999))
            ->setConstructorArgs($constructArgs)
            ->getMock()
        ;

        $mock->expects($this->once())->method('findClasses')->will($this->returnValue($this->buildClasses($classes)));

        return $mock->configure($config)->setFinder($this->getMock('Symfony\Component\Finder\Finder'));
        ;
    }

    private function buildClasses($classes)
    {
        $return = array();
        foreach ($classes as $class) {
            $return[$class] = new \ReflectionClass($class);
        }
        return $return;
    }

}

