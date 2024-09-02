<?php

interface I_DistanceCost extends I_Module, I_Coordinates{
    
    function __construct( I_Cost $costCalculator, I_Coordinates $coordinatesCalculator  );
    
}
