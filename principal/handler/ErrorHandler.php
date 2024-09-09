<?php

class ErrorHandler
{
    protected $logger;

    public function __construct(LogHandler $logger)
    {
        $this->logger = $logger;
    }
    
    /**
     * Ideal manager for the set_error_handler function although it can be called from external
     * 
     * @param string $errno error code
     * @param string $errstr error message
     * @param string $errfile absolute filename where error became
     * @param string $errline number of line in the file where script stopped
     * @param array $errcontext it can be info about the trace or the previous error
     * @return boolean for a complete or not bypass of PHP engine errors
     * 
     */
    public function handle($errno ,$errstr , $errfile , $errline , array $errcontext=NULL)
    {
        $code=$errno;
        // Se puede personalizar según el tipo de error
        switch($errno){
            default:                    
                $errno="EXCEPTION\nDescription: if no codified by PHP Error level constants, it is an Exception by the php script.";
                $typeMsg='EXCEPTION';
                break;
            case E_ALL:                 $typeMsg='E_ALL';$errno="E_ALL (code $code)\nDescription: All errors and warnings (includes E_STRICT as of PHP 6.0.0)";break;
            case E_ERROR:               $typeMsg='E_ERROR';$errno="E_ERROR (code $code)\nDescription: fatal run-time errors";break;
            case E_RECOVERABLE_ERROR:   $typeMsg='E_RECOVERABLE_ERROR';$errno="E_RECOVERABLE_ERROR (code $code)\nDescription: almost fatal run-time errors";break;
            case E_WARNING:             $typeMsg='E_WARNING';$errno="E_WARNING (code $code)\nDescription: run-time warnings (non-fatal errors)";break;
            case E_PARSE:               $typeMsg='E_PARSE';$errno="E_PARSE (code $code)\nDescription: compile-time parse errors";break;
            case E_NOTICE:              $typeMsg='E_NOTICE';$errno="E_NOTICE (code $code)\nDescription: run-time notices (these are warnings which often result from a bug in your code, but it's possible that it was intentional (e.g., using an uninitialized variable and relying on the fact it's automatically initialized to an empty string)";break;
            case E_STRICT:              $typeMsg='E_STRICT';$errno="E_STRICT (code $code)\nDescription: run-time notices, enable to have PHP suggest changes to your code which will ensure the best interoperability and forward compatibility of your code";break;
            case E_CORE_ERROR:          $typeMsg='E_CORE_ERROR';$errno="E_CORE_ERROR (code $code)\nDescription: fatal errors that occur during PHP's initial startup";break;
            case E_CORE_WARNING:        $typeMsg='E_CORE_WARNING';$errno="E_CORE_WARNING (code $code)\nDescription: warnings (non-fatal errors) that occur during PHP's initial startup";break;
            case E_COMPILE_ERROR:       $typeMsg='E_COMPILE_ERROR';$errno="E_COMPILE_ERROR (code $code)\nDescription: fatal compile-time errors";break;
            case E_COMPILE_WARNING:     $typeMsg='E_COMPILE_WARNING';$errno="E_COMPILE_WARNING (code $code)\nDescription: compile-time warnings (non-fatal errors)";break;
            case E_USER_ERROR:          $typeMsg='E_USER_ERROR';$errno="E_USER_ERROR (code $code)\nDescription: user-generated error message";break;
            case E_USER_WARNING:        $typeMsg='E_USER_WARNING';$errno="E_USER_WARNING (code $code)\nDescription: user-generated warning message";break;
            case E_USER_NOTICE:         $typeMsg='E_USER_NOTICE';$errno="E_USER_NOTICE (code $code)\nDescription:  user-generated notice message";break;
            case E_DEPRECATED:          $typeMsg='E_DEPRECATED';$errno="E_DEPRECATED (code $code)\nDescription: warn about code that will not work in future versions of PHP";break;
            
        }
        
        $rawdata="TYPE OF ERROR: $errno\nFILE: $errfile\nLINE: $errline\nEXCEPTION CONTEXT VARS: ".  print_r( $errcontext, TRUE );
        
        $publicnumber=rand();
        $message=strtoupper($typeMsg) . ' - ' . $errstr . ', with public number: '. $publicnumber . ' ' . ( $rawdata ? "\n".$rawdata : '' );
        
        $this->logger->log($message);
        
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
        exit( '<html><head><title>Internal Server Error</title></head><body><h1>Internal Server Error</h1><p>Log error Identificator Number: <pre><strong>'.$publicnumber.'</strong></pre></p></body></html>' );
        
        return TRUE; // Evita que PHP maneje el error por defecto en el caso de que llegase hasta aqui en algun momento
    }
    
    
}

