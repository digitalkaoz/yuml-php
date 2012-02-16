<?php

namespace YumlPhp\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Command\Command;
use Buzz\Browser;

use YumlPhp\Builder\BuilderInterface;
use YumlPhp\Builder\ClassDiagramBuilder;
use YumlPhp\Builder\YumlClassDiagramBuilder;
use YumlPhp\Builder\ConsoleClassDiagramBuilder;

/**
 * 
 *
 */
class ClassDiagramCommand extends Command
{
    private $builder;
    
    /**
     * @see Command::configure()
     */
    protected function configure()
    {
        $this
            ->setDefinition(array(
                new InputArgument('folder', InputArgument::REQUIRED, 'the folder to scan'),
                new InputOption('console', null, InputOption::VALUE_NONE, 'log to console'),
                new InputOption('debug', null, InputOption::VALUE_NONE, 'debug'),
                new InputOption('properties', null, InputOption::VALUE_NONE, 'build with properties'),
                new InputOption('methods', null, InputOption::VALUE_NONE, 'build with methods')
            ))
            ->setDescription('creates a class diagram of a given folder')
            ->setHelp(<<<EOT
The <info>class-diagram</info> command generates a class diagram from all classes in the given folder

<info>yuml-php classes src</info> classes only
<info>yuml-php classes --methods src/</info> generate uml with methods
<info>yuml-php classes --properties src/</info> generate uml with properties
EOT
            )
            ->setName('classes')
        ;
    }

    /**
     * @see Command
     *
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {        
        $messages = $this->createBuilder($input)->build();
        
        if(!count($messages)) {
            $input->setOption('debug',true);
            $messages = $this->createBuilder($input)->build();
            throw new \RuntimeException('Uml Build Error '."\n".join("\n", $messages ?: array()));
        }
        
        $output->writeln($messages);
    }
      
    protected function createBuilder(InputInterface $input)
    {
        $config = array(
          'withMethods' => (boolean) $input->getOption('methods'),
          'withProperties' => (boolean) $input->getOption('properties'),
          'url' => 'http://yuml.me/diagram/plain;dir:TB/class/shorturl/',
          'debug' => $input->getOption('debug')
        );
        
        if($input->getOption('console')) {
            $builder = new ConsoleClassDiagramBuilder();
        } else {
            $builder = new YumlClassDiagramBuilder(new Browser());
        }
        
        return $builder->configure($config)->setPath($input->getArgument('folder'));
    }
}