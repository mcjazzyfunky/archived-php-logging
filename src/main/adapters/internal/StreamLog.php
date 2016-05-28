<?php

namespace logging\adapters\internal;

use Exception;
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
    
    function log($level, $message, $context = null) {
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
                $text = LogUtils::formatLogMessage($message, $context); 
                $name = $this->name;
                
                if ($this->logMessageFormatter !== null) {
                    $formatter = $this->logMessageFormatter;
                    
                    $output = $formatter([
                        'date' => $date,
                        'name' => $name,
                        'level' => $levelName,
                        'code' => $level,
                        'message' => $text,
                        'context' => $context
                    ]);
                } else {
                    $output = "[$date] [$levelName] [$name] $text\n";
                    
                    $cause = null;
                        
                    if ($context instanceof Throwable || $context instanceof Exception) {
                        $cause = $context;
                    } else if (is_array($context)) {
                        $cause = @$context['cause'];
                        
                        if ($cause instanceof Throwable || $cause instanceof Exception) {
                            $context['cause'] = $cause->getMessage();
                        } else {
                            $cause = null;
                            $exception = @$context['exception'];   
    
                            if ($exception instanceof Throwable || $exception instanceof Exception) {
                                $cause = $exception;
                                $context['exception'] = $cause->getMessage();
                            }
                        }
                    }
                    
                    if (is_array($context)
                        && (count($context) > 0)
                        && (count($context) > 1 || $cause === null)) {
                        
                        $showContext = false; 
                        
                        if (!is_string($message)) {
                            $showContext = true;
                        } else {
                            foreach ($context as $key => $value) {
                                if (!is_scalar($value) || strpos($message, '{' . $key . '}') === false) {
                                    $showContext = true;
                                    break;
                                }
                            }
                        }
                        
                        if ($showContext) {
                            $output .= "---- Context ----\n";
                            
                            foreach ($context as $key => $value) {
                                if ($value instanceof Exception || $value instanceof Throwable) {
                                    $value = $value->getMessage();
                                }
                                
                                if (!is_string($value)) {
                                    $value = json_encode($value);
                                }
                                
                                $value = strtr($value, [
                                    "\r\n" => ' ',
                                    "\n" => ' ',
                                    "\r" => ' '
                                ]);
                                
                                $output .= "$key: $value";
                                $output .= "\n";
                            }
                        }
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
