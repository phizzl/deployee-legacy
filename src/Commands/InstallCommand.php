<?php


namespace Phizzl\Deployee\Commands;

use Phizzl\Deployee\Db\Db;
use Phizzl\Deployee\Di\DiContainerInjectableInterface;
use Phizzl\Deployee\Traits\DiContainerInjectableImplementation;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Yaml\Yaml;

class InstallCommand extends Command implements DiContainerInjectableInterface
{
    use DiContainerInjectableImplementation;

    /**
     * @inheritdoc
     */
    protected function configure()
    {
        parent::configure();
        $this
            ->setName('d:install')
            ->setDescription('Installs Deployee on the system')
            ->setHelp('Before you may start using Deployee you have to install the environment')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $configFile = getcwd() . '/deployee.yml';
        if(file_exists($configFile)){
            $output->writeln("deployee.yml already exists. Nothing to do.");
            exit(0);
        }

        $config = $this->getConfigurationInput($input, $output);
        $this->createDirectoryStructure($config, $output);
        $this->setupDatabase($config);


        ksort($config);
        if(!file_put_contents($configFile, Yaml::dump($config))){
            $output->writeln("Config file could not been written!");
            exit(1);
        }

        $output->writeln("Configuration successfully written");
    }

    /**
     * @param array $config
     */
    private function setupDatabase(array $config)
    {
        $db = new Db();
        $db->setConfiguration($config['db']);
        $db->connect();

        $sql = <<<EOL
CREATE TABLE IF NOT EXISTS `deployee_deploys` (
	`name` VARCHAR(255) NOT NULL,
	`deploydate` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`name`)
)
ENGINE=InnoDB;
EOL;

        $db->execute($sql);
    }

    /**
     * @param array $config
     * @param OutputInterface $output
     */
    private function createDirectoryStructure(array $config, OutputInterface $output)
    {
        if(in_array(strpos($config['deploy']['path'], strlen($config['deploy']['path'])-1), ['\\', '/'])){
            $config['deploy']['path'] = strpos($config['deploy']['path'], 0, strlen($config['deploy']['path'])-1);
        }

        $createDirectories = [
            getcwd() . "/{$config['deploy']['path']}",
            getcwd() . "/{$config['deploy']['path']}/deployments",
            getcwd() . "/{$config['deploy']['path']}/env",
            getcwd() . "/{$config['deploy']['path']}/assets",
        ];

        foreach($createDirectories as $createDirectory){
            if(!is_dir($createDirectory)
                && !mkdir($createDirectory)){
                $output->writeln("Unable to create directory structure: " . $createDirectory);
                exit(1);
            }
        }
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return array
     */
    private function getConfigurationInput(InputInterface $input, OutputInterface $output)
    {
        /* @var QuestionHelper $qHelper */
        $qHelper = $this->getHelper('question');

        $sectionQuestions = [
            "deploy" => [
                "Where should the deployments be stored" => ["deploy", "path"]
            ],
            "db" => [
                "Please enter your database host" => ["localhost", "host"],
                "Please enter your database host port" => [3306, "port"],
                "Please enter your database name" => ["", "name"],
                "Please enter your database user name" => ["", "user"],
                "Please enter your database user password" => ["", "password"],
            ]
        ];

        $config = $this->get('config')->all();
        foreach($sectionQuestions as $section => $questions){
            $config[$section] = !isset($config[$section]) ? [] : $config[$section];
            foreach($questions as $question => $qConfig){
                $qString = $question . ($qConfig[0] != "" ? " [{$qConfig[0]}]: " : ": ");
                $q = new Question($qString, $qConfig[0]);
                $config[$section][$qConfig[1]] = $qHelper->ask($input, $output, $q);
            }
        }

        return $config;
    }
}