<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Tests\Application;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Tester\ApplicationTester;
use YumlPhp\Console\Application;

/**
 * ApplicationTest
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 * 
 * @covers YumlPhp\Console\Application
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
