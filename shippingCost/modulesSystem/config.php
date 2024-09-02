<?php

define( 'ROOT_PATH' , dirname(__DIR__) );
define( 'MOD_FOLD_NAME' ,  'modules' );
define( 'MOD_FOLD_TEST_PATH', MOD_FOLD_NAME.DIRECTORY_SEPARATOR.'test' );
define( 'MOD_SYS_FOLD_NAME' ,  'modulesSystem' );

define( 
        'FOLD_WH_REQU_A' , 
        [
            
            MOD_SYS_FOLD_NAME,
            MOD_FOLD_NAME ,
            MOD_FOLD_TEST_PATH
            
        ] 
);



require_once 'Loader.php';
    
 Loader::autoloader();

