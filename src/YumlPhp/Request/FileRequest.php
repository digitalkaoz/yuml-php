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
 * A Request from File
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
abstract class FileRequest implements RequestInterface
{
    private $file;

    /**
     * @inheritDoc
     */
    public function setPath($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function configure(array $config)
    {
        return $this;
    }

    protected function getData()
    {
        return file_get_contents($this->file);
    }
}
