<?php

/**
 * 
 */
namespace Fut\Fifa;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Subscriber\Cookie as CookieSubscriber;
use Fut\Connector;
use Fut\Request\Forge;
use Fut\Fifa;
use Fut\Log;
use GuzzleHttp\json_decode;

class FUTBuyer
{

    protected $fifa;

    protected $CONFIG;

    public function __construct($CONFIG)
    {
        $this->CONFIG = $CONFIG;
    }

    /**
     * Connexion sur les serveur
     *
     * @param unknown $email            
     * @param unknown $password            
     * @param unknown $secret            
     */
    public function connection($email, $password, $secret)
    {
        $this->fifa = new \Fut\Fifa\Fifa();
        
        if ($this->fifa->connection($email, $password, $secret)) {
            
            // Sauvegarde de l'export
            $export = $this->fifa->getExport();
            $this->saveExport($export);
            return true;
        }
        return false;
    }

    public function quickConnection()
    {
        $this->fifa = new \Fut\Fifa\Fifa();
        
        // Traitement de l'export
        $export = $this->loadExport();
        
        // Utilisation de l'export autant que possible
        if ($export != null) {
            
            // Export valable -> utilisation
            $this->fifa->setExport($export);
            return true;
        }
        return false;
    }

    private function loadExport()
    {
        $export = file_get_contents(__DIR__ . "/../../app/conf/export.json");
        return json_decode($export, true);
    }

    public function saveExport($export)
    {
        $json = json_encode($export);
        
        $fp = fopen(__DIR__ . "/../../app/conf/export.json", 'w');
        fwrite($fp, $json);
        fclose($fp);
    }

    /**
     * *
     * Requêtes vers les serveurs EA
     * *
     */
    
    /**
     * Récupération des crédits de l'utilisateur
     */
    public function credits()
    {
        return $this->credits = $this->fifa->credit();
    }

    /**
     * Rechercher des joueurs en fonction des paramètres ci-dessous
     *
     * @param string $param            
     */
    public function findPlayers($param = null)
    {
        $parametres = array(
            'type' => 'player',
            'start' => 0, // item de debut 0
            'num' => 50, // nombre de résultats (50 max)
            'minb' => 0, // min buy
            'maxb' => 0, // max buy
            'maskedDefId' => 0, // /!\ Non fonctionnel (player ID? / BaseId?)
            'leag' => 0, // League::
            'nat' => 0, // Nation::
            'playStyle' => 0, // ChemistryStyle::
            'team' => 0, // Team::
            'micr' => 0, // Min bid
            'macr' => 0, // Max bid
            'lev' => null, // bronze, gold, silver
            'pos' => null, // GK,WG..
            'zone' => null
        ); // Defenders ,Midfielders , Attackers
        
        return $this->fifa->searchMarket($param);
    }

    /**
     * Placer une enchère sur un joueur
     *
     * @param unknown $tradeId
     *            tradeId
     * @param unknown $bidAmount
     *            montant (Conforme aux spec. FUT)
     */
    public function placeBid($tradeId, $bidAmount)
    {
        return $this->fifa->bid($tradeId, $bidAmount);
    }

    /**
     * Récupération de la pile des transferts
     * Expiré, En cours, Vendu
     */
    public function tradePile()
    {
        return $this->fifa->tradePile();
    }

    /**
     * Mise en vente d'un joueur aux enchères
     *
     * @param unknown $params            
     */
    public function sell($params)
    {
        $parameters = array(
            "itemData" => array(
                "id" => 0
            ), // id du joueur
            "buyNowPrice" => 0, // Prix achat immédiat
            "startingBid" => 0, // Enchère de départ
            "duration" => 3600
        ); // Durée de l'enchère
        
        return $this->fifa->sell($params);
    }

    /**
     * Récupération de la pile des joueurs/items suivis.
     * Contient également les joueurs/items 'non attribués'
     */
    public function watchingPile()
    {
        return $this->fifa->watching();
    }

    /**
     * Envoyer un joueur/item sur la pile des transferts
     *
     * @param unknown $tradeId            
     * @param unknown $id            
     */
    public function sendToTradeList($tradeId, $id)
    {
        return $this->fifa->sendToTradeList($tradeId, $id);
    }

