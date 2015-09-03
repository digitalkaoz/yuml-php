<?php

namespace YumlPhp\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use YumlPhp\Builder\BuilderInterface;

/**
 * this command generates a class diagram.
 *
 * @author Robert Schönthal <seroscho@googlemail.com>
 */
class ClassesCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        parent::configure();

        $this->getDefinition()->addOption(new InputOption('properties', null, InputOption::VALUE_NONE, 'build with properties'));
        $this->getDefinition()->addOption(new InputOption('methods', null, InputOption::VALUE_NONE, 'build with methods'));
        $this->getDefinition()->addOption(new InputOption('filter', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_REQUIRED, 'glob pattern filter'));

        $this
            ->setName('classes')
            ->setDescription('creates a class diagram of a given folder')
            ->setHelp(<<<EOT
The <info>class-diagram</info> command generates a class diagram from all classes in the given folder

<info>yuml-php classes src/</info> builds class diagram for folder src/
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function getBuilderConfig(BuilderInterface $builder, InputInterface $input)
    {
        $config = parent::getBuilderConfig($builder, $input);

        return array_merge($config, [
            'withMethods'    => $input->getOption('methods'),
            'withProperties' => $input->getOption('properties'),
            'filter'         => $input->getOption('filter'),
        ]);
    }
}
