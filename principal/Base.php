<?php

spl_autoload_register('Base::autoload');
        
if( !defined('BASE_PATH') )
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    
    exit( Base::getHtmlResponse( Base::FORBIDDEN ) );
}

abstract class Base{
    
    const FORBIDDEN='forbidden';
    
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
        
            $html[self::FORBIDDEN]='<html><head><title>Forbidden access</title></head><body><h1>Forbidden access</h1><p>Access to the requested URL '.str_replace(['public','index.php'],['',''],$_SERVER['PHP_SELF']).' was forbidden on this server.</p></body></html>';
        
        }
        
        $type=(string)$type;
        
        if( isset($html[$type]) ){
            return $html[$type];
        }
        
        return '';
    }
    
    static function autoload($className){
        $fileName=dirname(__FILE__).DIRECTORY_SEPARATOR.$className.'.php';
        if(  !is_string($className) || !file_exists($fileName) )   throw new RuntimeException();
        require_once $fileName;
    }
    
}