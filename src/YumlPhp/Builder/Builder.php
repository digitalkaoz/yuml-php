<?php

namespace YumlPhp\Builder;

use YumlPhp\Request\RequestInterface;

/**
 * Description of Builder.
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
abstract class Builder implements BuilderInterface
{
    /**
     * @var RequestInterface
     */
    protected $inspector;

    protected $path, $request = [], $configuration = [];
    /**
     * @var
     */
    private $type;

    /**
     * @param RequestInterface $inspector
     * @param string           $type
     */
    public function __construct(RequestInterface $inspector, $type)
    {
        $this->inspector = $inspector;
        $this->type      = $type;
    }

    /**
     * creates and returns an inspector.
     *
     * @return RequestInterface
     */
    private function getInspector()
    {
        $this->inspector->configure($this->configuration);
        $this->inspector->setPath($this->path);

        return $this->inspector;
    }

    /**
     * {@inheritdoc}
     */
    public function configure($config)
    {
        $this->configuration = $config;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function build($pattern = '*.php')
    {
        $request = $this->getInspector()->build();

        return $this->request($request);
    }

    /**
     * returns the builder type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
