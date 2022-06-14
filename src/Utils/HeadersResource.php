<?php


namespace MakiniAdapter\TracingErrors\Utils;

use Illuminate\Support\Arr;
use Illuminate\Support\Fluent;

/**
 * @property string transId
 * @property string tranEnv
 */
class HeadersResource extends Fluent
{
    const INTEGRATION_TRANS_ID = 'X-Integration-Trans-Id';
    const INTEGRATION_ENV = 'X-Integration-Env';

    public function __construct($attributes = [])
    {
        parent::__construct($attributes);

        if ($transId = Arr::get($attributes, self::INTEGRATION_TRANS_ID)) {
            $this->transId = is_array($transId) ? Arr::first($transId) : $transId;
        }

        if ($transEnv = Arr::get($attributes, self::INTEGRATION_ENV)) {
            $this->tranEnv = is_array($transEnv) ? Arr::first($transEnv) : $transEnv;
        }
    }

    public function getInternalHeaders(): array
    {
        $options = [];
        $headers = [];

        if ($this->transId) {
            $headers = Arr::add($headers, self::INTEGRATION_TRANS_ID, $this->transId);
        }

        if ($this->tranEnv) {
            $headers = Arr::add($headers, self::INTEGRATION_ENV, $this->tranEnv);
        }

        if ($headers !== []) {
            $options['headers'] = $headers;
        }

        return $options;
    }

    public static function getInstanceFromHeaders(): self
    {
        return new self(getallheaders());
    }
}
