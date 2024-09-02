<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'modulesSystem/config.php';






$test=Test::singleton();
$data=$test->run( Test::BOTH );

echo '<h2>Estas son las variables iniciales </h2>';
print_r($test).'<br>';

echo '<h1>La distancia desde el ayuntammiento de Vigo hasta el navegador del cliente es de '. round( $data['distance'],2 ) . ' km y el coste de envío es de ' . round( $data['cost'],2 ) . ' €</h1>';

echo '<h2>Estas son las variables resultantes </h2>';
print_r($data);