<?php

namespace MakiniAdapter\TracingErrors\Utils;

use Vinelab\Tracing\Contracts\Span;
use Vinelab\Tracing\Contracts\Tracer;

class TracingSync
{
    private Tracer $tracer;
    private ?Span $childSpan = null;
    private int $integrationId;

    public function __construct(Tracer $tracer, int $integrationId)
    {
        $this->tracer = $tracer;
        $this->integrationId = $integrationId;
    }

    public static function make(Tracer $tracer, int $integrationId): self
    {
        return new self($tracer,$integrationId);
    }

    public function startChildSpan(string $scope): self
    {
        $this->childSpan = $this->tracer->startSpan("Sync {$scope}", $this->tracer->getRootSpan()->getContext());
        $this->childSpan->tag('integration_id', $this->integrationId);

        return $this;
    }

    public function endChildSpan(): self
    {
        if ($this->childSpan) {
            $this->childSpan->finish();
        }

        $this->flushChildSpan();

        return $this;
    }

    private function flushChildSpan(): void
    {
        $this->childSpan = null;
    }
}
