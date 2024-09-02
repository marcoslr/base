<?php

interface I_Coordinates extends I_Module{
    
    const LATITUDE='lat';
    const LONGITUDE='lon';
    
    public function validateCoordinates($coordinates);
    
}

