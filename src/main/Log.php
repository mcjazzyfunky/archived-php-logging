<?php

namespace logging;

use Throwable;

interface Log {
    const TRACE = 1;
    const DEBUG = 2;
    const INFO = 3;
    const NOTICE = 4;
    const WARN = 5;
    const ERROR = 6;
    const CRITICAL = 7;
    const ALERT = 8;
    const EMERGENCY = 9;
    const NONE = 10;
    
    function log($level, $message, $context = null);
    
    function isEnabled($level);
    
    function trace($message, $context = null);
    
    function debug($message, $context = null);
    
    function info($message, $context = null);
    
    function notice($message, $context = null);  
    
    function warn($message, $context = null);
    
    function error($message, $context = null);
    
    function critical($message, $context = null);
    
    function alert($message, $context = null);
    
    function emergency($message, $context = null);

    function isTraceEnabled();

    function isDebugEnabled();

    function isInfoEnabled();

    function isNoticeEnabled();

    function isWarnEnabled();

    function isErrorEnabled();

    function isCriticalEnabled();

    function isAlertEnabled();

    function isEmergencyEnabled();
}
