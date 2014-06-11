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

/**
 * the common ConsoleBuilder
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class ConsoleBuilder extends Builder
{
    /**
     * @inheritDoc
     */
    public function request(array $request)
    {
        return join(",\n", $request);
    }
}
