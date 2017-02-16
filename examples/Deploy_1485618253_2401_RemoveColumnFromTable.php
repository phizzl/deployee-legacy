<?php

class Deploy_1485618253_2401_RemoveColumnFromTable extends Deployee\Deployments\AbstractDeployment
{
    /**
     * @inheritdoc
     */
    public function configure(){
        $dbm = $this->container['db'];
        $this->changeTable(
            $dbm
                ->table('my_test_table')
                ->removeIndex(array('specialIndex'))
        );
    }
}