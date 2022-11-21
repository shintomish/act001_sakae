<?php
namespace App\Logging;

use Monolog\Processor\ProcessorInterface;
use App\Logging\Formatters\ApplicationLogFormatter;

class LogProcessor implements ProcessorInterface
{
    public function __invoke(array $record)
    {
        //$record['extra']['custom'] = 'log_extra';
        //$record['extra']= 'log_extra';
        //return $record;
    }

    /**
     * Customize the given logger instance.
     *
     * @param  \Illuminate\Log\Logger  $logger
     * @return void
     */
    // public function __invoke($logger)
    // {
    //     foreach ($logger->getHandlers() as $handler) {
    //         $handler->setFormatter(app()->make(ApplicationLogFormatter::class));
    //     }
    // }
}

}
