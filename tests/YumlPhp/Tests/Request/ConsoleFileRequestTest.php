<?php

namespace YumlPhp\Tests\Request;

use YumlPhp\Request\Console\FileRequest;

/**
 * FileRequestTest
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 *
 * @covers YumlPhp\Request\Console\FileRequest<extended>
 */
class ConsoleFileRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testGetContent()
    {
        $file = __DIR__ . '/../Fixtures/activity.txt';

        $request = new FileRequest();
        $request->setPath($file);

        $expected = array(
            "(start)->|a|",
            "\n\n|a|->(Make Coffee)->|b|",
            "\n\n|a|->(Make Breakfast)->|b|",
            "\n\n|b|-><c>[want more coffee]->(Make Coffee)",
            "\n\n<c>[satisfied]->(end)"
        );

        $current = $request->build();

        sort($expected);
        sort($current);
        $this->assertEquals($expected, $current);
    }
}
