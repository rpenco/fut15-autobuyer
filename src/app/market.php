<?php
/**
 * Auto buyer
 * 13/01/2015
 * Par romain.
 */
require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/conf/config.php';

use Fut\Log;
use Fut\Fifa\FUTBuyer;
use Fut\FUTStat;

// Initialise l'autobuyer
$FUTBuyer = new FUTBuyer($CONFIG);

//Credit du joueurs
$CREDITS = 0;
$TRADEPILE = null; //Pile des transferts
$TRADESIZE = 0; //Nombre de joueurs sur la pile transfert

if (!$INI_FILE['enable']) {
    Log::W('L\'autobuyer est désactivé.');
}

if (!$INI_FILE['allow_trade_buy']) {
    Log::W('Les achats réels en "Achat/Revente" classique sont désactivés.');
}

if (!$INI_FILE['allow_quick_buy']) {
    Log::W('Les achats réels en "Achat/Revente rapide" sont désactivés.');
}

if (!$INI_FILE['allow_sold']) {
    Log::W('Les ventes réelles désactivées.');
}

if (!$INI_FILE['allow_quick']) {
    Log::W('Mode "Achat/Revente rapide" est désactivé.');
}

// Inclusion du mode achat/revente
if (!$INI_FILE['allow_trade']) {
    Log::W('Mode "Achat/Revente" classique est désactivé.');
}

if ($INI_FILE['enable']) {

    try {
        $FUTBuyer->connection($INI_FILE['email'], $INI_FILE['password'], $INI_FILE['secret']);
        usleep($CONFIG['wainting_time']);
        $TRADEPILE = $FUTBuyer->tradePile();
        if (isset($TRADEPILE['code'])) {
            Log::E('== Code '.$TRADEPILE['code'].', reason: '.$TRADEPILE['reason']);
        }
    } catch (Exception $e) {
        Log::E('Connexion impossible');
        Log::E('== Code '.$e->getCode().', reason: '.$e->getMessage());
        exit;
    }

    $CREDITS = $TRADEPILE['credits'];
    FUTStat::setCredits($CREDITS);
    $TRADESIZE = sizeof($TRADEPILE['auctionInfo']);

    //Récupération des credits
    Log::I('====== Connexion réussie. [Credits: '.$CREDITS.']');
    usleep($CONFIG['wainting_time'] * 2);

    // Inclusion du mode achat/revente rapide
    if ($INI_FILE['allow_quick']) {
        include 'market/market_quick.php';
    }

    // Inclusion du mode achat/revente
    if ($INI_FILE['allow_trade']) {
        include 'market/market_trade.php';
    }

    include 'market/market_clean.php';

    Log::I('====== Termine.');
}
