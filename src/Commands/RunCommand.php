<?php


namespace Phizzl\Deployee\Commands;

use Composer\Autoload\ClassLoader;
use Composer\Composer;
use Phizzl\Deployee\Deployments\DeploymentDefinitionInterface;
use Phizzl\Deployee\Deployments\Dispatcher\DeploymentDispatcher;
use Phizzl\Deployee\Deployments\Dispatcher\DeploymentDispatchValidatorInterface;
use Phizzl\Deployee\Di\DiInjectorInterface;
use Phizzl\Deployee\Filesystem\DeploymentFileFinder;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends AbstractCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('d:run')
            ->setDescription('Run deployments')
            ->setHelp('')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        /* @var DiInjectorInterface $injector */
        $injector = $this->get('di.injector');
        $deploymentFileFinder = new DeploymentFileFinder();
        $dispatcher = new DeploymentDispatcher();
        $injector->injectDependencies($deploymentFileFinder);
        $injector->injectDependencies($dispatcher);
        $deploymentFileFinder->findDeploymentFiles();

        /* @var DeploymentDispatchValidatorInterface $deploymentValidator */
        $deploymentValidator = $this->container()->get('deployment.validator');
        $start = microtime(true);

        foreach($this->publishDeploymentFilesToClassLoader($deploymentFileFinder) as $class){
            /* @var DeploymentDefinitionInterface $deploymentDefinition */
            $deploymentDefinition = new $class;
            if(!$deploymentValidator->canBeDispatched($deploymentDefinition)){
                $this->logger()->debug("Skipping deployment", [
                    "deployment" => get_class($deploymentDefinition)
                ]);
                continue;
            }

            $injector->injectDependencies($deploymentDefinition);
            $dispatcher->dispatch($deploymentDefinition);
            $deploymentValidator->closeDeployment($deploymentDefinition);
        }

        $output->writeln("Finished in " . round(microtime(true)-$start, 4) . " seconds");
    }

    private function publishDeploymentFilesToClassLoader(DeploymentFileFinder $finder)
    {
        $return = [];
        /* @var ClassLoader $autoloader */
        $autoloader = $this->container()->get('composer.class_loader');
        /* @var \SplFileInfo $file */
        foreach($finder as $file) {
            $class = $this->guessClassNameByFile($file->getPathname());
            $autoloader->addClassMap([
                $class => $file->getPathname()
            ]);
            $return[] = $class;
        }

        return $return;
    }

    private function guessClassNameByFile($filepath)
    {
        $filename = basename($filepath);
        $class = substr($filename, 0, strrpos($filename, '.php'));

        return $class;
    }
}