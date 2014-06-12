<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use YumlPhp\Command\ActivityCommand;

/**
 * Description of ClassDiagramCommandTest
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 *
 * @covers YumlPhp\Command\ActivityCommand<extended>
 */
class ActivityCommandTest extends \PHPUnit_Framework_TestCase
{

    public function testRun()
    {
        $builder = $this->getMock('YumlPhp\Builder\BuilderInterface');
        $builder->expects($this->any())->method('build')->will($this->returnValue(array('foo')));

        $command = new ActivityCommand($builder, $builder);
        $tester = new CommandTester($command);

        $code = $tester->execute(array(
            'source'    => __DIR__ . '/../Fixtures/activity.txt',
            '--debug'   => null,
            '--console' => null
        ));

        $this->assertEquals(0, $code);
        $this->assertGreaterThan(0, strlen($tester->getDisplay()));
    }

}
