<?php


namespace Phizzl\Deployee\Commands;

use Composer\Autoload\ClassLoader;
use Composer\Composer;
use Phizzl\Deployee\Deployments\DeploymentDefinitionInterface;
use Phizzl\Deployee\Deployments\Dispatcher\DeploymentDispatcher;
use Phizzl\Deployee\Deployments\Dispatcher\DeploymentDispatchValidatorInterface;
use Phizzl\Deployee\Descriptor\DeploymentDescriptor;
use Phizzl\Deployee\Di\DiInjectorInterface;
use Phizzl\Deployee\Filesystem\DeploymentFileFinder;
use Phizzl\Deployee\Filesystem\PathHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends AbstractCommand
{
    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('d:generate')
            ->setDescription('Generate deployment')
            ->setHelp('')
            ->addArgument('identifier', InputArgument::OPTIONAL, '', null)
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        parent::execute($input, $output);

        $identifier = $input->getArgument('identifier') ? $input->getArgument('identifier')  : '';
        $className = "Deploy_" . time() . "_" . uniqid() .
            ($input->getArgument('identifier') ? "_" . $input->getArgument('identifier')  : '');
        $classTemplate = <<<EOF
<?php

use Phizzl\Deployee\Deployments\AbstractDeploymentDefinition;

/**
 * @describe Your description goes here
 * @ticket DEPLOYEE-001
 */
class $className extends AbstractDeploymentDefinition
{
    /**
     * @return array
     */
    public function define()
    {

        return parent::define();
    }
}
EOF;

        /* @var PathHelper $pathHelper */
        $pathHelper = $this->container()->get('filesystem.paths');
        $pathname = $pathHelper->getDeploymentFilesPath() . DIRECTORY_SEPARATOR . "{$className}.php";
        if(!file_put_contents($pathname, $classTemplate)){
            throw new \Exception("Generation failed");
        }

        $output->writeln("Generated unter $pathname");
    }
}