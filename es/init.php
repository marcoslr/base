<?php

//app trigger file
define( 'BASE_PATH' , dirname(dirname(__FILE__)). DIRECTORY_SEPARATOR );//to prevent direct access to other files from external
define( 'DS' , DIRECTORY_SEPARATOR );
require_once BASE_PATH . DS . 'principal'. DS . 'Base.php';

Base::getInstance();

echo '¿Podremos empezar por aquí?';