    /**
     * Supprimer un joueur/item de la pile des joueurs/items suivis.
     *
     * @param unknown $tradeId            
     */
    public function removeWatchingPile($tradeId)
    {
        return $this->fifa->removeWatchList($tradeId);
    }

    /**
     * Fonction de test en lien avec la fonction test de la couche inférieure.
     *
     * @param string $param            
     */
    public function test($param = null)
    {
        return $this->fifa->test($param = null);
    }

    /**
     * Vente rapide d'un joueur/item
     *
     * @param unknown $itemId            
     */
    public function quickSell($itemId)
    {
        return $this->fifa->quickSell($itemId);
    }

    /**
     * *
     * Modes de l'autobuyer
     * *
     */
    
    /**
     * Rechercher les joueurs sous cotés.
     */
    public function findQuickSell()
    {
        $quickPlayers = array();
        
        Log::V("Recherche sur " . ($this->CONFIG['number_pages_search'] * 50) . " joueurs.");
        
        for ($i = 0; $i < $this->CONFIG['number_pages_search']; $i ++) {
            $allPlayers = $this->findPlayers(array(
                'start' => (50 * $i),
                'lev' => 'gold',
                'maxb' => 700
            )) // Ne pas dépasser 10 000, le calcul nextBid ne fonctionne pas (incomplet)
;
            $pl1 = $this->getQuickSellPlayer($allPlayers['auctionInfo']);
            foreach ($pl1 as $p) {
                array_push($quickPlayers, $p);
            }
            usleep($this->CONFIG['wainting_time']);
        }
        
        return $quickPlayers;
    }

    private function getQuickSellPlayer($players)
    {
        $quickPlayers = array();
        foreach ($players as $p) {
            $i = $p['itemData'];
            
            // Calcul du prix du prochain Bid
            $nextBid = ($this->calculateNextBid($p['startingBid'], $p['currentBid'], $p['buyNowPrice']));
            
            // Prix de revente rapide > prix d'achat
            if ($i['discardValue'] > $nextBid) {
                $p['nextBid'] = $nextBid;
                array_push($quickPlayers, $p);
            }
        }
        
        return $quickPlayers;
    }

    /**
     * Calcul du montant du prochain Bid
     *
     * @param unknown $startingBid            
     * @param unknown $currentBid            
     * @param unknown $buyNowPrice            
     * @return number|unknown|string
     */
    public function calculateNextBid($startingBid, $currentBid, $buyNowPrice)
    {
        // Pas d'offre
        if ($currentBid == 0) {
            
            // prix = starting ou 150 min
            if ($startingBid <= 150) {
                return 150;
            } else {
                return $startingBid;
            }
        } else {
            // Déja offre, obligation de rajouter dessus.
            $price = $currentBid + 50;
            
            // Ajustement de la valuer
            // Inférieur à 1000, 50 par 50 à partir de 150
            // Au dessus, 100 par 100
            $unite = 0; // Pas d'unité
            $dizaine = (($price / 10 % 10) > 0) ? 5 : 0; // Dizaine uniquement < 1000 et 50 uniquement ou 00
            $centaine = $price / 100 % 10;
            $millier = $price / 1000 % 10;
            
            // 100 par 100
            if ($millier > 0) {
                if ($dizaine == 5) {
                    $centaine += 1;
                    $dizaine = 0;
                }
                
                return $millier . $centaine . $dizaine . '0';
            } else {
                return $centaine . $dizaine . '0';
            }
        }
    }

    public function averageBuyNowPrice($players)
    {
        $total = 0;
        $nb_players = sizeof($players['auctionInfo']);
        
        foreach ($players['auctionInfo'] as $key => $p) {
            
            $i = $p['itemData'];
            $a = $i['attributeList'];
            $s = $i['statsList'];
            $l = $i['lifetimeStats'];
            
            $total += $p['buyNowPrice'];
        }
        
        return $total / $nb_players;
    }

