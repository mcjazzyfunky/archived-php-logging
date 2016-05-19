<?php

namespace logging;

Interface LoggerAdapter {
    function getLog($domain);
    
    function getLogThresholdByDomain($domain);
}