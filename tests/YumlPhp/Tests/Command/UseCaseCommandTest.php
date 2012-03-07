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

use YumlPhp\Command\UseCaseCommand;

/**
 * Description of ClassDiagramCommandTest
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 *
 * @covers YumlPhp\Command\UseCaseCommand<extended>
 */
class UseCaseCommandTest extends \PHPUnit_Framework_TestCase
{

    public function testRun()
    {
        $command = new UseCaseCommand();
        $tester = new CommandTester($command);

        $code = $tester->execute(array('source'  => __DIR__ . '/../Fixtures/use-case.txt',
                                       '--debug' => null));

        $this->assertEquals(0, $code);
        $this->assertGreaterThan(0, strlen($tester->getDisplay()));

        $code = $tester->execute(array('source'   => __DIR__ . '/../Fixtures/use-case.txt',
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
        $command = $this->getMockBuilder('YumlPhp\Command\UseCaseCommand')
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