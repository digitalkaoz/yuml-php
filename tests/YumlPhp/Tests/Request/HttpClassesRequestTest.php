<?php

namespace YumlPhp\Tests\Request;

use YumlPhp\Request\Http\ClassesRequest;

/**
 * FileRequestTest.
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 *
 * @covers YumlPhp\Request\Http\ClassesRequest<extended>
 */
class HttpClassesRequestTest extends \PHPUnit_Framework_TestCase
{
    public function testBuild()
    {
        $request = new ClassesRequest();
        $request->setPath(__DIR__ . '/../Fixtures');

        $this->assertEquals([
            '[<<YumlPhp/Tests/Fixtures/BarInterface>>{bg:orange}]',
            '[<<YumlPhp/Tests/Fixtures/BarInterface>>{bg:orange}]^-.-[<<YumlPhp/Tests/Fixtures/BarWithInterface>>{bg:orange}]',
            '[<<YumlPhp/Tests/Fixtures/BazzInterface>>{bg:orange}]',
            '[<<YumlPhp/Tests/Fixtures/BazzInterface>>{bg:orange}]^-.-[;<<YumlPhp/Tests/Fixtures/FooInterface>>{bg:orange}]^-.-[<<YumlPhp/Tests/Fixtures/FooWithInterface>>{bg:orange}]',
            '[<<YumlPhp/Tests/Fixtures/BazzInterface>>{bg:orange}]^-.-[<<YumlPhp/Tests/Fixtures/FooInterface>>{bg:orange}]',
            '[Symfony/Component/Console/Input/StringInput]^[YumlPhp/Tests/Fixtures/BarWithExternal]',
            '[YumlPhp/Tests/Fixtures/Bar]',
            '[YumlPhp/Tests/Fixtures/Bazz]',
            '[YumlPhp/Tests/Fixtures/Bazz]^[<<YumlPhp/Tests/Fixtures/BazzInterface>>{bg:orange}]^-.-[<<YumlPhp/Tests/Fixtures/FooBazzWithInterface>>{bg:orange}]',
            '[YumlPhp/Tests/Fixtures/Bazz]^[YumlPhp/Tests/Fixtures/Foo]',
        ], array_values($request->build()));
    }
}
