<?php

class LogHandler
{
    protected $logsPath;
    protected $logsFilename;
    protected $date;

    public function __construct( $logsPath, $logsFilename )
    {
        $this->logsPath = (string)$logsPath;

        //separate filename and its extension
        $logsFilename=explode( '.', $logsFilename );
        
        $this->logsFilename=$logsFilename[0] . '-' . date('Y-m-d') . '.' .$logsFilename[1];

    }

    //normal methods of Log
    

    public function log($message)
    {
        $date = $this->date = new DateTime();
        
        $formattedMessage = "[{$date->format('Y-m-d H:i:s')}] {$message}" . PHP_EOL ;
        
        $this->write($formattedMessage);
    }
    
    private function write($message) {
        
        $message = (string)$message;
        
        
        $logsPath = $this->logsPath;
        $log = $logsPath . DIRECTORY_SEPARATOR . $this->logsFilename;

        if (is_dir($logsPath)) {
            if (!file_exists($log)) {
                try {
                    $fh = fopen($log, 'a+') or die("Fatal Error !");
                    $logcontent = "Time : " . $this->date->format('H:i:s') . "\r\n" . $message . "\r\n";
                    fwrite($fh, $logcontent);
                    fclose($fh);
                } catch (Exception $e) {
                    throw new RuntimeException( "Log::write EXCEPTION on file ' $log '" , $e->getCode(), $e->getPrevious());
                }
            } 
            else {
                $this->edit($message);
            }
        } else {
            if (mkdir($logsPath, 0755) === true) {
                $this->write($message);
            }
            else{
                throw new RuntimeException( "Log::write EXCEPTION on file: ' $log '" );
            }
        }
    }


    private function edit($message) {
        $log = $this->logsPath . DIRECTORY_SEPARATOR . $this->logsFilename;
        $message=(string)$message;
        $date=$this->date;
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
