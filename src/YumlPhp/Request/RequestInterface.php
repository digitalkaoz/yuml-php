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

/**
 * The console application that handles the commands
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
interface RequestInterface
{
    public function setPath($path);

    public function configure(array $config);

    public function build();
}
