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
    
    function getLog($domain) {
        $ret = @$this->logs[$domain];
        
        if ($ret === null) {
            $ret = new StreamLog(
                $domain, $this->stream, $this->logMessageFormatter);
            
            $this->logs[$domain] = $ret;
        }
        
        return $ret;
    }
    
    function getLogThresholdByDomain($domain) {
        return Logger::getDefaultLogThreshold();
    }
}
