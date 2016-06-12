<?php 
/**
 * Auto buyer
 * 13/01/2015
 * http://www.pisse-froid.com/fifa-ultimate-team-trucs-et-astuces/
 * Methode avec les joueurs à niveau 75.
 * Mis à part quelques rares cas, les joueurs à 75 n’intéressent personne.
 * Pas mal de joueurs les vendent donc sur le marché avec une mise à prix à 150.
 * Sachant que ces joueurs valent à la revente 300, si l’on place tout de suite une enchère à 250,
 * on est quasiment sûr de gagner 50 à la revente rapide. A part un joueur voulant réellement ce joueur,
 * personne ne voudra mettre 300 pour un joueur qu’on revend 300 (ou alors un gros débile).
 * Ainsi, vous pouvez gagner 50 pour chaque joueur ayant une note de 75, et ayant été mis à prix à 150.
 *
 * UPDATE: Recherche tous les joueurs en vente sous-cotés.
 */
use Fut\Log;
use Fut\Fifa\Constant\Team;

Log::V('MODE ACHAT/REVENTE RAPIDE - OR');

    $players = $FUTBuyer->findQuickSell();
    Log::I(sizeof($players).' joueurs sous-côtés trouvés.');

    foreach ($players as $p) {
        $i = $p['itemData'];
        Log::D('Estimation bénef.: '.($i['discardValue'] - $p['nextBid']).', tradeId: '.$p['tradeId'].', startingBid: '.$p['startingBid'].', currentBid: '.$p['currentBid'].', buyNowPrice: '.$p['buyNowPrice'].', nextBid: '.$p['nextBid'].', discardValue: '.$i['discardValue'].', expire: '.$p['expires']);

        //Achat
        if ($INI_FILE['allow_quick_buy']) {
            try {
                $FUTBuyer->placeBid($p['tradeId'], $p['nextBid']);
                Log::I('Quick buy réussi... attente fin des enchères.');
                usleep($CONFIG['wainting_time']);
            } catch (Exception $e) {
                Log::V('Quick buy raté!');
            }
        }
    }

    usleep($CONFIG['wainting_time'] * 4);
?>
