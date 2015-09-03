<?php

namespace YumlPhp\Request;

/**
 * A Request from File.
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
abstract class FileRequest implements RequestInterface
{
    private $file;

    /**
     * {@inheritdoc}
     */
    public function setPath($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * {@inheritdoc}
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
