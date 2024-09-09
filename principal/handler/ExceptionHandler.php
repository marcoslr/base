<?php

class ExceptionHandler
{
    protected $handler;

    public function __construct($handler)
    {
        $this->handler = $handler;
    }

    public function handle($e)
    {
        $this->handler->handle( $e->getCode(),$e->getMessage(),$e->getFile(),$e->getLine(), array( $e->getPrevious(), $e->getTraceAsString() ) );
        
        return TRUE;

    }
}

