<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Tests;

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Console\Tester\ApplicationTester;
use YumlPhp\Application;
use YumlPhp\Container;

/**
 * ApplicationTest
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 *
 * @covers YumlPhp\Application
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{

    public function testCommands()
    {
        $app = new Application(new Container());
        $tester = new ApplicationTester($app);

        $app->doRun(new ArrayInput(array()), new NullOutput());
        //$tester->run(array());

        $this->assertTrue($app->has('classes'));
        $this->assertTrue($app->has('activity'));
        $this->assertTrue($app->has('use-case'));
    }

}
