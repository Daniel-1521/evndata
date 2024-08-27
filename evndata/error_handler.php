<?php
function custom_error_handler($errno, $errstr, $errfile, $errline)
{
    $message = date("Y-m-d H:i:s") . " - Error: [$errno] $errstr in $errfile on line $errline\n";
    error_log($message, 3, "app_errors.log");

    if (ini_get("display_errors")) {
        printf("<pre>Error: %s\nFile: %s\nLine: %d</pre>", $errstr, $errfile, $errline);
    } else {
        echo "An error occurred. Please try again later.";
    }
}

set_error_handler("custom_error_handler");

function exception_handler($exception)
{
    $message = date("Y-m-d H:i:s") . " - Exception: " . $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine() . "\n";
    error_log($message, 3, "app_exceptions.log");

    if (ini_get("display_errors")) {
        printf("<pre>Exception: %s\nFile: %s\nLine: %d</pre>", $exception->getMessage(), $exception->getFile(), $exception->getLine());
    } else {
        echo "An error occurred. Please try again later.";
    }
}

set_exception_handler("exception_handler");
