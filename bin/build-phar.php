#!/usr/bin/env php
<?php

if (!@include __DIR__.'/../vendor/autoload.php') {
    die('You must set up the project dependencies, run the following commands:'.PHP_EOL.
        'curl -s http://getcomposer.org/installer | php'.PHP_EOL.
        'php composer.phar install'.PHP_EOL);
}

use Symfony\Component\Finder\Finder;

$c = new Compiler();
$c->compile(__DIR__.'/../yuml-php.phar');
/**
 * The Compiler class compiles composer into a phar
 *
 * @author Fabien Potencier <fabien@symfony.com>
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class Compiler
{
    /**
     * Compiles composer into a single phar file
     *
     * @throws \RuntimeException
     * @param string $pharFile The full path to the file to create
     */
    public function compile($pharFile = 'yuml-php.phar')
    {
        if (file_exists($pharFile)) {
            unlink($pharFile);
        }

        $output = @system('git log --pretty="%h" -n1 HEAD', $result);

        if ($result != 0) {
            throw new \RuntimeException('Can\'t run git log. You must ensure to run compile from yuml-php git repository clone and that git binary is available.');
        }
        $this->version = trim($output);

        $output = @system('git describe --tags HEAD', $result);

        if ($result == 0) {
            $this->version = trim($output);
        }

        $phar = new \Phar($pharFile);
        //$phar->setSignatureAlgorithm(\Phar::SHA1);

        $phar->startBuffering();

        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->name('*.php')
            ->in(__DIR__.'/../src')
        ;

        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }
        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->name('*.php')
            ->in(__DIR__.'/../vendor')
        ;

        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }

        $this->addYumlBin($phar);

        // Stubs
        $phar->setStub($this->getStub());

        $phar->stopBuffering();

        // disabled for interoperability with systems without gzip ext
        // $phar->compressFiles(\Phar::GZ);

        $this->addFile($phar, new \SplFileInfo(__DIR__.'/../LICENSE'), false);
    }

    private function addFile($phar, $file, $strip = true)
    {
        $path = str_replace(dirname(dirname(__DIR__.'/..')).DIRECTORY_SEPARATOR, '', $file->getRealPath());

        if ($strip) {
            $content = php_strip_whitespace($file);
        } elseif ('LICENSE' === basename($file)) {
            $content = "\n".file_get_contents($file)."\n";
        } else {
            $content = file_get_contents($file);
        }

        $content = str_replace('@package_version@', $this->version, $content);

        $phar->addFromString($path, $content);
    }

    private function addYumlBin($phar)
    {
        $content = file_get_contents(__DIR__.'/yuml-php');
        $content = preg_replace('{^#!/usr/bin/env php\s*}', '', $content);
        $phar->addFromString('bin/yuml-php', $content);
    }

    private function getStub()
    {
        return <<<EOS
<?php
Phar::mapPhar('yuml-php.phar');

require 'phar://yuml-php.phar/bin/yuml-php';

__HALT_COMPILER();
EOS;
    }
}
