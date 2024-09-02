<?php

if( !defined('ROOT_PATH') )   die('Forbbiden direct access to this file');

class ShippingCostCalculator implements I_Cost {
    
    private $fuelConsumption;
    private $fuelCostPerLiter;

    public function __construct( $fuelConsumption, $fuelCostPerLiter) {
        $this->fuelConsumption = floatval( $fuelConsumption );
        $this->fuelCostPerLiter = floatval( $fuelCostPerLiter );
    }

    public function run( $distance=NULL ) {
        $distance=floatval($distance);
        $totalFuelUsed = $distance * $this->fuelConsumption; // Litros de gasoil usados
        $totalCost = $totalFuelUsed * $this->fuelCostPerLiter; // Coste total

        return $totalCost;
    }
    
}