    public function profilStats($player)
    {
        $list = $this->fifa->searchMarket($player['player']);
        $p = $list['auctionInfo'][0]['itemData'];
        
        $res = array(
            'player' => array(
                'resourceId' => $p['resourceId'],
                'rating' => $p['rating'],
                'assetId' => $p['assetId'],
                'discardValue' => $p['discardValue']
            ),
            'trade' => array(
                'min_starting_bid' => - 1,
                'max_starting_bid' => 0,
                'average_starting_bid' => 0,
                'min_bid' => - 1,
                'max_bid' => 0,
                'average_bid' => 0,
                'min_bnp' => - 1,
                'max_bnp' => 0,
                'average_bnp' => 0
            ),
            'players' => $list['auctionInfo'],
            'players_bnp' => null
        );
        
        usort($list['auctionInfo'], array(
            $this,
            "cmpBNP"
        ));
        $res['players_bnp'] = $list['auctionInfo'];
        
        $sum_starting_bid = 0;
        $sum_bid = 0;
        $sum_bnp = 0;
        $nb = sizeof($list['auctionInfo']);
        
        foreach ($list['auctionInfo'] as $p) {
            
            // Starting bid
            $sum_starting_bid += $p['startingBid'];
            
            if ($res['trade']['min_starting_bid'] > $p['startingBid'] || $p['startingBid'] > 0) {
                $res['trade']['min_starting_bid'] = $p['startingBid'];
            }
            
            if ($res['trade']['max_starting_bid'] < $p['startingBid']) {
                $res['trade']['max_starting_bid'] = $p['startingBid'];
            }
            
            // Min bid
            $sum_bid += $p['currentBid'];
            
            if ($res['trade']['min_bid'] > $p['currentBid'] || $p['currentBid'] > 0) {
                $res['trade']['min_bid'] = $p['currentBid'];
            }
            
            if ($res['trade']['max_bid'] < $p['currentBid']) {
                $res['trade']['max_bid'] = $p['currentBid'];
            }
            
            // Buy Now Price
            $sum_bnp += $p['buyNowPrice'];
            
            if ($res['trade']['min_bnp'] > $p['buyNowPrice'] || $p['buyNowPrice'] > 0) {
                $res['trade']['min_bnp'] = $p['buyNowPrice'];
            }
            
            if ($res['trade']['max_bnp'] < $p['buyNowPrice']) {
                $res['trade']['max_bnp'] = $p['buyNowPrice'];
            }
        }
        
        $res['trade']['average_starting_bid'] = $sum_starting_bid / $nb;
        $res['trade']['average_bid'] = $sum_bid / $nb;
        $res['trade']['average_bnp'] = $sum_bnp / $nb;
        
        return $res;
    }

    public function ajustNextBid($currentBid)
    {
        $currentBid = round($currentBid);
        
        $unite = 0;
        
        if ($dizaine < 5 && $millier < 0) {
            $dizaine = 5;
        } else {
            $dizaine = 0;
            
            if ($dizaine >= 5) {
                if ($centaine < 9) {
                    $centaine = $centaine + 1;
                } else {
                    $centaine = 0;
                    $millier = $millier + 1;
                }
            }
        }
        
        if ($millier > 0) {
            return $millier . $centaine . $dizaine . $unite;
        } else {
            return $centaine . $dizaine . $unite;
        }
    }

    public function calculBuyNowPrice($ref, $player)
    {
        $i = $player['itemData'];
        
        // Prix mini = prix d'achat
        /*
         * $price = $i['lastSalePrice'];
         *
         * if($ref['trade']['average_bnp'] > $price){
         * $price = $this->CalculateNextBid($ref['trade']['average_bnp']);
         * }else{
         * $price = $this->calculStartingPrice($ref, $player);
         * }
         */
        
        // V2
        $price = $this->CalculateNextBid($i['lastSalePrice'] + 400);
        return $price;
    }

    public function calculStartingPrice($ref, $player)
    {
        $i = $player['itemData'];
        
        // Prix mini = prix d'achat
        /*
         * $price = $i['lastSalePrice'];
         * $price = $this->CalculateNextBid($price * 1.15);
         */
        
        // V2
        $price = $this->CalculateNextBid($i['lastSalePrice'] + 200);
        return $price;
    }

