<?php

namespace logging;

Interface LoggerAdapter {
    function getLog($name);
    
    function getThresholdByLogName($name);
}