<?php

namespace MakiniAdapter\TracingErrors\Providers;

use Illuminate\Support\Arr;
use Psr\Container\ContainerInterface;
use Sentry;

class SentryServiceProvider
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
        return [];
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function boot()
    {
        Sentry\init([
            'dsn' => Arr::get($this->container->get('services.sentry'), 'dsn')
        ]);
    }
}
