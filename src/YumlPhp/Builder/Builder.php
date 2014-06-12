<?php

/*
 * This file is part of yuml-php
 *
 * (c) Robert Schönthal <seroscho@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace YumlPhp\Builder;

use YumlPhp\Analyzer\File;
use YumlPhp\Request\RequestInterface;

/**
 * Description of Builder
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
abstract class Builder implements BuilderInterface
{
    /**
     * @var RequestInterface
     */
    protected $inspector;

    protected $path, $request = array(), $configuration = array();
    /**
     * @var
     */
    private $type;

    /**
     * @param RequestInterface $inspector
     * @param                  $type
     */
    public function __construct(RequestInterface $inspector, $type)

    {
        $this->inspector = $inspector;
        $this->type = $type;
    }

    /**
     * creates and returns an inspector
     *
     * @return RequestInterface
     */
    public function getInspector()
    {
        $this->inspector->configure($this->configuration);
        $this->inspector->setPath($this->path);

        return $this->inspector;
    }

    /**
     * @inheritDoc
     */
    public function configure($config)
    {
        $this->configuration = $config;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function build($pattern = '*.php')
    {
        $request = $this->getInspector()->build();

        return $this->request($request);
    }

    /**
     * returns the builder type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
