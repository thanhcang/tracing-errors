<?php

namespace MakiniAdapter\TracingErrors\Handlers;

use Slim\Handlers\ErrorHandler as BaseErrorHandler;
use Sentry;

class ErrorHandler extends BaseErrorHandler
{
    protected function logError(string $error): void
    {
        Sentry\captureException($this->exception);
        parent::logError($error);
    }
}
