<?php
if( !defined('BASE_PATH') )
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    
    exit( 'Forbbiden access' );
}

class Base{

    /**
     * this registry singleton instance
     * @access private
     */
    
    protected static $instances=array();

    protected $config;
    
    //Desing pattern methods
    
    /**
     * singleton access to a this object
     * @access public
     * @return
     */
    
    public static function getInstance()
    {
        $obj = get_called_class();
        if( !isset( self::$instances[$obj] ) )
        {
            self::$instances[$obj] = new $obj;
        }
 
        return self::$instances[$obj];
    }
    
    
    
    public static function cleanSingleton(){
        $obj = get_called_class();
        if( isset(self::$instances[$obj]) )
        {
            unset( self::$instances[$obj] );
        }
    }
    
    protected function __construct( $configFile= __DIR__. DIRECTORY_SEPARATOR . 'config.php' ) {
        // Leer el archivo JSON y convertirlo en un array
        if (file_exists( $configFile )) 
        {
            $this->autoloader();    
            $this->config = require_once $configFile;
            $this->setErrorConfigs();
        } 
        else 
        {
            throw new Exception("Base::__construct config file not encountered");
        }

    }
    
    protected function setErrorConfigs(){
        
        $errorConfig=$this->config['error_handling'];
        
        if ( $errorConfig['environment'] === 'development' ) {
            
            ini_set('log_errors', 1);
            
            ini_set( 'display_errors', $errorConfig['display_errors'] ? 1 : 0 );
            
            error_reporting( $errorConfig['error_reporting_level'] );
            
        } 
        else 
        {
            
            ini_set('log_errors', 0);
            
            ini_set( 'display_errors', 0 );
            
            error_reporting(0);
        }

        // Configurar el log de errores
        if ( $errorConfig['log_errors'] ) {
            
            
            
            $logsPath=__DIR__ . DIRECTORY_SEPARATOR . $errorConfig['log_file_path'];
            
            ini_set('error_log', $logsPath );
            
            $errorHandler = new ErrorHandler( new LogHandler( $logsPath , $errorConfig['log_file_name'] ) );

            set_error_handler([$errorHandler, 'handle']);

            $exceptionHandler = new ExceptionHandler( $errorHandler ); 

            set_exception_handler([$exceptionHandler, 'handle']);
            
            $shutdownErrorHandler= new ShutdownErrorHandler( $errorHandler );
            
            register_shutdown_function( [$shutdownErrorHandler, 'handle'] );
            
        }
    }

    //var para hacerlo privado y poderlo llamarlo siendo privado
    protected function autoloader( $notDirs=['nbproject','stuff'] ){
       
        $root_path= realpath( dirname(__DIR__) );
        
        spl_autoload_register(
                
            function( $className ) use ( $root_path, $notDirs ) {
                $fileFound = false;
                
                // Obtener los directorios donde buscar
                $foldersWhereRequire = self::dirsFrom($root_path, $notDirs);

                // Recorrer cada directorio
                foreach ($foldersWhereRequire as $folder) {

                    // Construir la ruta del archivo
                    $fileName = $folder . DIRECTORY_SEPARATOR . $className . '.php';

                    // Si el archivo existe, lo incluimos
                    if (file_exists($fileName)) {
                        require_once $fileName;
                        $fileFound = true;
                        break; // Detener el bucle una vez que se encuentra e incluye el archivo
                    }
 
                }

                // Si no se encontró el archivo, podemos manejarlo
                if (!$fileFound) {
                    throw new Exception("Loader::autoloader $className class not found.");
                }
                
            });

    }
    
    protected static function dirsFrom( $path, $notDirs=[] ){
        $path= rtrim( (string)$path );
        // Array para guardar el directorio y los archivos
        $dirs = [];

        // Se comprueba que realmente sea la ruta de un directorio
        if (is_dir($path)) {
            // Abre un gestor de directorios para la ruta indicada
            $gestor = opendir($path);

            // Recorre todos los elementos del directorio
            while (($file = readdir($gestor)) !== false) {
                $complete_path = $path . DIRECTORY_SEPARATOR . $file;
                // Mostramos todos los archivos y directorios excepto "." y ".."
                if ($file != "." && $file != ".." && $file[0]!='. '&& !in_array($file , $notDirs )) {

                    // Si es un directorio se recorre recursivamente
                    if (is_dir($complete_path)) {
                        // Añadimos el array (recursivo) del siguiente directorio 
                        $dirs = array_merge( $dirs, self::dirsFrom($complete_path,$notDirs) );
                        // Si es un archivo añadimos ruta/archivo al Array
                    } else {
                        //$dirs = ['ruta' => $path . DIRECTORY_SEPARATOR, 'archivo' => $file];
                        if( !in_array($path, $dirs))
                            $dirs[]= $path;
                    }
                }
            }
            // Cierra el gestor de directorios
            closedir($gestor);

        } 

        // Devolvemos el array del directorio actual  
        return $dirs;
        
    }
    
    
    public function getErrorConfig($key, $default = null) {
        return isset($this->config['error_handling'][$key]) ? $this->config[$key] : $default;
    }

    public function __clone()
    {
        trigger_error( 'The clonation of this object is forbidden.', E_USER_ERROR );
    }

    // 'magic'

}