#!/usr/bin/env php
<?php

$rootDir = getcwd();
// php on windows can't use the shebang line from system()
$interpreter = PHP_OS == 'WINNT' ? 'php.exe' : '';
$composer = $rootDir.'/composer.phar';

//get composer
if (file_exists($composer)) {
    system(sprintf('php %s', $composer.' self-update'), $composerable);
} else {
    system(sprintf('curl %s | php -d=phar.readonly=Off -d=phar.require_hash=Off', 'http://getcomposer.org/installer'), $composerable);
}

//update if composer can be run
if (isset($composerable) && $composerable == 0) {
    system(sprintf('php %s', $composer.' update'));
}
