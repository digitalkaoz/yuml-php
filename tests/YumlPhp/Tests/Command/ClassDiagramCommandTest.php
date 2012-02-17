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
use YumlPhp\Command\ClassDiagramCommand;

/**
 * Description of ClassDiagramCommandTest
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 * 
 * @covers YumlPhp\Command\ClassDiagramCommand
 */
class ClassDiagramCommandTest extends \PHPUnit_Framework_TestCase
{

    public function testRun()
    {
        $command = new ClassDiagramCommand();
        $tester = new CommandTester($command);

        $code = $tester->execute(array('folder' => __DIR__ . '/../Fixtures', '--debug' => null, '--methods' => null, '--properties' => null));

        $this->assertEquals(0, $code);
        $this->assertGreaterThan(0, strlen($tester->getDisplay()));
    }

    /**
     * @expectedException \RuntimeException 
     */
    public function testRunWithErrors()
    {
        //$this->markTestSkipped();
        $command = new ClassDiagramCommand();
        $tester = new CommandTester($command);
        $folder = sys_get_temp_dir() . '/' . time();
        @mkdir($folder);

        $code = $tester->execute(array('folder' => $folder));
        @unlink($folder);

        $this->assertNull($code);
        $this->assertGreaterThan(0, strlen($tester->getDisplay()));
    }

}
