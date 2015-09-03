<?php

namespace YumlPhp\Builder;

/**
 * the common ConsoleBuilder.
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class ConsoleBuilder extends Builder
{
    /**
     * {@inheritdoc}
     */
    public function request(array $request)
    {
        return implode(",\n", $request);
    }
}
