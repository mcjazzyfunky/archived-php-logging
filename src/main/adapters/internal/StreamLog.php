<?php

namespace logging\adapters\internal;

use InvalidArgumentException;
use Throwable;
use logging\AbstractLog;
use logging\Log;
use logging\Logger;
use logging\LogUtils;

class StreamLog extends AbstractLog {
    private $stream;

    function __construct($name, $stream, callable $logMessageFormatter = null) {
        $this->name = $name;
        $this->stream = $stream;
        $this->logMessageFormatter = $logMessageFormatter;
    }
    
    function log($level, $message, $args = null, $cause = null, $extra = null) {
        if ($level !== LOG::NONE) {
            if (!LogUtils::isValidLogLevel($level, true)) {
                throw new InvalidArgumentException(
                    '[StreamLog::log] First argument $level must be a '
                    . 'valid log level');
            }
            
            if ($this->isEnabled($level)) {
                $output = null;
                $date = date ('Y-m-d H:i:s');
                $levelName = LogUtils::getLogLevelName($level);
                $text = LogUtils::formatLogMessage($message, $args); 
                $name = $this->name;
                
                if ($this->logMessageFormatter !== null) {
                    $formatter = $this->logMessageFormatter;
                    
                    $output = $formatter([
                        'date' => $date,
                        'level' => $levelName,
                        'levelNo' => $level,
                        'message' => $text,
                        'cause' => $cause,
                        'extra' => $extra,
                        'logName' => $name
                    ]);
                } else {
                    $output = "[$date] [$levelName] [$name] $text\n";
                    
                    if ($extra !== null) {
                        $output .= "---- Extra ----\n";
                        $output .= trim(print_r($extra, true));
                        $output .= "\n";
                    }
                    
                    if ($cause !== null) {
                        $output .= "---- Cause ----";
                        $output .= "\nClass: ";
                        $output .= get_class($cause);
                        $output .= "\nMessage: ";
                        $output .= $cause->getMessage();
                        $output .= "\nCode: ";
                        $output .= $cause->getCode();
                        $output .= "\nFile: ";
                        $output .= $cause->getFile();
                        $output .= "\nLine: ";
                        $output .= $cause->getLine();
                        $output .= "\nStack trace:\n";
                        $output .= $cause->getTraceAsString();
                        $output .= "\n";
                    }
                }
                
                fputs($this->stream, $output);
                fflush($this->stream);
            }
        }
    }
    
    function isEnabled($level) {
        return $level >= Logger::getDefaultThreshold();
    }
}
