<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Builder\Console;

use YumlPhp\Request\FileRequest;

use YumlPhp\Builder\ConsoleBuilder;
/**
 * the Activity Builder generates an ActivityDiagram for Console Output
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class ActivityBuilder extends ConsoleBuilder
{
    protected $inspectorClass = 'YumlPhp\Request\FileRequest';
    
    /**
     * builds a request array
     * 
     * @return ActivityBuilder 
     */
    protected function doBuild()
    {
        $this->request = explode(',',file_get_contents($this->path));

        return $this;
    }

    /**
     * returns the class diagram as concatenated string
     * 
     * @return string
     */
    public function request()
    {
        return join(",\n", $this->request);
    }    
}
