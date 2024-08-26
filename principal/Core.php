<?php

if( !defined('BASE_PATH') )
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    
    exit( Base::getHtmlResponse( Base::FORBIDDEN ) );
    
}

final class Core extends Base{
    
    //CONSTANTS
    
    const CONFIG_FILE_EXTENSION='ini';
    const CONFIG_FILE_NAME='config';
    
    //OBJECT VARIABLES
    
    /**
     * registered objects collection
     * @access protected
     */
    
    protected $objects = array();
    
   
    
    
    
}

