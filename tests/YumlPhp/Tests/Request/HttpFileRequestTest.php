<?php

namespace YumlPhp\Tests\Request;

use YumlPhp\Request\Http\FileRequest;

/**
 * FileRequestTest.
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 *
 * @covers YumlPhp\Request\Http\FileRequest<extended>
 */
class HttpFileRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testGetContent()
    {
        $file = __DIR__ . '/../Fixtures/activity.txt';

        $request = new FileRequest();
        $request->setPath($file);

        $this->assertEquals([
            '(start)->|a|',
            '|a|->(Make Coffee)->|b|',
            '|a|->(Make Breakfast)->|b|',
            '|b|-><c>[want more coffee]->(Make Coffee)',
            '<c>[satisfied]->(end)',
        ], $request->build());
    }
}
