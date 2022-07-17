<?php

namespace MakiniAdapter\TracingErrors\Factories\Loggers;

use MakiniAdapter\TracingErrors\Utils\HeadersResource;
use Monolog\Handler\AbstractProcessingHandler;
use Psr\Container\ContainerInterface;
use Ramsey\Uuid\Uuid;
use Aws;
use Maxbanton\Cwh\Handler\CloudWatch;

class CloudwatchLogger
{
    private ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    /**
     * @throws \Psr\Container\NotFoundExceptionInterface
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Exception
     */
    public function resolve(array $resource = []): AbstractProcessingHandler
    {
        $configs = $this->container->get('services.aws');
        $sdkParams = [
            'region' => $configs['region'],
            'version' => 'latest',
            'credentials' => [
                'key' => $configs['key'],
                'secret' => $configs['secret'],
            ]
        ];

        $client = new Aws\CloudWatchLogs\CloudWatchLogsClient($sdkParams);

        return new CloudWatch(
            $client,
            $this->group(),
            $this->stream($resource),
            $this->container->get('log.aws.retention'),
            $this->container->get('log.aws.batchSize')
        );
    }

    public function stream($data = []): string
    {
        $resource = HeadersResource::getInstanceFromHeaders();

        if ($resource->transId) {
            return $resource->transId;
        }

        if ($data) {
            $resource = HeadersResource::getInstanceFromResource($data);
        }

        if ($resource->transId) {
            return $resource->transId;
        }

        return Uuid::uuid4()->toString();
    }

    private function group(): string
    {
        $resource = HeadersResource::getInstanceFromHeaders();
        if ($resource->tranEnv && in_array($resource->tranEnv, ['dev', 'prod'])) {
            return $this->container->get('log.aws.group') . '/' . $resource->tranEnv;
        }

        return $this->container->get('log.aws.group.default');
    }
}