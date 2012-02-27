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

use YumlPhp\Command\ClassesCommand;

/**
 * Description of ClassDiagramCommandTest
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 * 
 * @covers YumlPhp\Command\ClassesCommand<extended>
 */
class ClassesCommandTest extends \PHPUnit_Framework_TestCase
{

    public function testRun()
    {
        $command = new ClassesCommand();
        $tester = new CommandTester($command);

        $code = $tester->execute(array('source' => __DIR__ . '/../Fixtures', '--debug' => null, '--methods' => null, '--properties' => null));

        $this->assertEquals(0, $code);
        $this->assertGreaterThan(0, strlen($tester->getDisplay()));
        
        $code = $tester->execute(array('source' => __DIR__ . '/../Fixtures', '--debug' => null, '--methods' => null, '--properties' => null, '--console'=>null));

        $this->assertEquals(0, $code);
        $this->assertGreaterThan(0, strlen($tester->getDisplay()));
    }

    /**
     * @expectedException \RuntimeException 
     */
    public function testRunWithErrors()
    {
        $this->markTestIncomplete();
        $command = new ClassesCommand();
        $tester = new CommandTester($command);
        $code = $tester->execute(array('source' => sys_get_temp_dir()));
    }

}
