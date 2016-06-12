<?php
namespace Fut\Fifa;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Subscriber\Cookie as CookieSubscriber;
use Fut\Connector;
use Fut\Request\Forge;
use Fut\Fifa\Constant\Resources;

class Fifa
{
    protected $client;
    protected $export;
    
    public function __construct(){

    }
    
    
    public function connection($email, $password, $secret){
        
        $this->client = new Client();
        $cookieJar = new CookieJar();
        $cookieSubscriber = new CookieSubscriber($cookieJar);
        $this->client->getEmitter()->attach($cookieSubscriber);
       
        
        try {
        
            $connector = new Connector(
                $this->client, $email, $password, $secret,
                Forge::PLATFORM_PLAYSTATION,
                Forge::ENDPOINT_MOBILE
            );
        
            $this->export = $connector
                ->connect()
                ->export();
        
            return true;
        } catch(Exception $e) {
            return false;
        }
    }
    
    public function getExport(){
        return $this->export;
    }
    
    public function setExport($export){
        $this->export = $export;
    }
    
    public function credit(){
        
        $forge = Forge::getForge($this->client, Resources::FUTHOME.Resources::CREDITS, 'post', 'get');
        
       
        $json = $forge
            ->setNucId($this->export['nucleusId'])
            ->setSid($this->export['sessionId'])
            ->setPhishing($this->export['phishingToken'])
            ->getJson();
        
        return $json['credits'];
    }
    
    public function consumables(){
    
        $forge = Forge::getForge($this->client, Resources::FUTHOME.Resources::CONSUMABLES, 'post', 'get');
    
         
        $json = $forge
        ->setNucId($this->export['nucleusId'])
        ->setSid($this->export['sessionId'])
        ->setPhishing($this->export['phishingToken'])
        ->getJson();
    
        return $json;
    }
    
    public function purchased(){
    
        $forge = Forge::getForge($this->client, Resources::FUTHOME.Resources::PURCHASEDITEMS, 'post', 'get');
         
        $json = $forge
        ->setNucId($this->export['nucleusId'])
        ->setSid($this->export['sessionId'])
        ->setPhishing($this->export['phishingToken'])
        ->getJson();
    
        return $json;
    }

    public function teams(){
    
        $forge = Forge::getForge($this->client, Resources::FUTHOME.Resources::SQUADLIST, 'post', 'get');
    
         
        $json = $forge
        ->setNucId($this->export['nucleusId'])
        ->setSid($this->export['sessionId'])
        ->setPhishing($this->export['phishingToken'])
        ->getJson();
        
        return $json;
    }
    
    public function pileSize(){
    
        $forge = Forge::getForge($this->client, Resources::FUTHOME.Resources::PILESIZE, 'post', 'get');
    
         
        $json = $forge
        ->setNucId($this->export['nucleusId'])
        ->setSid($this->export['sessionId'])
        ->setPhishing($this->export['phishingToken'])
        ->getJson();
    
        return $json;
    }
    
    public function tradePile(){
        $forge = Forge::getForge($this->client, Resources::FUTHOME.Resources::TRADEPILE, 'post', 'get');

        $json = $forge
        ->setNucId($this->export['nucleusId'])
        ->setSid($this->export['sessionId'])
        ->setPhishing($this->export['phishingToken'])
        ->getJson();
    
        return $json;
    }
    
    public function searchMarket($param){ 
        
        $parametres = array(
        'type'      => 'player',
        'start'     => 0,    //item start 0
        'num'       => 50,   //number item display
        'minb'      => 0,    //min buy
        'maxb'      => 0,    //max buy
        'maskedDefId'  => 0, //player ID (calcul baseID?)
        'leag' => 0,
        'nat' => 0,
        'playStyle' => 0,
        'team' => 0,
        'micr' => 0,        //Min bid
        'macr' => 0,        //Max bid
        'lev' => null,      //Level bronze, gold, silver
        'pos' => null,
        'zone' => null      //Defenders ,Midfielders , Attackers
        );
        
        $param = array_merge($parametres,$param);

        $search = '?type='.$param['type'].'&start='.$param['start'].'&num='.$param['num'];
        
        $search .= ($param['minb'] > 0)? '&minb='.$param['minb']: '';
        $search .= ($param['maxb'] > 0)? '&maxb='.$param['maxb']: '';
        $search .= ($param['maskedDefId'] > 0)? '&maskedDefId='.$param['maskedDefId']: '';
        $search .= ($param['leag'] > 0)? '&leag='.$param['leag']: '';
        $search .= ($param['nat'] > 0)? '&nat='.$param['nat']: '';
        $search .= ($param['playStyle'] > 0)? '&playStyle='.$param['playStyle']: '';
        $search .= ($param['team'] > 0)? '&team='.$param['team']: '';
        $search .= ($param['micr'] > 0)? '&micr='.$param['micr']: '';
        $search .= ($param['macr'] > 0)? '&minb='.$param['macr']: '';
        $search .= ($param['lev'] != null)? '&lev='.$param['lev']: '';
        $search .= ($param['pos'] != null)? '&pos='.$param['pos']: '';
        $search .= ($param['zone'] != null)? '&zone='.$param['zone']: '';

        
        $forge = Forge::getForge($this->client, Resources::FUTHOME.Resources::TRANSFERMARKET.$search, 'post', 'get');
    
         
        $json = $forge
        ->setNucId($this->export['nucleusId'])
        ->setSid($this->export['sessionId'])
        ->setPhishing($this->export['phishingToken'])
        ->getJson();
        
        return $json;
    }

    
    public function bid($tradeId, $bid){
    
        $url = sprintf(Resources::BID, $tradeId);
        $forge = Forge::getForge($this->client, Resources::FUTHOME.$url, 'post', 'put');
   
        $json = $forge
        ->setNucId($this->export['nucleusId'])
        ->setSid($this->export['sessionId'])
        ->setPhishing($this->export['phishingToken'])
        ->setBody(array('bid' => $bid), true)
        ->getJson();
        return $json;
    }
    
