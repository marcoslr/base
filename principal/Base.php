<?php

spl_autoload_register('Base::autoload');

        
if( !defined('BASE_PATH') )
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    
    exit( 'Acceso directo no permitido a este directorio desde la web.' );
}
else
{
    
    set_error_handler('Base::errorHandler');//depends on Log object and config ini file
    set_exception_handler('Base::exceptionHandler');//depends on Log object and config ini file
}

class Base{

    /**
     * this registry singleton instance
     * @access private
     */
    
    protected static $instances=array();
    
    
    
    protected function __construct( $param=NULL ) {
        
    }

    
    //Desing pattern methods
    
    /**
     * singleton access to a this object
     * @access public
     * @return
     */
    
    public static function getInstance()
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
     * @param string $typeMsg it will be one of the control constant  of this class, to know what king of error we are dealing with
     * @param string $message the content of the  error/exception
     * @param string $rawdata the raw content of the error/exception
     * @return string the the complete message logged
     * @throws \InvalidArgumentException if bad paremeters were passed (string in $typeMsg, string in $message, string in $rawdata)
     */
    protected static function log( $typeMsg, $message, $rawdata= ''){
        
    }
    
    /**
     * manager for the set_error_handler function although it can be called from external
     * 
     * @param string $errno error code
     * @param string $errstr error message
     * @param string $errfile absolute filename where error became
     * @param string $errline number of line in the file where script stopped
     * @param array $errcontext it can be info about the trace or the previous error
     * @return boolean for a complete or not bypass of PHP engine errors
     * 
     */
    protected static function errorHandler($errno ,$errstr , $errfile , $errline , array $errcontext)
    {
        
        self::log($typeMsg,$errstr,$rawdata);

    }
    
    /**
     *  manager for the set_exception_handler function although it can be called from external
     * 
     * @param \Exception $e the original exception info
     * @return void
     */
    protected static function exceptionHandler($e)
    {
        
    }
    /**
     * 
     * @param type Base constant
     * @return string the html response or empty string
     */
    
        //MAGIC METHODS
    
    protected static function autoload($className)
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

    public function __clone()
    {
        trigger_error( 'The clonation of this object is forbidden.', E_USER_ERROR );
    }

    // 'magic'
    
    public function __get($name) {
        return $this->{(string)$name};
    }

    public static function __callStatic($name, $arguments) {
        return call_user_func_array(array(get_called_class(), $name),$arguments);
    }
}