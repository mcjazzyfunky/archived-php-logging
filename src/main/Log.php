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
    
    function log($level, $message, $context = null, $cause, $extra = null);
    
    function isEnabled($level);
    
    function trace($message, $context = null, $cause = null, $extra = null);
    
    function debug($message, $context = null, $cause = null, $extra = null);
    
    function info($message, $context = null, $cause = null, $extra = null);
    
    function notice($message, $context = null, $cause = null, $extra = null);  
    
    function warn($message, $context = null, $cause = null, $extra = null);
    
    function error($mssage, $context = null, $cause = null, $extra = null);
    
    function critical($message, $context = null, $cause = null, $extra = null);
    
    function alert($message, $context = null, $cause = null, $extra = null);
    
    function emergency($message = null, $context = null, $cause = null, $extra = null);

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
