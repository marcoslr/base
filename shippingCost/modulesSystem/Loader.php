<?php

class Loader{
    
    const TEST='Test';
    protected static $registry=array();
    
    function __construct() {
        self::$registry=new stdClass();
        //tests
        self::$registry[ self::TEST ]->test=new Test();
        //
    }

    protected static function singleton()
    {
        
        $obj = get_called_class();
        
        if( in_array( $obj, [ self::TEST ] ) ){
            
            if( !isset( self::$registry[$obj] ) )
            {
                self::$registry[$obj] = new $obj;
            }

            return self::$registry[$obj];
            
        }
        return NULL;
    }
    
    protected static function autoloader(){
        
        spl_autoload_register(
                
            function($className){
             
            if( is_string($className) ){

                $foldersWhereRequire=FOLD_WH_REQU_A;
                
                $filePath=ROOT_PATH.DIRECTORY_SEPARATOR;

                for($i=0;$i<count($foldersWhereRequire);$i++){

                    $fileName= $filePath . $foldersWhereRequire[$i].DIRECTORY_SEPARATOR . $className.'.php';
                    if( file_exists($fileName) ){
                        require_once $fileName;
                        return TRUE;
                    }

                }
            }

            throw new RuntimeException();
        
            });
        
    }
    
    /**
     * Avoid this object clonation: issues an E_USER_ERROR if this is attempted
     * @access public
     * @return
     */

    protected function __clone()
    {
        trigger_error( 'The clonation of this object is forbidden.', E_USER_ERROR );
    }
    
    public function __set($name, $value) {
       $this->{(string)$name}=$value;
       
       return $this;
    }
    
    public function __get($name) {
        return $this->{(string)$name};
    }
    
    protected function _add($name,$value){
        $name=(string)$name;
        if(in_array($name, [self::ERROR,self::WARNING,self::EXCEPTION,self::ALERT] )){
            
            $this->$name[]=$value;
        }
        
        
    }
    
    public static function __callStatic($name, $arguments) {
        return call_user_func_array(array(get_called_class(), $name),$arguments);
    }

    public function __call($name, $arguments) {
        return call_user_func_array(array(get_called_class(), $name),$arguments);
    }

}

