<?php


namespace Phizzl\Deployee\Commands;


use Phizzl\Deployee\Di\DiContainerInjectableInterface;
use Phizzl\Deployee\Environment\EnvironmentInjectableInterface;
use Phizzl\Deployee\Environment\EnvironmentInterface;
use Phizzl\Deployee\Logger\LoggerInjectableInterface;
use Phizzl\Deployee\Traits\DiContainerInjectableImplementation;
use Phizzl\Deployee\Traits\EnvironmentInjectableImplementation;
use Phizzl\Deployee\Traits\LoggerInjectableImplementation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
implements DiContainerInjectableInterface, LoggerInjectableInterface, EnvironmentInjectableInterface
{
    use DiContainerInjectableImplementation;
    use LoggerInjectableImplementation;
    use EnvironmentInjectableImplementation;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        $this->addOption('env', null,InputArgument::OPTIONAL, 'Define the default environment');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if($env = $input->getOption('env')){
            $this->container()->parameter('env.active', $input->getOption('env'));
        }
    }

    /**
     * @return \Phizzl\Deployee\Configuration\ConfigurationInterface
     */
    protected function config()
    {
        return $this->env()->getConfiguration();
    }
}