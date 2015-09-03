<?php

namespace YumlPhp\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use YumlPhp\Command\UseCaseCommand;

/**
 * Description of ClassDiagramCommandTest.
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 *
 * @covers YumlPhp\Command\UseCaseCommand<extended>
 */
class UseCaseCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testRun()
    {
        $builder = $this->getMock('YumlPhp\Builder\BuilderInterface');
        $builder->expects($this->any())->method('build')->will($this->returnValue(['foo']));

        $command = new UseCaseCommand($builder, $builder);
        $tester  = new CommandTester($command);

        $code = $tester->execute([
            'source'    => __DIR__ . '/../Fixtures/use-case.txt',
            '--debug'   => null,
            '--console' => null,
        ]);

        $this->assertEquals(0, $code);
        $this->assertGreaterThan(0, strlen($tester->getDisplay()));
    }
}
