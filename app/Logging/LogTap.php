<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\HostnameProcessor;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\IntrospectionProcessor;
use App\Logging\LogProcessor;

class LogTap
{
    const FORMAT = "[%datetime%] %level_name% %channel% %message% %extra.file% %extra.line% %extra.class% %extra.function% %extra.hostname% %extra.url% %extra.ip% %extra.http_method% %extra.server% %extra.referrer% %extra.custom% %context.memo%\n";

    public function __invoke($logger)
    {
        $hostnameProcessor = new HostnameProcessor();
        $webProcessor = new WebProcessor();
        $introspectionProcessor = new IntrospectionProcessor(Logger::DEBUG, ['Illuminate\\']);
        $sampleProcessor = new LogProcessor();

        $lineFormatter = new LineFormatter(static::FORMAT);

        foreach ($logger->getHandlers() as $handler) {
            $handler->pushProcessor($webProcessor);
            $handler->pushProcessor($hostnameProcessor);
            $handler->pushProcessor($introspectionProcessor);
            $handler->pushProcessor($sampleProcessor);
            $handler->setFormatter($lineFormatter);
        }
    }
}
