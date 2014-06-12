<?php

namespace YumlPhp;

/**
 * Container
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 * @codeCoverageIgnore
 */
class Container extends \Pimple
{
    public function __construct()
    {
        parent::__construct($this->buildContainer());
    }

    private function buildContainer()
    {
        $services = array();

        $this->addCommonServices($services);
        $this->addBuilders($services);
        $this->addCommands($services);

        return $services;
    }

    private function addCommonServices(array &$services)
    {
        $services['browser'] = function () {
            $client = function_exists('curl_version') ? new \Buzz\Client\Curl() : new \Buzz\Client\FileGetContents();
            $browser = new \Buzz\Browser($client);
            if ($client instanceof \Buzz\Client\Curl) {
                $browser->getClient()->setTimeout(30);
            }

            return $browser;
        };

        $services['request.classes.http'] = function () {
            return new \YumlPhp\Request\Http\ClassesRequest();
        };
        $services['request.classes.console'] = function () {
            return new \YumlPhp\Request\Console\ClassesRequest();
        };

        $services['request.file.http'] = function () {
            return new \YumlPhp\Request\Http\FileRequest();
        };
        $services['request.file.console'] = function () {
            return new \YumlPhp\Request\Console\FileRequest();
        };
    }

    private function addBuilders(array &$services)
    {
        $services['builder.classes.http'] = function (\Pimple $container) {
            return new \YumlPhp\Builder\HttpBuilder($container['request.classes.http'], $container['browser'], 'class');
        };

        $services['builder.activity.http'] = function (\Pimple $container) {
            return new \YumlPhp\Builder\HttpBuilder($container['request.file.http'], $container['browser'], 'activity');
        };

        $services['builder.use_case.http'] = function (\Pimple $container) {
            return new \YumlPhp\Builder\HttpBuilder($container['request.file.http'], $container['browser'], 'usecase');
        };

        $services['builder.classes.console'] = function (\Pimple $container) {
            return new \YumlPhp\Builder\ConsoleBuilder($container['request.classes.console'], 'class');
        };

        $services['builder.activity.console'] = function (\Pimple $container) {
            return new \YumlPhp\Builder\ConsoleBuilder($container['request.file.console'], 'activity');
        };

        $services['builder.use_case.console'] = function (\Pimple $container) {
            return new \YumlPhp\Builder\ConsoleBuilder($container['request.file.console'], 'usecase');
        };
    }

    private function addCommands(array &$services)
    {
        $services['command.classes'] = function (\Pimple $container) {
            return new \YumlPhp\Command\ClassesCommand($container['builder.classes.http'], $container['builder.classes.console']);
        };

        $services['command.activity'] = function (\Pimple $container) {
            return new \YumlPhp\Command\ActivityCommand($container['builder.activity.http'], $container['builder.activity.console']);
        };

        $services['command.use_case'] = function (\Pimple $container) {
            return new \YumlPhp\Command\UseCaseCommand($container['builder.use_case.http'], $container['builder.use_case.console']);
        };
    }

}
