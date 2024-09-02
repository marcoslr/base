<?php

//tests
class Test extends Loader implements I_Module{

    protected $shippingCostCalculator;
    protected $coordinatesCalculator;
    protected $originCoordinates;
    protected $shippingAddedCostCalculator;
            
    function __construct() {
        
        // Creamos objecto con parámetros de consumo y costo de combustible
        // Litros por kilómetro (ejemplo)
        // Costo del gasoil por litro (ejemplo)
        $this->shippingCostCalculator=new ShippingCostCalculator( 0.08, 1.50 );
        //factor de diferencia de distancia entre línea recta y ruta, diferencia de distancia: 1,25=100/80
        //doble distancia, ida y vuelta
        $this->shippingAddedCostCalculator=new ShippingAddedCostCalculator( 1.25 , 2 , $this->shippingCostCalculator );
        
        
        // Obtener la IP del cliente

        
        //$this->distanceCalculator=new DistanceCalculator( $_SERVER['REMOTE_ADDR'] );
        //
        //
        //test en localhost
        $this->coordinatesCalculator=new CoordinatesCalculator( '92.59.140.13' );
        //
        
        // Coordenadas de origen (manuales)
        $this->originCoordinates = [
            CoordinatesCalculator::LATITUDE => 42.2313564,  // Ejemplo: Vigo
            CoordinatesCalculator::LONGITUDE => -8.7124471
        ];
        
    }
    
    public function run( $selector = NULL) {
        
        switch( $selector ){
  
            case self::DISTANCE:
                
                //$distanceCostCalculator=new DistanceCostCalculator( $this->shippingCostCalculator, $this->coordinatesCalculator );
                $distanceCostCalculator=new DistanceCostCalculator( $this->shippingAddedCostCalculator, $this->coordinatesCalculator );
                
                return $distanceCostCalculator[ self::DISTANCE ];
                
            
            case self::COST;
                
                //$distanceCostCalculator=new DistanceCostCalculator( $this->shippingCostCalculator, $this->coordinatesCalculator );
                
                $distanceCostCalculator=new DistanceCostCalculator( $this->shippingAddedCostCalculator, $this->coordinatesCalculator );
            
                return $distanceCostCalculator[ self::COST ];

            
            case self::COORDINATES;
            
                return $this->coordinatesCalculator->run();

            
            case self::BOTH:
                
                //$distanceCostCalculator=new DistanceCostCalculator( $this->shippingCostCalculator, $this->coordinatesCalculator );
                
                $distanceCostCalculator=new DistanceCostCalculator( $this->shippingAddedCostCalculator, $this->coordinatesCalculator );
                
                $distanceCost=$distanceCostCalculator->run( $this->originCoordinates );
                
                $coordinates=$this->coordinatesCalculator->run();
                
                return array(
                       
                        self::DISTANCE => $distanceCost[ self::DISTANCE ],
                        self::COST => $distanceCost[ self::COST ],
                        self::COORDINATES => $coordinates,
                        
                );
            
        }
        
        return FALSE;
        
    }
}
