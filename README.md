YumlPhp [![Build status...](https://secure.travis-ci.org/digitalkaoz/yuml-php.png)](http://travis-ci.org/digitalkaoz/yuml-php)
=======

a `php adapater` for [http://yuml.me](http://yuml.me)

Installation
------------

## Dependencies

* [`Buzz`](https://github.com/kriswallsmith/Buzz)
* [`Symfony Finder`](https://github.com/symfony/Finder)
* [`Symfony Console`](https://github.com/symfony/Console)
* [`Composer`](https://github.com/composer/composer.git) (for tests or self containing library only)

## with [`composer`](https://github.com/composer/composer.git)

``` json
"require"{
    "digitalkaoz/yuml-php":  ">=0.3"
}
```

Usage
-----

# the `classes` command generates a class diagram from all classes in the given folder

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

# the `activity` command generates an activity diagram from a given file

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

# the `use-case` command generates a use-case diagram from a given file

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


Tests
-----

    php/bin/vendors.php
    phpunit

TODO
----

* more Features from [http://yuml.me](http://yuml.me/diagram/scruffy/class/samples) API