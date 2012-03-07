<?php

namespace YumlPhp\Tests\Fixtures;

use Symfony\Component\Console\Input\StringInput;

/**
 *
 * @author caziel
 */
class BarWithExternal extends StringInput
{
    private $foo;
    public $bar;

    private function foo()
    {
    }

    public function bar()
    {
    }
}
