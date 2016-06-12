<?php
namespace Fut;

class FUTStat
{

    public static function moreOneBought()
    {
        FUTStat::moreOne('players_bought');
    }

    public static function moreOneQuickSell()
    {
        FUTStat::moreOne('players_quicksell');
    }
    
    public static function setCredits($credits)
    {
        FUTStat::moreOne('credits', $credits,false);
    }
    
    public static function setBenef($benef)
    {
        FUTStat::moreOne('quick_benef', $benef);
    }
    

    private static function moreOne($key, $value = 1, $increment = true)
    {
        $stats = FUTStat::loadStat();
		if(!isset($stats[date('Y-m-d')][$key])){
			$stats[date('Y-m-d')][$key] = 0;
		}
		
        if($increment){
            $stats[date('Y-m-d')][$key] = $stats[date('Y-m-d')][$key] + $value;
        }else{
            $stats[date('Y-m-d')][$key] = $value;
        }
        FUTStat::saveStat($stats);
    }

    private static function loadStat()
    {
        $stats = file_get_contents(__DIR__ . "/../app/logs/stats.json");
        return json_decode($stats, true);
    }

    private static function saveStat($stats)
    {
        $json = json_encode($stats);
        $fp = fopen(__DIR__ . "/../app/logs/stats.json", 'w');
        fwrite($fp, $json);
        fclose($fp);
    }
}