<?php

namespace logging;

use Exception;
use InvalidArgumentException;
use Throwable;

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
        $messageIsArray = is_array($message);
        $ret = null;
        
        if (!is_string($message) && !$messageIsArray) {
            throw new InvalidArgumentException(
                '[LogUtils::formatLogMessage] First argument $message must '
                . 'either be a string or an array');
        } else if ($messageIsArray && !is_string(@$message[0])) {
            throw new InvalidArgumentException(
                '[LogUtils::formatLogMessage] First array element of first '
                . 'argument $message must be a string');
        } else if ($context !== null
            && !is_array($context)
            && !($context instanceof Throwable)
            && !($context instanceof Exception)) {
                
            throw new InvalidArgumentException(
                '[LogUtils::formatLogMessage] Second argument $context must '
                . 'either be an array or an Exception/Throwable or null');
        }

        if ($messageIsArray) {
            $args = [];
            
            for ($i = 1; $i < count($message); ++$i) {
                $item = $message[$i];
                
                $args[] = self::stringify($item);
            }
            
            $ret = vsprintf($message[0], $args);
        } else if (!is_array($context)) {
            $ret = $message;
        } else {
            $replacements = [];
            
            foreach ($context as $key => $value) {
                $replacements['{' . $key . '}'] = self::stringify($value);
            }
            
            $ret = strtr($message, $replacements);
        }

        return $ret;
    }
    
    private static function stringify($value) {
        if ($value === null) {
            $ret = 'null';
        } else if ($value === false) {
            $ret = 'false';
        } else if ($value === true) {
            $ret = 'true';
        } else if (is_string($value)) {
            $ret = $value;
        } else if (is_scalar($value)) {
            $ret = strval($value);
        } else if ($value instanceof Exception || $value instanceof Throwable) {
            $ret = $value->getMessage();
        } else {
            $ret = json_encode($value);
        }

        return $ret;
    }
}
