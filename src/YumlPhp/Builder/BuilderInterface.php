<?php

namespace YumlPhp\Builder;

use Symfony\Component\Finder\Finder;

/**
 *
 * @author caziel
 */
interface BuilderInterface
{
    /**
     * configures the builder
     * 
     * @param array $config
     * @return BuilderInterface
     */
    function configure($config);
        
    /**
     * sets the path to crawl for classes
     * 
     * @param string $path
     * @return BuilderInterface
     */
    function setPath($path);
    
    /**
     * sets the finder
     * 
     * @param Finder $finder 
     * @return BuilderInterface
     */
    function setFinder(Finder $finder);
    
    /**
     * executes the build
     * 
     * @return mixed
     */
    function build();
    
}