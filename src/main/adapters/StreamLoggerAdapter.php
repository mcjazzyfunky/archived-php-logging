<?php

namespace logging\adapters;

use logging\LoggerAdapter;
use logging\adapters\internal\StreamLog;

class StreamLoggerAdapter implements LoggerAdapter {
    private $stream;
    private $logMessageFormatter;
    private $logs;

    function __construct($stream, callable $logMessageFormatter = null) {
        $this->stream = $stream;
        $this->logMessageFormatter = $logMessageFormatter;
        $this->logs = [];
    }
    
    function getStream() {
        return $this->stream;
    }
    
    function getLog($name) {
        $ret = @$this->logs[$name];
        
        if ($ret === null) {
            $ret = new StreamLog(
                $name, $this->stream, $this->logMessageFormatter);
            
            $this->logs[$name] = $ret;
        }
        
        return $ret;
    }
    
    function getThresholdByLogName($name) {
        return Logger::getDefaultThreshold();
    }
}
