<?php

if( !defined('ROOT_PATH') )   die('Forbbiden direct access to this file');

interface I_Module{
    
    const DISTANCE='distance';
    const COST='cost';
    const COORDINATES='coordinates';
    const BOTH='both';
    
    public function run( $param=NULL );
}

