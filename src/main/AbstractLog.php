<?php

namespace logging;

use Exception;
use InvalidArgumentException;
use Throwable;

abstract class AbstractLog implements Log {
    abstract function log($level, $message, $context = null, $cause = null, $extra = null);
    
    abstract function isEnabled($level);
    
    function trace($message, $context = null, $cause = null, $extra = null) {
        if ($cause !== null
            && !($cause instanceof Exception) 
            && !($cause instanceof Throwable)) {
         
            throw new InvalidArgumentException(
                '[AbstractLog#trace] Third argument $cause must be a '
                . 'Throwable/Exception or null');
        }
        
        $this->log(Log::TRACE, $message, $context, $extra);   
    }
    
    function debug($message, $context = null, $cause = null, $extra = null) {
        if ($cause !== null
            && !($cause instanceof Exception) 
            && !($cause instanceof Throwable)) {
         
            throw new InvalidArgumentException(
                '[AbstractLog#debug] Third argument must be a '
                . 'Throwable/Exception or null');
        }

        $this->log(Log::DEBUG, $message, $context, $cause, $extra);   
    }
    
    function info($message, $context = null, $cause = null, $extra = null) {
        if ($cause !== null
            && !($cause instanceof Exception) 
            && !($cause instanceof Throwable)) {
         
            throw new InvalidArgumentException(
                '[AbstractLog#info] Third argument $cause must be a '
                . 'Throwable/Exception or null');
        }
        
        $this->log(Log::INFO, $message, $context, $cause, $extra);   
    }
    
    function notice($message, $context = null, $cause = null, $extra = null) {
        if ($cause !== null
            && !($cause instanceof Exception) 
            && !($cause instanceof Throwable)) {
         
            throw new InvalidArgumentException(
                '[AbstractLog#notice] Third argument must be a '
                . 'Throwable/Exception or null');
        }

        $this->log(Log::TRACE, $message, $context, $cause, $extra);   
    }
    
    function warn($message, $context = null, $cause = null, $extra = null) {
        if ($cause !== null
            && !($cause instanceof Exception) 
            && !($cause instanceof Throwable)) {
         
            throw new InvalidArgumentException(
                '[AbstractLog#warn] Third argument $cause must be a '
                . 'Throwable/Exception or null');
        }

        $this->log(Log::WARN, $message, $context, $cause, $extra);
    }
    
    function error($message, $context = null, $cause = null, $extra = null) {
        if ($cause !== null
            && !($cause instanceof Exception) 
            && !($cause instanceof Throwable)) {
         
            throw new InvalidArgumentException(
                '[AbstractLog#error] Third argument $cause must be a '
                . 'Throwable/Exception or null');
        }

        $this->log(Log::ERROR, $message, $context, $cause, $extra);
    }
    
    function critical($message, $context = null, $cause = null, $extra = null) {
        if ($cause !== null
            && !($cause instanceof Exception) 
            && !($cause instanceof Throwable)) {
         
            throw new InvalidArgumentException(
                '[AbstractLog#critical] Third argument $cause must be a '
                . 'Throwable/Exception or null');
        }

        $this->log(Log::CRITICAL, $message, $context, $cause, $extra);
    }
    
    function alert($message, $context = null, $cause = null, $extra = null) {
        if ($cause !== null
            && !($cause instanceof Exception) 
            && !($cause instanceof Throwable)) {
         
            throw new InvalidArgumentException(
                '[AbstractLog#alert] Third argument $cause must be a '
                . 'Throwable/Exception or null');
        }

        $this->log(Log::ALERT, $message, $context, $extra);
    }
    
    function emergency($message = null, $context = null, $cause = null, $extra = null) {
        if ($cause !== null
            && !($cause instanceof Exception) 
            && !($cause instanceof Throwable)) {
         
            throw new InvalidArgumentException(
                '[AbstractLog#emergency] Third argument $cause must be a '
                . 'Throwable/Exception or null');
        }

        $this->log(Log::EMERGENCY, $message, $context, $cause, $extra);
    }

    function isTraceEnabled() {
        return $this->isEnabled(Log::TRACE);   
    }

    function isDebugEnabled() {
        return $this->isEnabled(Log::DEBUG);
    }

    function isInfoEnabled() {
        return $this->isEnabled(Log::INFO);
    }

    function isNoticeEnabled() {
        return $this->isEnabled(Log::NOTICE);
    }

    function isWarnEnabled() {
        return $this->isEnabled(Log::WARN);
    }

    function isErrorEnabled() {
        return $this->isEnabled(Log::ERROR);
    }

    function isCriticalEnabled() {
        return $this->isEnabled(Log::CRITICAL);
    }

    function isAlertEnabled() {
        return $this->isEnabled(Log::ALERT);
    }

    function isEmergencyEnabled() {
        return $this->isEnabled(Log::EMERGENCY);
    }
}
