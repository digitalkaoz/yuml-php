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

use Symfony\Component\Finder\Finder;
use Buzz\Browser;

use YumlPhp\Analyzer\File;

/**
 * common HttpBuilder for API requests
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
abstract class HttpBuilder extends Builder
{
    private $browser;

    /**
     * injects the http client
     * 
     * @param Browser $browser 
     */
    public function __construct(Browser $browser)
    {
        $this->browser = $browser;
    }

    /**
     * request the diagram from the API
     * 
     * @return array|string
     * @throws \RuntimeException 
     */
    public function request()
    {
        $url = $this->configuration['url'];

        if ($this->configuration['debug']) {
            return join(',', $this->request);
        }
        
        if (!count($this->request)) {
            throw new \RuntimeException('No Request built for: '.$this->path);
        }

        $response = $this->browser->post($url,array(
          'X-Requested-With' =>'XMLHttpRequest',
          'Content-Type' => 'application/x-www-form-urlencoded',
          'Accept-Encoding' => 'gzip,deflate,sdch'
        ),'dsl_text='.  urlencode(join(',', $this->request)));

        if ($response && 500 > $response->getStatusCode()) {
            $file = $response->getContent();

            $result = array(
              '<info>PNG</info> http://yuml.me/' . $file,
              '<info>URL</info> http://yuml.me/edit/' . str_replace('.png', '', $file),
              '<info>PDF</info> http://yuml.me/' . str_replace('.png', '.pdf', $file),
            );

            return $result;
        }
        
        throw new \RuntimeException('API Error for Request: '.$url.join(',', $this->request));
    }
}
