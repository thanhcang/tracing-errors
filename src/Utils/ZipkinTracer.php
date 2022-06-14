<?php

namespace MakiniAdapter\TracingErrors\Utils;

use Vinelab\Tracing\Drivers\Zipkin\ZipkinTracer as BaseZipkinTracer;

class ZipkinTracer extends BaseZipkinTracer
{
    protected function resolveCollectorIp(string $host): string
    {
        $ipv4 = gethostbyname($host);

        if ($ipv4 == $host) {
            return "127.0.0.1";
        }

        return $ipv4;
    }
}
