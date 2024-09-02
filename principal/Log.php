<?php
if( !defined('BASE_PATH') )
{
    header($_SERVER['SERVER_PROTOCOL'] . ' 403 Forbidden');
    
    exit( 'Acceso no permitido a este archivo.' );
}

final class Log{
		
    /**
     * @var string $path Log directory name
     */
    private $path;

    /**
     * Default Constructor, Sets the timezone and path of the log files.
     * 
     * @param type $path a path where create or manage the log directory with log files
     * @param type $timeZone to write date of file correctly to each zone
     * @throws \InvalidArgumentException if bad timezone passed or are not strings
     * @return void
     */
    function __construct($path) {
        $path=(string)$path;
        if( $path[strlen($path)-1]!=DIRECTORY_SEPARATOR )
            $path.=DIRECTORY_SEPARATOR;
        $this->path= $path;
        
    }

    /**
     *	Creates or write the daily log
     *
     *	 1. Checks if directory exists, if not, create one and call this method again.
     *	 2. Checks if log already exists.
     *	 3. If not, new log gets created. Log is written into the logs folder.
     *	 4. Logname is current date(Year - Month - Day).
     *	 5. If log exists, edit method called.
     *	 6. Edit method modifies the current log.
     * 
     * @param string $message the message which is written into the log.
     * @return void
     * @throws \InvalidArgumentException if bad $message is not a string
     */	
    public function write($message) {
        $message=(string)$message;
        $date = new DateTime();
        $log = $this->path .$date->format('Y-m-d') . ".txt";

        if (is_dir($this->path)) {
            if (!file_exists($log)) {
                try {
                    $fh = fopen($log, 'a+') or die("Fatal Error !");
                    $logcontent = "Time : " . $date->format('H:i:s') . "\r\n" . $message . "\r\n";
                    fwrite($fh, $logcontent);
                    fclose($fh);
                } catch (Exception $e) {
                    throw new RuntimeException( "Log::write EXCEPTION on file ' $log '" , $e->getCode(), $e->getPrevious());
                }
            } 
            else {
                $this->edit($log, $date, $message);
            }
        } else {
            if (mkdir($this->path, 0777) === true) {
                $this->write($message);
            }
            else{
                throw new RuntimeException( "Log::write EXCEPTION on file ' $log '" , $e->getCode(), $e->getPrevious());
            }
        }
    }

    /** 
     *  Gets called if log exists. 
     *  Modifies current log and adds the message to the log.
     *
     * @param string $log the filename with path, subpathh and basename
     * @param object $date datetime object to name and write the log file
     * @param string $message the content of the log file
     * @return void
     * @throws \InvalidArgumentException if bad parameters passed (string in $log, \DateTime in $date or string in $message)
     * @throws \RuntimeException if any I/O error occurs (length minor than 0, not EOF, ..)
     */
    
    private function edit($log,$date,$message) {
        $log=(string)$log;
        $message=(string)$message;
        $date=( !is_object($date) ? new DateTime() : (object)$date );
        try{
            $logcontent = "Time : " . $date->format('H:i:s')."\r\n" . $message ."\r\n\r\n";
            $logcontent = $logcontent . file_get_contents($log);
            file_put_contents($log, $logcontent);
        }
        catch(Exception $e){
            throw new RuntimeException( "Log::edit EXCEPTION on file ' $log '" , $e->getCode(), $e->getPrevious());
        }
    }
}

