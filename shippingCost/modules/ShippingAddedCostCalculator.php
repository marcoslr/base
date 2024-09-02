<?php

if( !defined('ROOT_PATH') )   die('Forbbiden direct access to this file');

class ShippingAddedCostCalculator implements I_Cost {
    
    protected $distanaceFactorNotDirectRoute;
    protected $distanceFactorGoneBack;
    protected $calculator;
    
    function __construct( $distanaceFactorNotDirectRoute , $distanceFactorGoneBack, ShippingCostCalculator $calculator=NULL) {
        $this->distanaceFactorNotDirectRoute=floatval( $distanaceFactorNotDirectRoute );
        $this->distanceFactorGoneBack=floatval( $distanceFactorGoneBack );
        $this->calculator=$calculator;
    }
    
    public function run( $distance=NULL ) {
        $distance=floatval($distance);
        if( $this->calculator ){
            return $this->calculator->run($distance) *
                   $this->distanaceFactorNotDirectRoute *
                   $this->distanceFactorGoneBack;
        }
        
        return $this->calculator($distance);
    }
}

