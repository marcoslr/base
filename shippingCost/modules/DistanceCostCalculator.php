<?php

if( !defined('ROOT_PATH') )   die('Forbbiden direct access to this file');
//Decorator Class for DistanceCalculator and ShippingCostCalculator
class DistanceCostCalculator implements I_DistanceCost {
    
    const DISTANCE='distance';
    const COST='cost';
    
    protected $coordinatesCalculator;
    protected $costCalculator;
    
    function __construct(I_Cost $costCalculator, I_Coordinates $coordinatesCalculator  ) {
        
        $this->coordinatesCalculator=$coordinatesCalculator;
        $this->costCalculator=$costCalculator;
        
    }
    
    //harvestine stuff
    public static function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        $earthRadius = 6371; // Radio de la Tierra en kilómetros

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        return $distance; // Distancia en kilómetros
    }
    
    public function validateCoordinates($coordinates){
        return is_array($coordinates) && count($coordinates)>0 && isset($coordinates[ self::LATITUDE ]) && isset($coordinates[ self::LONGITUDE ]);
    }
    
    public function run( $originCoordinates=array() ){

        // Ejemplo de uso
      
        if($this->validateCoordinates( $originCoordinates ) ){
            
            

            // Instanciar GeoLocation y obtener coordenadas de destino
            $geoLocation = $this->coordinatesCalculator;
            $destinationCoordinates = $geoLocation->run(  );
            
            if( $this->validateCoordinates($destinationCoordinates) ){
            
                // Calcular la distancia
                $distance = self::calculateDistance(
                    $originCoordinates[ self::LATITUDE ],
                    $originCoordinates[ self::LONGITUDE ],
                    $destinationCoordinates[ self::LATITUDE ],
                    $destinationCoordinates[ self::LONGITUDE ]
                );

                // Calcular el costo de envío
                $shippingCostCalculator = $this->costCalculator;
                $shippingCost = $shippingCostCalculator->run($distance);

                $return =  [ 
                                self::DISTANCE => $distance,
                                self::COST => $shippingCost,
                            ];


                return $return;
            
            }
            
        }

        
        return [ 
                    self::DISTANCE => 0,
                    self::COST => 0,
                ];
        
    }
}
