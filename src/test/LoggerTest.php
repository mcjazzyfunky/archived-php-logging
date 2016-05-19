<?php

namespace logging;

require_once __DIR__ . '/../../include.php';

use Exception;
use PHPUnit_Framework_TestCase;
use logging\Logger;
use logging\Log;
use logging\adapters\StreamLoggerAdapter;
use logging\adapters\FileLoggerAdapter;

class LoggerTest extends PHPUnit_Framework_TestCase {
    function testRun() {
        // Initialize a file logger:
        // $logFile = __DIR__ . '/test-{date}.log';
        // Logger::setAdapter(new FileLoggerAdapter($logFile));
        
        // Customize output format, if you like:
        // Logger::setAdapter(
        //     new FileLoggerAdapter($logFile, function ($logParams)  {
        //        ...
        //     }));
        
        // Initialize a logger to log out to STDOUT:
        //Logger::setAdapter(new FileLoggerAdapter('php://stdout'));
        Logger::setAdapter(new StreamLoggerAdapter(STDOUT));
    
        Logger::setDefaultThreshold(Log::DEBUG);
        
        // Get the log instance:
        // It would surely be better to inject/pass the log by constructor
        // $log = Logger::getLog('name-of-logger');
        // $log = Logger::getLog(self::class);
        // $log = Logger::getLog(__CLASS__);
        $log = Logger::getLog($this);
        
        $log->debug('Just a debug message (with one placeholder)');
        $log->info('Hey {0}, just wanna say hello', 'Marge');
        
        $error = new Exception('Evil error', 911);

        // Include error message:
        $log->error(
            'Ooops, there was an error: {err}',
            ['err' => $error->getMessage()]);
        
        // Include error message and stack trace:
        $log->critical(
            'Help, there was a critical error: {err}',
            ['err' => $error->getMessage()],
            $error);

        // Include error message and stack trace and some extra log data
        $log->emergency(
            'Run for your lives, there was a core melt accident: {err}',
            ['err' => $error->getMessage()],
            $error,
            ['location' => 'Sector 7G']);
    }
}



