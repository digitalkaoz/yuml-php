<?php

namespace YumlPhp\Request\Http;

use YumlPhp\Request\FileRequest as BaseRequest;

/**
 * FileRequest.
 *
 * @author Robert Schönthal <seroscho@gmail.com>
 */
class FileRequest extends BaseRequest
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        return explode(',', str_replace("\n", '', $this->getData()));
    }
}
