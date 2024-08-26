<?php

//app trigger file
define('BASE_PATH', dirname(dirname(__FILE__)). DIRECTORY_SEPARATOR );//to prevent direct access to other files from external
include BASE_PATH.DIRECTORY_SEPARATOR.'principal'.DIRECTORY_SEPARATOR.'Base.php';

Core::singleton();

echo '¿Podremos empezar por aquí?';

