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
use YumlPhp\Request\RequestInterface;

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
        $ns = str_replace('\\', '/', $this->ns) . '/';
        $this->assertEquals($expected, str_replace(array($ns, '<info>', '</info>', '<note>', '</note>', '<highlight>', '</highlight>', "\t", "\n"), null, $result));
    }

    /**
     *
     * @return BuilderInterface
     */
    protected function getBuilder($builderClass, $classes, $config, $constructArgs)
    {
        $mock = $this->getMockBuilder($builderClass)
            ->setMethods(array('getInspector'))
            ->setMockClassName('Mock' . str_replace('YumlPhp\\Tests\\Fixtures\\', '_', join('_', $classes)) . '_' . rand(0, 999999))
            ->setConstructorArgs($constructArgs)
            ->getMock();

        if (strpos($builderClass, 'ClassesBuilder')) {
            $inspector = $this->getMockBuilder('YumlPhp\Request\ClassesRequest')
                ->setMethods(array('findClasses'))
            //->setConstructorArgs(array(sys_get_temp_dir()))
                ->getMock();
            $inspector->expects($this->any())->method('findClasses')->will($this->returnValue($classes));
        } else {
            $inspector = $this->getMockBuilder('YumlPhp\Request\FileRequest')
//                ->setMethods(array('getClasses'))
                ->setConstructorArgs(array(tempnam(sys_get_temp_dir(), 'yuml-php')))
                ->getMock();
            //$inspector->expects($this->any())->method('getContent')->will($this->returnValue($this->buildClasses($classes)));
        }

        $inspector->configure($config);

        //$inspector->expects($this->any())->method('getInterfaces')->will($this->returnValue($this->buildClasses($classes)));

        $mock->expects($this->any())->method('getInspector')->will($this->returnValue($inspector));

        return $mock->configure($config);
    }
}
