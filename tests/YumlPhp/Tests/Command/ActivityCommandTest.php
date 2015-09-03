<?php

namespace YumlPhp\Tests\Command;

use Symfony\Component\Console\Tester\CommandTester;
use YumlPhp\Command\ActivityCommand;

/**
 * Description of ClassDiagramCommandTest.
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
        $builder->expects($this->any())->method('build')->will($this->returnValue(['foo']));

        $command = new ActivityCommand($builder, $builder);
        $tester  = new CommandTester($command);

        $code = $tester->execute([
            'source'    => __DIR__ . '/../Fixtures/activity.txt',
            '--debug'   => null,
            '--console' => null,
        ]);

        $this->assertEquals(0, $code);
        $this->assertGreaterThan(0, strlen($tester->getDisplay()));
    }
}
