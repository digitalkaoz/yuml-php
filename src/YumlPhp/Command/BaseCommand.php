<?php

namespace YumlPhp\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use YumlPhp\Builder;
use YumlPhp\Builder\BuilderInterface;

/**
 * this common command.
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
abstract class BaseCommand extends Command
{
    /**
     * @var BuilderInterface
     */
    protected $builder;

    /**
     * @var BuilderInterface
     */
    protected $httpBuilder;

    /**
     * @var BuilderInterface
     */
    protected $consoleBuilder;

    /**
     * @param BuilderInterface $httpBuilder
     * @param BuilderInterface $consoleBuilder
     */
    public function __construct(BuilderInterface $httpBuilder, BuilderInterface $consoleBuilder)
    {
        $this->httpBuilder    = $httpBuilder;
        $this->consoleBuilder = $consoleBuilder;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDefinition([
            new InputArgument('source', InputArgument::REQUIRED, 'the input source'),
            new InputOption('console', null, InputOption::VALUE_NONE, 'log to console'),
            new InputOption('debug', null, InputOption::VALUE_NONE, 'debug'),
            new InputOption('style', null, InputOption::VALUE_NONE, 'yuml style options'),
        ]);
    }

    /**
     * creates the builder.
     *
     * @return BuilderInterface
     */
    private function createBuilder(InputInterface $input)
    {
        $builder = $input->getOption('console') ? $this->consoleBuilder : $this->httpBuilder;
        $builder->configure($this->getBuilderConfig($builder, $input));
        $builder->setPath($input->getArgument('source'));

        return $builder;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $messages = $this->createBuilder($input)->build();

        if (!$messages) {
            $input->setOption('debug', true);
            $messages = $this->createBuilder($input)->build();
            throw new \RuntimeException('Uml Build Error ' . "\n" . implode("\n", $messages ?: []));
        }

        $output->writeln($messages);
    }

    /**
     * creates the builder configuration.
     *
     * @param \YumlPhp\Builder\BuilderInterface $builder
     * @param InputInterface                    $input
     *
     * @return array
     */
    protected function getBuilderConfig(BuilderInterface $builder, InputInterface $input)
    {
        //scruffy, nofunky, plain
        //dir: LR TB RL
        //scale: 180 120 100 80 60
        $style = $input->getOption('style') ?: 'plain;dir:TB;scale:80;';
        $type  = $builder->getType();

        return [
            'url'   => 'http://yuml.me/diagram/' . $style . '/' . $type . '/',
            'debug' => $input->getOption('debug'),
            'style' => $input->getOption('style') ?: 'plain;dir:LR;scale:80;',
        ];
    }
}
