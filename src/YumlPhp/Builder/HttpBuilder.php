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

use Buzz\Browser;
use Buzz\Message\Response;
use YumlPhp\Request\RequestInterface;

/**
 * common HttpBuilder for API requests
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class HttpBuilder extends Builder
{
    /**
     * @var \Buzz\Browser
     */
    private $browser;

    /**
     * injects the http client
     *
     * @param RequestInterface $request
     * @param Browser          $browser
     * @param string           $type
     */
    public function __construct(RequestInterface $request, Browser $browser, $type)
    {
        $this->browser = $browser;

        parent::__construct($request, $type);
    }

    /**
     * request the diagram from the API
     *
     * @return array|string
     * @throws \RuntimeException
     */
    public function request(array $request)
    {
        $url = $this->configuration['url'];

        if ($this->configuration['debug']) {
            return join(',', $request);
        }

        if (!count($request)) {
            throw new \RuntimeException('No Request built for: ' . $this->path);
        }

        $response = $this->browser->post($url, array(), 'dsl_text=' . urlencode(join(',', $request)));

        if ($response instanceof Response && $response->isSuccessful()) {
            $file = $response->getContent();

            return array(
                '<info>PNG</info> http://yuml.me/' . $file,
                '<info>URL</info> http://yuml.me/edit/' . str_replace('.png', '', $file),
                '<info>PDF</info> http://yuml.me/' . str_replace('.png', '.pdf', $file),
            );
        }

        throw new \RuntimeException('API Error for Request: ' . $url . join(',', $request));
    }
}