    public function canBuy($config_player, $ref, $p, $credit_restant)
    {
        $i = $p['itemData'];
        
        // Prix max
        $max_price = $config_player['trade']['max_price'];
        
        // Calcul prix d'achat
        $nextBid = $this->calculateNextBid($p['startingBid'], $p['currentBid'], $p['buyNowPrice']);
        
        $LOG = 'tradeId: ' . $p['tradeId'] . ', Prix max: ' . $max_price . ', nextBid: ' . $nextBid . ', startingBid: ' . $p['startingBid'] . ', currentBid: ' . $p['currentBid'] . ', buyNowPrice: ' . $p['buyNowPrice'] . ', discardValue: ' . $i['discardValue'] . ', expires: ' . $p['expires'];
        
        // Ne peut pas acheter
        if ($nextBid > $max_price) {
            Log::V('Trop cher - ' . $LOG);
            return - 1;
        }
        
        if ($i['contract'] < 7) {
            Log::V('Contrat invalide [' . $i['contract'] . '] - ' . $LOG);
            return - 1;
        }
        
        if ($i['fitness'] < 90) {
            Log::V('Forme invalide [' . $i['fitness'] . '] - ' . $LOG);
            return - 1;
        }
        
        // Joueur moins cher que la côte -> achat immédiat
        if ($nextBid < $i['discardValue']) {
            Log::I('Sous-cote - Achat - ' . $LOG);
            return $nextBid;
        }
        
        // OK pour l'acheter
        Log::V('Achat [BID] - ' . $LOG);
        return $nextBid;
    }

    public function canQuickBuy($p)
    {
        $i = $p['itemData'];
        $rating = $i['rating'];
        $discardValue = $i['discardValue'];
        $buyNowPrice = $p['buyNowPrice'];
        $startingBid = $p['startingBid'];
        $expires = $p['expires'];
        $currentBid = $p['currentBid'];
        
        $price = $startingBid;
        if ($currentBid > 0) {
            $price = $currentBid + 50;
        }
        
        if ($price == 0) {
            $price = 150;
        }
        
        if ($price < $discardValue) {
            Log::I('[BUY BID] place: ' . $price . ', discardValue: [' . $discardValue . '], buyNowPrice: [' . $p['buyNowPrice'] . '], startingBid: [' . $p['startingBid'] . '], currentBid: [' . $currentBid . '], expire: ' . $p['expires']);
        }
        return $price;
    }

    private function cmpBNP($p1, $p2)
    {
        if ($p1['buyNowPrice'] == $p2['buyNowPrice']) {
            return 0;
        }
        return ($p1['buyNowPrice'] < $p2['buyNowPrice']) ? - 1 : 1;
    }

    private function cmpCurrentBid($p1, $p2)
    {
        if ($p1['currentBid'] == $p2['currentBid']) {
            return 0;
        }
        return ($p1['currentBid'] < $p2['currentBid']) ? - 1 : 1;
    }
}

//         foreach($players['auctionInfo'] as $key => $p){

//             $i = $p['itemData'];
//             $a = $i['attributeList'];
//             $s = $i['statsList'];
//             $l = $i['lifetimeStats'];

// //             echo '- tradeId: '.$p['tradeId'].'<br/>';
// //             echo '- id: '.$i['id'].'<br/>';
// //             echo '- rating: '.$i['rating'].'<br/>';
// //             echo '- itemState: '.$i['itemState'].'<br/>';
// //             echo '- contract: '.$i['contract'].'<br/>';
// //             echo '- injuryGames: '.$i['injuryGames'].'<br/>';
// //             echo '- resourceId: '.$i['resourceId'].'<br/>';
// //             echo '- injuryType: '.$i['injuryType'].'<br/>';
// //             echo '- playStyle: '.$i['playStyle'].'<br/>';
// //             echo '- fitness: '.$i['fitness'].'<br/>';
// //             echo '- buyNowPrice: '.$p['buyNowPrice'].'<br/>';
// //             echo '- currentBid: '.$p['currentBid'].'<br/>';
// //             echo '- startingBid: '.$p['startingBid'].'<br/>';
// //             echo '- expires: '.$p['expires'].'<br/>';
// //             echo '================================<br/>';
//         }

//             echo '- tradeId: '.$p['tradeId'].'<br/>';
//             echo '- contract: '.$i['contract'].'<br/>';
//             echo '- forme: '.$i['fitness'].'<br/>';
//             echo '- buyNowPrice: '.$p['buyNowPrice'].'<br/>';
//             echo '- currentBid: '.$p['currentBid'].'<br/>';
//             echo '- startingBid: '.$p['startingBid'].'<br/>';
//             echo '- expires: '.$p['expires'].'<br/>';