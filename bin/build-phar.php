<?php

include __DIR__.DIRECTORY_SEPARATOR.'vendors.php';

$pharFile = __DIR__.'/../yuml-php.phar';
if (file_exists($pharFile)) {
    Phar::unlinkArchive($pharFile);
}

$p = new Phar($pharFile, 0, 'yuml-php.phar');
$p->setSignatureAlgorithm (Phar::SHA1);

$p->startBuffering();
//TODO dont include everything
$p->buildFromIterator(new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__.'/../')),__DIR__.'/../');
$p->stopBuffering();

$p->setStub(<<<EOS
<?php 
Phar::mapPhar(); 

include 'phar://yuml-php.phar/vendor/.composer/autoload.php';

use YumlPhp\\Console\\Application;

// run the command application
\$application = new Application('yuml-php','0.6.0');
\$application->run();
    
__HALT_COMPILER();
EOS
    
); 