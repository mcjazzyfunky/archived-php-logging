<?php

namespace logging;

require_once __DIR__ . '/../../include.php';

use Exception;
use PHPUnit_Framework_TestCase;
use logging\Logger;
use logging\Log;
use logging\adapters\StreamLogAdapter;
use logging\adapters\FileLogAdapter;
use logging\adapters\CustomLogAdapter;

class LoggerTest extends PHPUnit_Framework_TestCase {
    function testRun() {
        // Initialize a file logger:
        // $logFile = __DIR__ . '/test-{date}.log';
        // Logger::setAdapter(new FileLogAdapter($logFile));
        
        // Customize output format, if you like:
        // Logger::setAdapter(
        //     new FileLogAdapter($logFile, function ($logParams)  {
        //        ...
        //     }));
        
        // Initialize a logger to log out to STDOUT:
        //Logger::setAdapter(new FileLogAdapter('php://stdout'));
        //Logger::setAdapter(new StreamLogAdapter(STDOUT));
        
        // Customize logging completely
        Logger::setAdapter(new CustomLogAdapter(function ($logParams) {
            if ($logParams['cause'] !== null) {
                $logParams['cause'] = $logParams['cause']->getMessage();
            }
            
            print_r($logParams);  
        }));
    
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



