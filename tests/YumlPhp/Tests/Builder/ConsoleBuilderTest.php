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
use YumlPhp\Builder\ConsoleBuilder;
use YumlPhp\Request\Console\ClassesRequest;
use YumlPhp\Request\Console\FileRequest;

/**
 * @covers YumlPhp\Builder\ConsoleBuilder<extended>
 */
class ConsoleBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider fileProvider
     */
    public function testType(BuilderInterface $builder, $data, $fixture, $type)
    {
        $this->assertEquals($type, $builder->getType());
    }

    /**
     * @dataProvider fileProvider
     */
    public function testBuild(BuilderInterface $builder, $data, $fixture, $type, $config)
    {
        $result = $builder
            ->configure($config)
            ->setPath($data)
            ->build();

        $expected = file_get_contents($fixture);
        $current = str_replace(array('<question>','<comment>', '<info>', '</comment>', '</question>', '</info>'), '', str_replace("\n\n", "\n", $result));

        $this->assertEquals($expected, $current);
    }

    public function fileProvider()
    {
        return array(
            array(new ConsoleBuilder(new FileRequest(), 'activity'), __DIR__ . '/../Fixtures/activity.txt', __DIR__ . '/../Fixtures/activity.txt', 'activity', array()),
            array(new ConsoleBuilder(new FileRequest(), 'usecase'), __DIR__ . '/../Fixtures/use-case.txt', __DIR__ . '/../Fixtures/use-case.txt', 'usecase', array()),
            array(new ConsoleBuilder(new ClassesRequest(), 'classes'), __DIR__ . '/../Fixtures', __DIR__ . '/../Fixtures/classes.txt', 'classes', array()),
            array(new ConsoleBuilder(new ClassesRequest(), 'classes'), __DIR__ . '/../Fixtures', __DIR__ . '/../Fixtures/classes-props.txt', 'classes', array('withProperties' => true)),
            array(new ConsoleBuilder(new ClassesRequest(), 'classes'), __DIR__ . '/../Fixtures', __DIR__ . '/../Fixtures/classes-methods.txt', 'classes', array('withMethods' => true)),
            array(new ConsoleBuilder(new ClassesRequest(), 'classes'), __DIR__ . '/../Fixtures', __DIR__ . '/../Fixtures/classes-full.txt', 'classes', array('withMethods' => true, 'withProperties' => true, 'debug')),
        );
    }
}
