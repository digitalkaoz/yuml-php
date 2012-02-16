YumlPhp [![Build status...](https://secure.travis-ci.org/digitalkaoz/yuml-php.png)](http://travis-ci.org/digitalkaoz/yuml-php)
=======

a `php adapater` for [http://yuml.me](http://yuml.me)

Installation
------------

## with [`composer`](https://github.com/composer/composer.git)

``` json
    "require"{
        "digitalkaoz/yuml-php":  ">=0.3"
    }
```

Usage
-----

The `classes` command generates a class diagram from all classes in the given folder

    Usage:
        classes [--console] [--debug] [--properties] [--methods] folder

    Arguments:
        folder      the folder to scan for classes

    Options:
        --console     log to console
        --debug       debug
        --properties  build with properties
        --methods     build with methods


```sh
    yuml-php classes src/
```


Tests
-----

    php/bin/vendors.php
    phpunit

TODO
----

* ActivityDiagram-Generator
* UseCaseDiagram-Generator
* more Features from [http://yuml.me](http://yuml.me/diagram/scruffy/class/samples) API