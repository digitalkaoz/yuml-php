<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Tests\Builder;

use Buzz\Browser;

use YumlPhp\Builder\Http\UseCaseBuilder;
/**
 * ConsoleClassDiagramBuilderTest
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 * 
 * @covers YumlPhp\Builder\Http\UseCaseBuilder<extended>
 */
class YumlUseCaseBuilderTest extends \PHPUnit_Framework_TestCase
{
    public function testYuml()
    {
        $config = array(
          'url' => 'http://yuml.me/diagram/plain;dir:TB/usecase/',
          'debug' => false
        );
        
        $browser = new Browser();
        $browser->getClient()->setTimeout(5);
        $builder = new UseCaseBuilder($browser);
        $builder->configure($config)->setPath(__DIR__.'/../Fixtures/use-case.txt');

        $result = $builder->build();

        $this->assertInternalType('array', $result);
        $this->assertGreaterThan(0, count($result));

        foreach ($result as $message) {
            $url = explode(' ', $message);
            $response = $browser->get($url[1]);

            switch ($url[0]) {
                case '<info>PNG</info>' : $contentType = 'image/png';
                    break;
                case '<info>PDF</info>' : $contentType = 'application/pdf';
                    break;
                case '<info>URL</info>' : $contentType = 'text/html; charset=utf-8';
                    break;
            }
            $this->assertEquals($contentType, $response->getHeader('Content-Type'));
        }
    }    
}