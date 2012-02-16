YumlPhp [![Build status...](https://secure.travis-ci.org/digitalkaoz/yuml-php.png)](http://travis-ci.org/digitalkaoz/yuml-php)
=======

a `php adapater` for [http://yuml.me](http://yuml.me)

Installation
------------

## with [`composer`](https://github.com/composer/composer.git)

    ``` json
        "require"{
            "digitalkaoz":  ">=0.3"
        }
    ```

Usage
-----

## ClassDiagram-Generator

    bin/yuml-php classes `path/to/classes`
    bin/yuml-php classes --properties `path/to/classes`
    bin/yuml-php classes --classes --properties --methods `path/to/classes`
    bin/yuml-php classes --console `path/to/classes`
    bin/yuml-php classes --debug `path/to/classes`


Tests
-----

## with [`composer`](https://github.com/composer/composer.git)

    php/bin/vendors.php
    phpunit


TODO
----

*) ActivityDiagram-Generator
*) UseCaseDiagram-Generator