<?php

namespace logging;

Interface LogAdapter {
    function getLog($name);
    
    function getThresholdByLogName($name);
}