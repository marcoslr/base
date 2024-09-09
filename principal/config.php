<?php

return [
    "error_handling" => 
    [
        "display_errors" => true,
        "log_errors" => true,
        "error_reporting_level" => E_ALL & ~E_NOTICE & ~E_DEPRECATED,
        "log_file_path" => "logs",
        "log_file_name" => "errors.log",
        "environment" => "development",  // o 'production'
    ],
];
