<?php

namespace YumlPhp\Tests\Application;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Tester\ApplicationTester;

use YumlPhp\Console\Application;

/**
 * Description of ApplicationTest
 *
 * @author caziel
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testCommands()
    {
        $app = new Application();
        $tester = new ApplicationTester($app);
        
        $app->doRun(new ArrayInput(array()), new NullOutput());
        //$tester->run(array());
        
        $this->assertTrue($app->has('classes'));
    }
}
