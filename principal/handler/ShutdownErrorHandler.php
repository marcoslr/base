<?php

class ShutdownErrorHandler{
    
    protected $handler;

    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    public function handle()
    {
        $e = error_get_last();
        if( $e ){
            $this->handler->handle( $e['type'], $e['message'], $e['file'], $e['line'] );
        }
        
        return TRUE;

    }
    
}
