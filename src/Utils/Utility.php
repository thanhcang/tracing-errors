<?php


namespace MakiniAdapter\TracingErrors\Utils;


class Utility
{
    public static function getConfig() : array
    {
        return [
            __DIR__.'/../Config/log.php',
            __DIR__.'/../Config/services.php',
            __DIR__.'/../Config/tracing.php',
        ];
    }
}