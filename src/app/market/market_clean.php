<?php
/**
 * Auto buyer
 * 13/01/2015
 * Fonctionnemnent classique,
 * recherche un joueur à acheter,
 * vend les joueurs à vendre.
 */
use Fut\Log;
use Fut\Fifa\Constant\Duration;
use Fut\FUTStat;

Log::V('TRAITEMENT DES JOUEURS ACHETES');
    Log::V('Etape 1: Traitement des joueurs non-attribués');

    usleep($CONFIG['wainting_time']);
    try {
        $pile = $FUTBuyer->watchingPile();
        usleep($CONFIG['wainting_time']);
    } catch (Exception $e) {
        Log::E('== Code '.$e->getCode().', reason: '.$e->getMessage());
        exit;
    }

    if (sizeof($pile['auctionInfo']) > 0) {
        foreach ($pile['auctionInfo'] as $p) {
            $i = $p['itemData'];

            $LOG = 'tradeId: '.$p['tradeId'].', lastSalePrice: '.$i['lastSalePrice'].',  startingBid: '.$p['startingBid'].', currentBid: '.$p['currentBid'].', buyNowPrice: '.$p['buyNowPrice'].', discardValue: '.$i['discardValue'].', expires: '.$p['expires'];

            // Transfert fini
            if ($p['tradeState'] == 'closed') {

                // Joueur acheté
                if ($p['bidState'] == 'highest') {
                    $buyAssetId = $BUY_PLAYER['player']['assetId'];
                    $soldAssetId = $SOLD_PLAYER['player']['assetId'];
                    $benef = ($i['discardValue'] - $p['currentBid']);

                    //Joueur acheté pour l'achat/revente rapide
                    if ($i['assetId'] != $buyAssetId && $i['assetId']  != $soldAssetId && $p['currentBid'] < $i['discardValue']) {
                        Log::I('Revente rapide [+'.$benef.'] - '.$LOG);
                        $res = $FUTBuyer->quickSell($i['id']);
                        usleep($CONFIG['wainting_time'] * 2);
                        $CREDITS = $res['totalCredits'];
                        FUTStat::moreOneQuickSell();
                        FUTStat::setBenef($benef);
                    } else {

                        //Joueur acheté pour l'achat/revente
                        if ($i['assetId'] == $soldAssetId) {
                            if ($i['lastSalePrice'] < $i['discardValue']) {
                                Log::I('Revente rapide [+'.$benef.'] - '.$LOG);
                                $res = $FUTBuyer->quickSell($i['id']);
                                usleep($CONFIG['wainting_time'] * 2);
                                $CREDITS = $res['totalCredits'];
                                FUTStat::moreOneQuickSell();
                                FUTStat::setBenef($benef);
                            } else {
                                Log::V('Envoi vers les transferts - '.$LOG);

//                                 $selldata = array(
//                                     "itemData" => array(
//                                         "id" => $i['id']
//                                     ),
//                                     "buyNowPrice" => $SOLD_PLAYER['trade']['sold_buy_now_price'],
//                                     "startingBid" => $SOLD_PLAYER['trade']['sold_starting_bid'],
//                                     "duration" => Duration::ONEHOUR
//                                 );

                                $FUTBuyer->sendToTradeList($p['tradeId'], $i['id']);
                                FUTStat::moreOneBought();
                                usleep($CONFIG['wainting_time'] * 2);
                                //$LOG = 'tradeId: '.$p['tradeId'].', startingBid: '.$SOLD_PLAYER['trade']['sold_starting_bid'].', buyNowPrice: '.$SOLD_PLAYER['trade']['sold_buy_now_price'].', discardValue: '.$i['discardValue'];

//                                 if ($INI_FILE['allow_sold']) {

//                                     $res = $FUTBuyer->sell($selldata);
//                                     if (is_array($res)) {
//                                         Log::I('En vente - '.$LOG);
//                                     } else {
//                                         Log::E('En vente - '.$LOG);
//                                     }
//                                 } else {
//                                     Log::D('En vente - '.$LOG);
//                                 }
                            }
                        }
                    }
                } else {

                    // joueur raté -> Suppr
                    if ($p['bidState'] == 'outbid') {
                        Log::V('Expiré ['.$p['currentBid'].'] - '.$LOG);

                        $FUTBuyer->removeWatchingPile($p['tradeId']);
                        usleep($CONFIG['wainting_time'] * 2);
                    } else {
                        // Autres...
                        Log::E('WatchingPile inconnu bidState: '.$p['bidState']);
                    }
                }

                usleep($CONFIG['wainting_time']);
            } else {

                //Joueur encores en vente / Aucune action
                if ($p['tradeState'] == 'active') {
                    Log::V('En cours ['.$p['currentBid'].'] - '.$LOG);
                } else {
                    Log::E('WatchingPile inconnu tradeState: '.$p['tradeState']);
                }
            }
        }
    } else {
        Log::V('Aucun joueur non attribué.');
    }

    usleep($CONFIG['wainting_time'] * 4);

    Log::V('Etape 2: Remise en vente des joueurs expirés.');

    //Recuperation de la pile des joueurs à vendre / en vente
    // $pile = $FUTBuyer->tradePile(); //Déplacé dans market.php (évite double requete)
    $nb_sold = 0;

    foreach ($TRADEPILE['auctionInfo'] as $key => $p) {
        $i = $p['itemData'];

        // Dejé en vente -> Log
        if ($i['itemState'] == 'forSale') {
            Log::V('Vente en cours - tradeId: '.$p['tradeId'].', startingBid: '.$p['startingBid'].', buyNowPrice: '.$p['buyNowPrice'].', discardValue: '.$i['discardValue'].', expires: '.$p['expires']);
        } else {

            // Plus en vente -> remise en vente
            if ($i['itemState'] == 'free') {
                $selldata = array(
                    'itemData' => array(
                        'id' => $i['id'],
                    ),
                    'buyNowPrice' => $SOLD_PLAYER['trade']['sold_buy_now_price'],
                    'startingBid' => $SOLD_PLAYER['trade']['sold_starting_bid'],
                    'duration' => Duration::ONEHOUR,
                );

                //Compte +1
                ++$nb_sold;

                // Mise en vente
                $LOG = 'startingBid: '.$SOLD_PLAYER['trade']['sold_starting_bid'].', buyNowPrice: '.$SOLD_PLAYER['trade']['sold_buy_now_price'].', discardValue: '.$i['discardValue'];

                if ($INI_FILE['allow_sold']) {
                    try {
                        $res = $FUTBuyer->sell($selldata);
                        usleep($CONFIG['wainting_time'] * 4);
                        if (is_array($res)) {
                            Log::I('Remis en vente - '.$LOG);
                        } else {
                            Log::E('Remis en vente - '.$LOG);
                        }
                    } catch (Exception $e) {
                        Log::E('== Code '.$e->getCode().', reason: '.$e->getMessage());
                        exit;
                    }
                } else {
                    Log::D('Remis en vente - '.$LOG);
                }
            }
        }

        //Limite le nombre de requéte sur le serveur
        if ($nb_sold == $CONFIG['treatment_by_round']) {
            break;
        }
    }

?>
