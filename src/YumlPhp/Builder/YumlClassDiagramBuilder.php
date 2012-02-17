<?php

namespace YumlPhp\Builder;

use \Buzz\Browser;

/**
 * Description of ClassDiagramBuilder
 *
 * @author caziel
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
     * @inheritDoc
     */
    protected function buildRequest()
    {
        foreach ($this->classes as $class) {

            /** @var $class \ReflectionClass */
            $name = $this->buildName($class);
            $parent = $this->buildParent($class, '[',']^');
            $interfaces = $this->buildInterfaces($class,'<<','>>]^-.-[');
            $props = $this->buildProperties($class);
            $methods = $this->buildMethods($class);
                                             
            //build pattern
            $pattern = "%s[%s%s%s%s]";
            if(count($methods) || count($props)) {
                $pattern = "%s[%s%s|%s%s]";
            }
            if(count($props) && count($methods)) {
                $pattern = "%s[%s%s|%s|%s]";
            }
            
            $line = sprintf($pattern, $parent, join(';',$interfaces), $name, join(';', $props), join(';', $methods));
            
            if($class->isInterface()) {
                array_unshift($this->request, $line);                
            } else {
                $this->request[] = $line;
            }            
        }

        return $this;
    }
        
    protected function requestDiagram()
    {
        $url = $this->configuration['url'].  urlencode($this->format(join(',', $this->request)));
        
        if ($this->configuration['debug']){
            return $this->format(join(',', $this->request));
        }
        
        $response = $this->browser->get($url);
        
        if ($response && 500 != $response->getStatusCode()) {
            $tiny = str_replace('"','',substr($response->getHeader('Content-Disposition'),  strpos($response->getHeader('Content-Disposition'), '="')+2));
            
            $result = array(
            '<info>PNG</info> http://yuml.me/'.$tiny,
            '<info>URL</info> http://yuml.me/edit/'.  str_replace('.png','',$tiny),
            '<info>PDF</info> http://yuml.me/'.  str_replace('.png','.pdf',$tiny),
            );

            return $result;        
        }
        
        return array();
    }

    /**
     * yuml cant handle blackslashes, so rewriting them to slashes
     * 
     * @param type $string
     * @return type 
     */
    protected function format($string)
    {
        return str_replace('\\', '/', $string);
    }
    
}
