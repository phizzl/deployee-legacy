# Deployee
## Setup
### Install Composer dependencies
First you have to install all dependencies via composer.

For information how to install composer visit <https://getcomposer.org/download/>

Run the following command
```bash
php composer.phar install
```

### Configuration
You have to edit the parameters in the config.yml
You need to have a database connection to run the tool.

```yaml
default_environment: test
jira: ~
environments:
  test:
    deployments: ~/deployments
    database:
      mysql:
        host: localhost
        user: root
        password: ~
        name: test
```

You can define multiple environments, but you must have one environment at least, that is also defined aus default environment.

### Initialization
Before you can start deploying you have to initialize your environment. 

You have to do this for each environment separately. You can user the --env option to tell the application which environment to use
```bash
php bin/deployee.php deployee:init --env=test
```

If you don't pass the --env option your default environment that you configured in the configuration is being used.

## Create a deployment
### Create the file
To create a new deployment you have to use the deployee:create command.
You have to pass a name for the deployment and optionally a ticket number
```bash
php bin/deployee.php deployee:create MyTestDeploymet Ticket1
```

After running this command a new file will be added to you environments path you defined in the parameter _deployments_

The file looks like
```php
<?php

class Deploy_1485504939_430_MyTestDeploymet extends Deployee\Deployments\AbstractDeployment
{
    /**
     * @inheritdoc
     */
    public function configure(){
        $this->context->set('ticket', 'Ticket1');
       
    }
}
```

### Define the deployment
After you added the file you can start defining your deployment. So let's add a file called _test.txt_ to the deployment directory and define it's content
```php
<?php

class Deploy_1485504939_430_MyTestDeploymet extends Deployee\Deployments\AbstractDeployment
{
    /**
     * @inheritdoc
     */
    public function configure(){
        $this->context->set('ticket', 'Ticket1');
        $this->createFile(__DIR__ . '/test.txt', 'This is my deployment!');
    }
}
```

### Get the deployment description
Great! You created your first deployment. Now we can use the following command to get a description of what steps in the deployment will be executed
```bash
php bin/deployee.php deployee:describe > MyDeployment.md
```
The description will be send to stdout in Markdown language. If you have defined a JIRA url in the config.yml and the deployment has a ticket context the ticket names will be linked to JIRA.

### Executing the deployment
Now we can execute the deployment.
```bash
php bin/deployee.php deployee:deploy
```

Now every deployment is being executed, that hasn't being executed in your environment yet. The full history and audit can be viewed in your environments database in the tables
* deployee_history
* deployee_deployment_audit
* deployee_task_audit