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

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
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
        $command = new ActivityCommand();
        $tester = new CommandTester($command);

        $code = $tester->execute(array('source'  => __DIR__ . '/../Fixtures/activity.txt',
                                       '--debug' => null));

        $this->assertEquals(0, $code);
        $this->assertGreaterThan(0, strlen($tester->getDisplay()));
        $code = $tester->execute(array('source'   => __DIR__ . '/../Fixtures/activity.txt',
                                       '--debug'  => null,
                                       '--console'=> null));

        $this->assertEquals(0, $code);
        $this->assertGreaterThan(0, strlen($tester->getDisplay()));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testRunWithErrors()
    {
        $command = $this->getMockBuilder('YumlPhp\Command\ActivityCommand')
            ->setMethods(array('getBuilderConfig'))
            ->getMock();

        $command->expects($this->once())->method('getBuilderConfig')->will($this->returnValue(array(
            'url'   => 'http://lolcathost/',
            'debug' => false,
            'style' => 'plain;dir:LR;scale:80;'
        )));

        $tester = new CommandTester($command);
        $tester->execute(array('source' => sys_get_temp_dir()));
    }

}
