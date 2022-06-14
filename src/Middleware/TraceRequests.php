<?php

namespace MakiniAdapter\TracingErrors\Middleware;

use Illuminate\Support\Str;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Vinelab\Tracing\Contracts\Span;
use Vinelab\Tracing\Propagation\Formats;
use Vinelab\Tracing\Contracts\Tracer;

class TraceRequests
{
    public ContainerInterface $container;
    public Tracer $tracer;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->tracer = $this->container->get(Tracer::class);
    }

    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        $span = null;

        if (!$this->shouldBeExcluded($request->getUri()->getPath())) {
            $spanContext = $this->tracer->extract($request, Formats::PSR_REQUEST);
            $span = $this->tracer->startSpan('Http Request', $spanContext);
            $this->tagRequestData($span, $request);
        }

        $response =  $handler->handle($request);

        if ($span) {
            $span->finish();
            $this->tracer->flush();
        }
        return  $response;
    }

    private function shouldBeExcluded(string $path): bool
    {
        $excludedPaths = $this->container->get('tracing.excluded_paths');

        foreach ($excludedPaths as $excludedPath) {
            if (Str::is($excludedPath, $path)) {
                return true;
            }
        }

        return false;
    }

    protected function tagRequestData(Span $span, Request $request): void
    {
        $span->tag('type', 'http');
        $span->tag('request_method', $request->getMethod());
        $span->tag('request_path', $request->getUri()->getPath());
        $span->tag('request_uri', $request->getUri());
        $span->tag('request_headers', $this->transformedHeaders($request->getHeaders()));
        $span->tag('request_input', json_encode($request->getParsedBody()));
    }

    protected function transformedHeaders(array $headers = []): string
    {
        if (!$headers) {
            return '';
        }

        ksort($headers);
        $max = max(array_map('strlen', array_keys($headers))) + 1;

        $content = '';
        foreach ($headers as $name => $values) {
            $name = implode('-', array_map('ucfirst', explode('-', $name)));

            foreach ($values as $value) {
                $content .= sprintf("%-{$max}s %s\r\n", $name . ':', $value);
            }
        }

        return $content;
    }
}
