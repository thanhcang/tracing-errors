<?php

namespace MakiniAdapter\TracingErrors\Factories;

use MakiniAdapter\TracingErrors\Utils\HeadersResource;
use Monolog\Formatter\LineFormatter;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use MakiniAdapter\TracingErrors\Factories\Loggers;

class LoggerManager
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function resolve(array $resource = []): LoggerInterface
    {
        $instance = $this->isCloudWatch($resource) ? (new Loggers\CloudwatchLogger($this->container)) : (new Loggers\DefaultLogger($this->container));
        $handler = $instance->resolve($resource);

        $lineFormatter = new LineFormatter(null, null, true, true);
        $handler->setFormatter($lineFormatter);
        $log = new Logger($this->container->get('log.name'));
        $log->pushHandler($handler);

        return $log;
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function isCloudWatch(array $resource = []): bool
    {
        $internalHeaderResource =  HeadersResource::getInstanceFromHeaders();
        
        if ($internalHeaderResource === []) {
            $internalHeaderResource = HeadersResource::getInstanceFromResource($resource);
        }

        if (!$internalHeaderResource->transId) {
            return false;
        }

        return $this->container->get('log.channel') === 'cloudwatch';
    }
}