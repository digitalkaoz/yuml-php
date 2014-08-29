YumlPhp
=======

a `php adapater` for [http://yuml.me](http://yuml.me)

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/digitalkaoz/yuml-php/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/digitalkaoz/yuml-php/?branch=master)
[![SensioLabsInsight](https://insight.sensiolabs.com/projects/9d3914c2-0636-4d7c-a560-dfea413baa93/mini.png)](https://insight.sensiolabs.com/projects/09d510ab-7d2e-4ea2-8a94-2a37b9121603)
[![Build status...](https://secure.travis-ci.org/digitalkaoz/yuml-php.png)](http://travis-ci.org/digitalkaoz/yuml-php)
[![Code Coverage](https://scrutinizer-ci.com/g/digitalkaoz/yuml-php/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/digitalkaoz/yuml-php/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/digitalkaoz/yuml-php/version.svg)](https://packagist.org/packages/digitalkaoz/yuml-php)
[![Total Downloads](https://poser.pugx.org/digitalkaoz/yuml-php/downloads.svg)](https://packagist.org/packages/digitalkaoz/yuml-php)
[![License](https://poser.pugx.org/digitalkaoz/yuml-php/license.svg)](https://packagist.org/packages/digitalkaoz/yuml-php)
[![HHVM Status](http://hhvm.h4cc.de/badge/digitalkaoz/yuml-php.png)](http://hhvm.h4cc.de/package/digitalkaoz/yuml-php)

Installation
------------

## Installation with [`composer`](https://github.com/composer/composer.git)


``` json
"require" : {
    "digitalkaoz/yuml-php":  "@stable"
    "andrewsville/php-token-reflection":    "dev-develop@dev"
},
"repositories" : [
    {
        "type" : "vcs",
        "url" : "https://github.com/digitalkaoz/PHP-Token-Reflection"
    }
],

```

You need to add my custom PHP-Token-Reflection Fork, since it has some unmerged but needed improvements!

## Installation with `PHAR`

    wget http://digitalkaoz.github.io/yuml-php/yuml-php.phar

Usage
-----

### the `classes` command generates a class diagram from all classes in the given folder

    Usage:
        classes [--console] [--debug] [--properties] [--methods] [--filter] folder

    Arguments:
        folder      the folder to scan for classes

    Options:
        --console     log to console
        --debug       debug
        --properties  build with properties
        --methods     build with methods
        --filter      to include/exclude files/folder

```sh
yuml-php classes src/
```

![Class Diagram](http://digitalkaoz.github.io/yuml-php/examples/classes_01.png)

### the `activity` command generates an activity diagram from a given file

    Usage:
        activity [--console] [--debug] file

    Arguments:
        file          the file to read

    Options:
        --console     log to console
        --debug       debug


```sh
yuml-php activity activities.txt
```

![Activity Diagramm](http://digitalkaoz.github.io/yuml-php/examples/activity_01.png)

### the `use-case` command generates a use-case diagram from a given file

    Usage:
        use-case [--console] [--debug] file

    Arguments:
        file          the file to read

    Options:
        --console     log to console
        --debug       debug


```sh
yuml-php use-case use-cases.txt
```

![Use-Case Diagramm](http://digitalkaoz.github.io/yuml-php/examples/usecase-01.png)

Building the PHAR
-----------------

    php vendor/bin/box build

Tests
-----

    php bin/vendors.php
    phpunit


TODO
----

* more Features from [http://yuml.me](http://yuml.me/diagram/scruffy/class/samples) API