    public function clubItems(){
    
    
        $forge = Forge::getForge($this->client, Resources::FUTHOME.Resources::MYCLUB, 'post', 'get');
         
        $json = $forge
        ->setNucId($this->export['nucleusId'])
        ->setSid($this->export['sessionId'])
        ->setPhishing($this->export['phishingToken'])
        ->getJson();
    
        return $json;
    }
    

    public function sell($data){
    
       $forge = Forge::getForge($this->client, Resources::FUTHOME.Resources::AUCTIONHOUSE, 'post', 'post');
    
        $json = $forge
        ->setNucId($this->export['nucleusId'])
        ->setSid($this->export['sessionId'])
        ->setPhishing($this->export['phishingToken'])
        ->setBody($data, true)
        ->getJson();
        
        return $json;
    }
    
    public function quickSell($itemId){
    
        $forge = Forge::getForge($this->client, Resources::FUTHOME.Resources::QUICKSELL.$itemId, 'post', 'delete');
    
        $json = $forge
        ->setNucId($this->export['nucleusId'])
        ->setSid($this->export['sessionId'])
        ->setPhishing($this->export['phishingToken'])
        ->getJson();
        return $json;
    }
    
    
    public function watching(){
    
        $forge = Forge::getForge($this->client, Resources::FUTHOME.Resources::WATCHLIST, 'post', 'get');
    
        $json = $forge
        ->setNucId($this->export['nucleusId'])
        ->setSid($this->export['sessionId'])
        ->setPhishing($this->export['phishingToken'])
        ->getJson();
    
        return $json;
    }
    
    public function removeWatchList($tradeId){
    
        $forge = Forge::getForge($this->client, Resources::FUTHOME.Resources::WATCHLIST."?tradeId=".$tradeId, 'post', 'delete');
    
        $json = $forge
        ->setNucId($this->export['nucleusId'])
        ->setSid($this->export['sessionId'])
        ->setPhishing($this->export['phishingToken'])
        ->getJson();
    
        return $json;
    }
    
    public function sendToTradeList($tradeId, $id){
    
        $data = array(
            'itemData' => [array(
                'tradeId' => $tradeId,
                'id' => $id,
                'pile' => 'trade'
            )] 
        );

        $forge = Forge::getForge($this->client, Resources::FUTHOME.Resources::LISTITEM, 'post', 'put');
        
        $json = $forge
        ->setNucId($this->export['nucleusId'])
        ->setSid($this->export['sessionId'])
        ->setPhishing($this->export['phishingToken'])
        ->setBody($data,true)
        ->getJson();
    
        return $json;
    }
    
    /////////////////////////////////////////////////////////////////////////////////////
    
    public function test($param = null){

        $forge = Forge::getForge($this->client, Resources::FUTHOME.Resources::WATCHLIST, 'post', 'get');
    
        $json = $forge
        ->setNucId($this->export['nucleusId'])
        ->setSid($this->export['sessionId'])
        ->setPhishing($this->export['phishingToken'])
        ->getJson();
        
        return $json;
    }
    
  
}

// /**
//  * the connector will not export your cookie jar anymore
//  * keep a reference on this object somewhere to inject it on reconnecting
//  */
// 


// var_dump($export);

// // example for playstation accounts to get the credits
// // 3. parameter of the forge factory is the actual real http method
// // 4. parameter is the overridden method for the webapp headers
// 


// 
	

// echo "you have " . $json['credits'] . " coins" . PHP_EOL;