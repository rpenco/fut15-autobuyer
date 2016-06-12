<?php

namespace Fut;

class Log
{
    public static function V($message, $write = false){
        Log::LOG('verb', $message, $write);
    }
    
    public static function D($message, $write = true){
        Log::LOG('debu', $message, $write);
    }
    
    public static function I($message, $write = true){
        Log::LOG('info', $message, $write);
    }

    public static function W($message, $write = true){
        Log::LOG('warn', $message, $write);
    }
    
    public static function E($message, $write = true){
        Log::LOG('erro', $message, $write);
    }
    
    private static function LOG($level, $message, $write = true){
        
        $log = date('Y-m-d H:i:s', time()).' ['.$level.'] '.$message.'';
        echo '<div class="log log-'.$level.'">'.$log.'</div>';
        
        if($write){
            $h = fopen(__DIR__.'/../app/logs/activity.log', 'a+');
            fwrite($h,$log."\r\n");
            fclose($h);
        }
    }
}