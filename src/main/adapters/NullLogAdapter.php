<?php

namespace logging\adapters;

use logging\LogAdapter;
use logging\adapters\internal\NullLog;

class NullLogAdapter implements LogAdapter {
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
