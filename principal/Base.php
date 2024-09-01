<?php

spl_autoload_register('Base::autoload');
        
if( !defined('BASE_PATH') )
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    
    exit( Base::getHtmlResponse( Base::FORBIDDEN ) );
}
else
{
    set_error_handler('Base::errorHandler');//depends on Log object and config ini file
    set_exception_handler('Base::exceptionHandler');//depends on Log object and config ini file
}

abstract class Base{
    
    const ERROR='errors';
    const WARNING='warnings';
    const EXCEPTION='exceptions';
    const ALERT='alerts';
    
    const FORBIDDEN='forbidden';
    
    private $alerts;//to show on the site web
    private $errors;
    private $warnings;
    private $exceptions;
   
    
    /**
     * inmediate and basic html response
     * @access private
     */
    
    protected static $htmlResponse=array();

    /**
     * this registry singleton instance
     * @access private
     */
    
    protected static $instances=array();
    
    /**
     * protected constructor to avoid directly instantiaton
     * @access private
     */
    
    protected function __construct() 
    {
        $this->alerts=array();
        $this->errors=array();
        $this->warnings=array();
        $this->exceptions=array();
        

    }
    
    //Desing pattern methods
    
    /**
     * Avoid this object clonation: issues an E_USER_ERROR if this is attempted
     * @access public
     * @return
     */

    protected function __clone()
    {
        trigger_error( 'The clonation of this object is forbidden.', E_USER_ERROR );
    }
    
    /**
     * singleton access to a this object
     * @access public
     * @return
     */
    
    public static function singleton()
    {
        $obj = get_called_class();
        if( !isset( self::$instances[$obj] ) )
        {
            self::$instances[$obj] = new $obj;
        }
 
        return self::$instances[$obj];
    }
    
    public static function cleanSingleton(){
        $obj = get_called_class();
        if( isset(self::$instances[$obj]) )
        {
            unset( self::$instances[$obj] );
        }
    }
    
    //Utilities methods
    
    /**
     * 
     * @param type Base constant
     * @return string the html response or empty string
     */

    public static function getHtmlResponse($type)
    {
        $html=&self::$htmlResponse;
        
        if( count($html)==0 )
        {
        
            $html[self::FORBIDDEN]='<html><head><title>Forbidden access</title></head><body><h1>Forbidden access</h1><p>Access to the requested URL '.str_replace(['public/','index.php'],['',''],$_SERVER['PHP_SELF']).' was forbidden on this server.</p></body></html>';
        
            
            
        }
        
        $type=(string)$type;
        
        if( isset($html[$type]) ){
            return $html[$type];
        }
        
        return '';
    }
    
    //One use utilities
    
    private static function autoload($className)
    {
        $fileName=dirname(__FILE__).DIRECTORY_SEPARATOR.$className.'.php';
        if(  !is_string($className) || !file_exists($fileName) )   throw new RuntimeException();
        require_once $fileName;
    }
    
    /**
     * 
     * @param string $typeMsg it will be one of the control constant  of this class, to know what king of error we are dealing with
     * @param string $message the content of the  error/exception
     * @param string $rawdata the raw content of the error/exception
     * @return string the the complete message logged
     * @throws \InvalidArgumentException if bad paremeters were passed (string in $typeMsg, string in $message, string in $rawdata)
     */
    private static function log( $typeMsg, $message, $rawdata= ''){
        $publicnumber=rand();        
        self::$log->write( strtoupper($typeMsg). ' - ' .$message.' with public number: '.$publicnumber.' '.( $rawdata ? "\n".$rawdata : '' ) );
        header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error');
        exit( '<html><head><title>Internal Server Error</title></head><body><h1>Internal Server Error</h1><p>Log error Identificator Number: '.$publicnumber.'</p></body></html>' );
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
    private static function errorHandler($errno ,$errstr , $errfile , $errline , array $errcontext)
    {
        $code=$errno;
        switch($errno){
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
            default:                    
                $errno="EXCEPTION\nDescription: if no codified by PHP Error level constants, it is an Exception by the php script.";
                $typeMsg='EXCEPTION';
                break;
        }
        
        $rawdata="TYPE OF ERROR: $errno\nFILE: $errfile\nLINE: $errline\nCONTEXT VARS: ".  json_encode($errcontext,JSON_PRETTY_PRINT);
        self::log($typeMsg,$errstr,$rawdata);
        exit;
        return TRUE;
    }
    
    /**
     * Ideal manager for the set_exception_handler function although it can be called from external
     * 
     * @param \Exception $e the original exception info
     * @return void
     */
    private static function exceptionHandler($e)
    {
        self::errorHandler( $e->getCode(),$e->getMessage(),$e->getFile(),$e->getLine(), array( $e->getPrevious(), $e->getTraceAsString() ) );
    }
    
    
    
    //MAGIC METHODS
    
    public static function __callStatic($name, $arguments) {
        return call_user_func_array(array(get_called_class(), $name),$arguments);
    }

    public function __call($name, $arguments) {
        return call_user_func_array(array(get_called_class(), $name),$arguments);
    }
    
}