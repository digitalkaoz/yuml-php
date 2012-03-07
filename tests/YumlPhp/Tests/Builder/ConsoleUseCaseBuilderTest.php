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

use YumlPhp\Builder\Console\UseCaseBuilder;

/**
 * ConsoleClassDiagramBuilderTest
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 *
 * @covers YumlPhp\Builder\Console\UseCaseBuilder<extended>
 */
class ConsoleUseCaseBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testType()
    {
        $builder = new UseCaseBuilder();

        $this->assertEquals('usecase',$builder->getType());
    }

    public function testConsole()
    {
        $config = array();

        $builder = new UseCaseBuilder();
        $builder->configure($config)->setPath(__DIR__ . '/../Fixtures/use-case.txt');

        $result = $builder->build();

        $this->assertEquals(str_replace("\n", '', file_get_contents(__DIR__ . '/../Fixtures/use-case.txt')), str_replace("\n", '', $result));
    }
}