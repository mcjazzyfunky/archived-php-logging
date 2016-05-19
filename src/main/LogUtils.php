<?php

namespace logging;

use InvalidArgumentException;

final class LogUtils {
    private function __construct() {
    }
    
    private static $logLevelNames = [
        Log::TRACE => 'TRACE',
        Log::DEBUG => 'DEBUG',
        Log::INFO => 'INFO',
        Log::NOTICE => 'NOTICE',
        Log::WARN => 'WARN',
        Log::ERROR => 'ERROR',
        Log::CRITICAL => 'CRITICAL',
        Log::ALERT => 'ALERT',
        Log::EMERGENCY => 'EMERGENCY',
        Log::NONE => 'NONE'
    ];
    
    static function isValidLogLevel($level, $excludeLevelNone = false) {
        return $level >= Log::TRACE && $level <= Log::EMERGENCY
            || $level === Log::NONE && !$excludeLevelNone;
    }
    
    static function getLogLevelName($level) {
        return self::$logLevelNames[$level];
    }
    
    static function formatLogMessage($message, $context = null) {
        if (!is_string($message)) {
            throw new InvalidArgumentException(
                '[LogUtils::formatLogMessage] First argument $message must be '
                . 'a string');
        } else if ($context !== null && !is_scalar($context) && !is_array($context)) {
            throw new InvalidArgumentException(
                '[LogUtils::formatLogMessage] Second argument $context must either '
                . 'be a scalar or an array or null');
        }
        
        $ret = null;
        
        if ($context === null) {
            $ret = $message;    
        } else {
            if (is_scalar($context)) {
                $ret = str_replace('{0}', $context, $message);
            } else if (is_array($context)) {
                $replacements = [];
                
                foreach ($context as $key => $value) {
                    $replacements['{' . $key . '}'] = $value;
                }
                
                return strtr($message, $replacements);
            }
        }
        
        return $ret;
    }
}
