<?php

namespace logging\adapters\internal;

use InvalidArgumentException;
use Throwable;
use logging\AbstractLog;
use logging\Log;
use logging\Logger;
use logging\LogUtils;

class CustomLog extends AbstractLog {
    private $stream;

    function __construct(
        $name,
        callable $performLogRequest,
        callable $getThresholdByLogName = null) {
        
        $this->name = $name;
        $this->performLogRequest = $performLogRequest;
        $this->getThresholdByLogName = $getThresholdByLogName;
    }
    
    function log($level, $message, $args = null, $cause = null, $extra = null) {
        $date = date ('Y-m-d H:i:s');
        $levelName = LogUtils::getLogLevelName($level);
        $text = LogUtils::formatLogMessage($message, $args); 
        $name = $this->name;
        
        $performLogRequest = $this->performLogRequest;
        
        $performLogRequest([
            'date' => $date,
            'level' => $levelName,
            'levelNo' => $level,
            'message' => $text,
            'cause' => $cause,
            'extra' => $extra,
            'logName' => $name
        ]);
    }
    
    function isEnabled($level) {
        $ret = false;
        
        if ($this->getThresholdByLogName !== null) {
            $getThresholdByLogName = $this->getThresholdByLogName;
            
            $ret = $getThresholdByLogName($this->name);
        } else {
            $ret = $level >= Logger::getDefaultThreshold();
        }
        
        return $ret;
    }
}
