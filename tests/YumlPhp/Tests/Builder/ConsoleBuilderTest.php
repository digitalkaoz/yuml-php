<?php

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
        $current  = str_replace(['<question>', '<comment>', '<info>', '</comment>', '</question>', '</info>'], '', str_replace("\n\n", "\n", $result));

        $this->assertEquals($expected, $current);
    }

    public function fileProvider()
    {
        return [
            [new ConsoleBuilder(new FileRequest(), 'activity'), __DIR__ . '/../Fixtures/activity.txt', __DIR__ . '/../Fixtures/activity.txt', 'activity', []],
            [new ConsoleBuilder(new FileRequest(), 'usecase'), __DIR__ . '/../Fixtures/use-case.txt', __DIR__ . '/../Fixtures/use-case.txt', 'usecase', []],
            [new ConsoleBuilder(new ClassesRequest(), 'classes'), __DIR__ . '/../Fixtures', __DIR__ . '/../Fixtures/classes.txt', 'classes', []],
            [new ConsoleBuilder(new ClassesRequest(), 'classes'), __DIR__ . '/../Fixtures', __DIR__ . '/../Fixtures/classes-props.txt', 'classes', ['withProperties' => true]],
            [new ConsoleBuilder(new ClassesRequest(), 'classes'), __DIR__ . '/../Fixtures', __DIR__ . '/../Fixtures/classes-methods.txt', 'classes', ['withMethods' => true]],
            [new ConsoleBuilder(new ClassesRequest(), 'classes'), __DIR__ . '/../Fixtures', __DIR__ . '/../Fixtures/classes-full.txt', 'classes', ['withMethods' => true, 'withProperties' => true, 'debug']],
        ];
    }
}
