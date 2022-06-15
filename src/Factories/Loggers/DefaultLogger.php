<?php


namespace MakiniAdapter\TracingErrors\Factories\Loggers;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Handler\StreamHandler;
use Psr\Container\ContainerInterface;

class DefaultLogger
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function resolve(): AbstractProcessingHandler
    {
        return new StreamHandler($this->container->get('log.path'), $this->container->get('log.level'));
    }
}