<?php

namespace logging\adapters;

use logging\LoggerAdapter;
use logging\adapters\internal\NullLog;

class NullLoggerAdapter implements LoggerAdapter {
    private $log;

    function __construct() {
        $this->log = new NullLog();
    }
    
    function getLog($domain) {
        return $this->log;
    }

    function getLogThresholdByDomain($domain) {
        return LOG::NONE;
    }
}
