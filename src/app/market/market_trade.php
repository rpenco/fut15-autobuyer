<?php
/**
 * Auto buyer
 * 13/01/2015
 * Fonctionnemnent classique,
 * recherche un joueur à acheter,
 * vend les joueurs à vendre.
 */
use Fut\Log;

Log::V('MODE ACHAT/REVENTE');

    $CREDIT_LEFT = $CREDITS - $CONFIG['min_credit_left'];

    Log::I('====== Crédit: ['.$CREDITS.' points], Budget: ['.$CREDIT_LEFT.' points]');

    if ($CREDIT_LEFT > 150) {
        if ($TRADESIZE < $CONFIG['players_in_trade']) {
            Log::V('Analyse et achat des joueurs');

            $ref = $FUTBuyer->profilStats($BUY_PLAYER);
            $refP = $ref['player'];
            $refT = $ref['trade'];
            $players = $ref['players_bnp'];

            // Verifie qu'on a le bon joueur
            if ($refP['assetId'] == $BUY_PLAYER['player']['assetId']) {
                Log::V('Joueur recherché ['.$BUY_PLAYER['player']['name'].', assetId:'.$BUY_PLAYER['player']['assetId'].', discard:'.$refP['discardValue'].']. start bid: ['.$refT['min_starting_bid'].' / '.$refT['max_starting_bid'].' / '.$refT['average_starting_bid'].'],
                    current bid: ['.$refT['min_bid'].' / '.$refT['max_bid'].' / '.$refT['average_bid'].'],
                    Buy Now: ['.$refT['min_bnp'].'/ '.$refT['max_bnp'].' / '.$refT['average_bnp'].']');

                //Traitement des joueurs récupérés précédement

                $nb_buy = 0;
                foreach ($players as $key => $p) {
                    $i = $p['itemData'];

                    // Retourne le montant de l'achat / 0 si ne pas acheter
                    $price = $FUTBuyer->canBuy($BUY_PLAYER, $ref, $p, $CREDIT_LEFT);

                    if ($price > 0) {
                        Log::V('Achat: ['.$price.'], tradeId: '.$p['tradeId'].', startingBid: '.$p['startingBid'].', currentBid: '.$p['currentBid'].', buyNowPrice: '.$p['buyNowPrice'].', nextBid: '.$price.', discardValue: '.$i['discardValue'].', expire: '.$p['expires']);

                        // Achat
                        if ($INI_FILE['allow_trade_buy']) {
                            try {
                                if ($TRADESIZE < $CONFIG['players_in_trade']) {
                                    $res = $FUTBuyer->placeBid($p['tradeId'], $price);
                                    ++$TRADESIZE;
                                    usleep($CONFIG['wainting_time']);
                                } else {
                                    Log::V('Pile pleine.');
                                }
                            } catch (Exception $e) {
                                Log::V('Trade raté!');
                            }
                        }

                        // Soustrait prix
                        $CREDIT_LEFT -= $price;
                        ++$nb_buy;
                    }

                    //Limite le nombre de requéte sur le serveur
                    if ($nb_buy == $CONFIG['treatment_by_round_trade_buy']) {
                        break;
                    }
                }

                Log::V('Crédits restants: '.$CREDIT_LEFT);
                usleep($CONFIG['wainting_time'] * 4);
            } else {
                Log::E('ID differents: ref: '.$refP['assetId'].', profil: '.$BUY_PLAYER['player']['assetId']);
            }
        } else {
            Log::V('Pile pleine.');
        }
    } else {
        Log::I('Credits insuffisants.');
    }

?>
