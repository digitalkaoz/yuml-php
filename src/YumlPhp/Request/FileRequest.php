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

use Symfony\Component\Finder\Finder;
use YumlPhp\Request\RequestInterface;

/**
 * A Request from File
 * 
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class FileRequest implements RequestInterface
{
    private $file, $content;
    
    public function getContent()
    {
        return $this->content;
    }
    
    public function getFile()
    {
        return $this->file;
    }
    
    /**
     * reflects given classes
     * 
     * @param array $config
     * @param string path
     */
    public function __construct($file)
    {
        $this->file = $file;
        $this->content = file_get_contents($file);
    }
}
