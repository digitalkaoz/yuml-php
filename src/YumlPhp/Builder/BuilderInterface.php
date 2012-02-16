<?php

namespace YumlPhp\Builder;

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
     * builds the graphs
     * 
     * @return mixed
     */
    function build();
}
