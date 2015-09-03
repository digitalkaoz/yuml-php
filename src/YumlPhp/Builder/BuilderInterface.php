<?php

namespace YumlPhp\Builder;

/**
 * the common builder interface.
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
interface BuilderInterface
{
    /**
     * configures the builder.
     *
     * @param array $config
     *
     * @return BuilderInterface
     */
    public function configure($config);

    /**
     * sets the path to crawl for classes.
     *
     * @param string $path
     *
     * @return BuilderInterface
     */
    public function setPath($path);

    /**
     * executes the build.
     *
     * @return mixed
     */
    public function build();

    /**
     * perforns the request.
     *
     * @param array $request
     *
     * @return mixed
     */
    public function request(array $request);

    /**
     * @return string
     */
    public function getType();
}
