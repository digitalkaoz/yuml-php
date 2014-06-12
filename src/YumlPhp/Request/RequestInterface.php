<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Request;

use YumlPhp\Builder\BuilderInterface;

/**
 * The console application that handles the commands
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
interface RequestInterface
{
    /**
     * set the path the process, either a file or folder
     *
     * @param  string           $path
     * @return BuilderInterface
     */
    public function setPath($path);

    /**
     * configures the request
     *
     * @param  array            $config
     * @return BuilderInterface
     */
    public function configure(array $config);

    /**
     * processes the request
     *
     * @return array
     */
    public function build();
}
