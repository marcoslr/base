<?php

if( !defined('ROOT_PATH') )   die('Forbbiden direct access to this file');

class CoordinatesCalculator implements I_Coordinates{

    protected $ip;

    public function __construct( $ip=NULL ) {
        if( !$ip ) $ip=self::realIp ();
        $this->ip=(string)$ip;
    }
    
    public static function realIp(){
        //debug: return '92.59.140.13';
        $ip = $_SERVER['REMOTE_ADDR'];
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && preg_match_all('#\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}#s', $_SERVER['HTTP_X_FORWARDED_FOR'], $matches)) {
            foreach ($matches[0] AS $xip) {
                if (!preg_match('#^(10|172\.16|192\.168)\.#', $xip)) {
                    $ip = $xip;
                    break;
                }
            }
        } elseif (isset($_SERVER['HTTP_CLIENT_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CF_CONNECTING_IP'])) {
            $ip = $_SERVER['HTTP_CF_CONNECTING_IP'];
        } elseif (isset($_SERVER['HTTP_X_REAL_IP']) && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_REAL_IP'])) {
            $ip = $_SERVER['HTTP_X_REAL_IP'];
        }
        
        return $ip;
    }
    
    public function validateCoordinates($coordinates){
        return is_array($coordinates) && count($coordinates)>0 && isset($coordinates[self::LATITUDE]) && isset($coordinates[self::LONGITUDE]);
    }

    public function run( $param=NULL ) {
        
        $ip=$this->ip;
        
        if( $ip ){
            try{

                $url = "http://ip-api.com/json/{$ip}";
                $response = file_get_contents($url);
                $data = json_decode($response, true);

                if ( $data['status'] == 'success' && $this->validateCoordinates($data) ) {
                    return [
                        self::LATITUDE => $data['lat'],
                        self::LONGITUDE => $data['lon']
                    ];
                } else {
                    throw new Exception("Coordinates couldn't be taken for ip: " . $ip);
                }

            } 
            catch (Exception $e) 
            {
                throw new RuntimeException( "Error: " . $e->getMessage() );
            }
        }
        
        return [
                    self::LATITUDE => 0,
                    self::LONGITUDE => 0
                ];
    }
}
