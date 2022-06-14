<?php

namespace MakiniAdapter\TracingErrors\Providers;

use MakiniAdapter\TracingErrors\Factories\LoggerManager;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

class LoggingServiceProvider
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return array
     */
    public static function definitions(): array
    {
        return [
            LoggerInterface::class => function (ContainerInterface $container) {
                return (new LoggerManager($container))->resolve();
            }
        ];
    }

    public function boot()
    {

    }
}
