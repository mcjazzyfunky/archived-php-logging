<?php

namespace logging\adapters;

use logging\LoggerAdapter;
use logging\adapters\internal\NullLog;

class NullLoggerAdapter implements LoggerAdapter {
    private $log;

    function __construct() {
        $this->log = new NullLog();
    }
    
    function getLog($name) {
        return $this->log;
    }

    function getThresholdByLogName($name) {
        return LOG::NONE;
    }
}
