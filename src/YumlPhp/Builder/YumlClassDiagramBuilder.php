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

/**
 * the Yuml Builder generates a ClassDiagram via the http://yuml.me API
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class YumlClassDiagramBuilder extends Builder
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
     * builds a request array for the yuml API
     * 
     * @return YumlClassDiagramBuilder
     */
    protected function buildRequest()
    {
        foreach ($this->classes as $class) {

            /** @var $class \ReflectionClass */
            $name = $this->buildName($class);
            $parent = $this->buildParent($class, '[', ']^');
            $interfaces = $this->buildInterfaces($class, '<<', '>>]^-.-[');
            $props = $this->buildProperties($class);
            $methods = $this->buildMethods($class);

            $prefix = null;
            $suffix = null;
            if ($class->isInterface()) {
                $prefix = '';
                $suffix = '{bg:orange}';
            }
            
            //build pattern
            $pattern = "%s[%s%s%s%s]";
            if (count($methods) || count($props)) {
                $pattern = "%s[%s%s|%s%s]";
            }
            if (count($props) && count($methods)) {
                $pattern = "%s[%s%s|%s|%s]";
            }

            $line = sprintf($pattern, $parent, $prefix.join(';', $interfaces), $name, join(';', $props), join(';', $methods).$suffix);

            if ($class->isInterface()) {
                array_unshift($this->request, $line);
            } else {
                $this->request[] = $line;
            }
        }

        return $this;
    }

    /**
     * request the diagram from the API
     * 
     * @return array|string
     * @throws \RuntimeException 
     */
    protected function requestDiagram()
    {
        $url = $this->configuration['url'];

        if ($this->configuration['debug']) {
            return join(',', $this->request);
        }
        
        if (!count($this->request)) {
            throw new \RuntimeException('No Classes found in: '.$this->path);
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

    /**
     * prepares a class name into its FQDN with namespace if not found in current class namespaces
     * 
     * @param \ReflectionClass $class
     * @return string 
     */
    protected function prepare(\ReflectionClass $class)
    {
        $name = parent::prepare($class);

        if (!in_array($class->getNamespaceName(), array_keys($this->namespaces))) {
            return $name.'{bg:white}';
        }
        
        return $name;
    }
}
