<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Tests\Request;

use YumlPhp\Request\Http\FileRequest;

/**
 * FileRequestTest
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

        $this->assertEquals(array(
            '(start)->|a|',
            '|a|->(Make Coffee)->|b|',
            '|a|->(Make Breakfast)->|b|',
            '|b|-><c>[want more coffee]->(Make Coffee)',
            '<c>[satisfied]->(end)'
        ), $request->build());
    }
}
