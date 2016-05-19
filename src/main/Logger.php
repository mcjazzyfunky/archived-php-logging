<?php

namespace logging;

use InvalidArgumentException;
use logging\Log;
use logging\adapters\NullLoggerAdapter;

final class Logger {
    private static $adapter = null;
    private static $nullLog = null;
    private static $defaultLogThreshold = Log::NONE;
    
    private function Logger() {
    }
    
    static function getLog($domain) {
        $isObject = is_object($domain);
        $isString = is_string($domain);
        
        if (!$isObject && !$isString) {
            throw new InvalidArgumentException(
                '[Logger.getLog] First argument $domain must not be '
                . 'a string or an object');
        } else if ($isString
            && (strlen($domain) === 0 || trim($domain) !== $domain)) {
            
            throw new InvalidArgumentException(
                '[Logger.getLog] First argument $domain must not be '
                . "a valid domain name ('$domain' is invalid");
        }
        
        $domainName =
            is_object($domain)
            ? get_class($domain)
            : $domain;
        
        if (self::$adapter === null) {
            self::$adapter = new NullLoggerAdapter();
        }
        
        return self::$adapter->getLog($domainName);
    }
    
    static function getNullLog() {
        if (self::$nullLog === null) {
            self::$nullLog = new NullLog(); 
        }
        
        return self::$nullLog;
    }
    
    static function setDefaultLogThreshold($level) {
        self::$defaultLogThreshold = $level;
    }
    
    static function getDefaultLogThreshold() {
        return self::$defaultLogThreshold; 
    }
    
    static function getLogThresholdByDomain($domain) {
        return
            self::adapter === null
            ? Log::NONE
            : self::$adapter->getLogThresholdByDomain($domain);
    }
    
    static function setAdapter(LoggerAdapter $adapter) {
        self::$adapter = $adapter;
    }
    
    static function getAdatper() {
        return self::$adapter;
    }
}
