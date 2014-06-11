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
     * executes the build
     *
     * @return mixed
     */
    function build();

    function request(array $request);

    function getType();

}