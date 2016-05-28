<?php

namespace logging\adapters\internal;

use InvalidArgumentException;
use Throwable;
use logging\AbstractLog;
use logging\LogUtils;

class NullLog extends AbstractLog {
    function log($level, $message, $context) {
        if (!LogUtils::isValidLogLevel($level, true)) {
            throw new InvalidArgumentException(
                '[NullLog::log] First argument $level must be a '
                . 'valid log level');
        }
    }
    
    function isEnabled($level) {
        return false;
    }
}
