<?php


namespace Deployee;


interface ContainerAwareInterface
{
    public function setContainer(DIContainer $container);
}