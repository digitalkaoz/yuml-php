<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Builder;

use Symfony\Component\Finder\Finder;

/**
 * the common builder interface
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
interface BuilderInterface
{

    /**
     * configures the builder
     * 
     * @param array $config
     * @return BuilderInterface
     */
    function configure($config);

    /**
     * sets the path to crawl for classes
     * 
     * @param string $path
     * @return BuilderInterface
     */
    function setPath($path);

    /**
     * sets the finder
     * 
     * @param Finder $finder 
     * @return BuilderInterface
     */
    function setFinder(Finder $finder);

    /**
     * executes the build
     * 
     * @return mixed
     */
    function build();
}