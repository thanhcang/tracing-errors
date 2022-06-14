<?php

namespace MakiniAdapter\TracingErrors\Providers;

use MakiniAdapter\TracingErrors\Utils\ZipkinTracer;
use Psr\Container\ContainerInterface;
use Vinelab\Tracing;
use Vinelab\Tracing\Contracts\Tracer;

class ZipkinServiceProvider
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
            Tracing\Contracts\Tracer::class => function (ContainerInterface $container): Tracer {
                $service = $container->get('app.short_name');
                $zipkin = $container->get('zipkin');
                $tracer = new ZipkinTracer(
                    $service,
                    $zipkin['host'],
                    $zipkin['port'],
                    $zipkin['options']['128bit'],
                    $zipkin['options']['request_timeout'],
                );

                ZipkinTracer::setMaxTagLen(
                    $zipkin['options']['max_tag_len']
                );

                return $tracer->init();
            }
        ];
    }

    public function boot()
    {
    }
}
