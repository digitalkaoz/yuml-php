<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Builder\Http;

use YumlPhp\Request\FileRequest;
use YumlPhp\Builder\HttpBuilder;

/**
 * the Yuml Builder generates a ClassDiagram via the http://yuml.me API
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class ActivityBuilder extends HttpBuilder
{
    protected $inspectorClass = 'YumlPhp\Request\FileRequest';
    
    /**
     * builds a request array for the yuml API
     * 
     * @return ActivityBuilder
     */
    protected function doBuild()
    {
        $this->request = explode(',',  str_replace("\n",'',file_get_contents($this->path)));
        
        return $this;        
    }
}
