<?php

namespace YumlPhp\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use YumlPhp\Command\ClassesCommand;

/**
 * Description of ClassDiagramCommandTest.
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 *
 * @covers YumlPhp\Command\ClassesCommand<extended>
 */
class ClassesCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $builder = $this->getMock('YumlPhp\Builder\BuilderInterface');
        $builder->expects($this->any())->method('build')->will($this->returnValue(['foo']));

        $command = new ClassesCommand($builder, $builder);
        $tester  = new CommandTester($command);

        $code = $tester->execute([
            'source'       => __DIR__ . '/../Fixtures',
            '--debug'      => null,
            '--methods'    => null,
            '--properties' => null,
            '--console'    => null,
        ]);

        $this->assertEquals(0, $code);
        $this->assertGreaterThan(0, strlen($tester->getDisplay()));
    }
}
