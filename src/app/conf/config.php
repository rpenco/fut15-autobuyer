<?php

use Fut\Fifa\Constant\Team;
use Fut\Fifa\Constant\Position;
use Fut\Fifa\Constant\Nation;

//Identifiant FIFA 15 et CONFIG générale du programme
$INI_FILE = parse_ini_file("config.ini");

//Configuration pour les différents modes
$CONFIG = array(

    //Mode trade
    'sold_player'               => 0,       //Joueur à vendre (pour le calcul des prix de vente)
    'buy_player'                => 0,       //Joueur à acheter (pour le calcul des prix d'achat)
    'min_credit_left'           => 2000,    //Nombre de crédit min disponible (pas d'achat en dessous)

    'treatment_by_round'        => 3,       //Nombre de joueurs en attente de vente à traiter par minute (pas trop pour �viter pb nombreuse requ�te -> captcha)
    'treatment_by_round_trade_buy'    => 3,       //Nombre de joueurs en attente de vente à traiter par minute (pas trop pour �viter pb nombreuse requ�te -> captcha)
    'players_in_trade'          => 20,      //Nombre de joueurs dans la file d'attente (max  ~30)
    'wainting_time'             => 700000,  //Pour ralentir le nombre requete/seconde (0.5s)
    'number_pages_search'       => 10,      //Nombre de page à rechercher pour le quicksell (x*50 joueurs).

    'profils' => array(

        0 => array(
            'player' => array(
                'name' => 'Ter Stegen',
                'assetId' => 192448,
                'lev' => 'gold',
                'nat' => Nation::GERMANY,
                'team' => Team::FCBarcelona,
                'pos' => Position::G
            ),
            'trade' => array(
                'max_price' => 700,             //Prix d'achat max
                'sold_starting_bid' => 1000,    //Prix de vente min (au enchère) [attention au 5% de taxe]
                'sold_buy_now_price' => 1200    //Prix de revente en achat immédiat [attention au 5% de taxe]
            )
        ),

        1 => array(
            'player' => array(
                'name'=> 'Edinson Cavani',
                'assetId' => 179813,
                'lev' => 'gold',
                'nat' => Nation::URUGUAY,
                'team'=> Team::ParisSaintGermain,
                'pos' => Position::BU
            ),
            'trade' => array(
                'max_price' => 6200,             //Prix max d'achat
                'sold_starting_bid' => 6700,    //Prix de vente min (au ench�re)
                'sold_buy_now_price' => 14000    //Prix de revente en achat imm�diat
            )
        ),
        2 => array(
            'player' => array(
                 'name'=> 'Mezut Ozil',
                'assetId' => 176635,
                'lev' => 'gold',
                'nat' => Nation::GERMANY,
                'team'=> Team::Arsenal,
                'pos' => Position::MOC
            ),
            'trade' => array(
                'max_price' => 700,
                'sold_starting_bid' => 1000,
                'sold_buy_now_price' => 1400
            )
        ),
        3 => array(
            'player' => array(
                'name'=> 'Alexandre Lacazette',
                'assetId' => 193301,
                'lev' => 'gold',
                'nat' => Nation::FRANCE,
                'team'=> Team::OlympiqueLyonnais,
                'pos' => Position::BU
            ),
            'trade' => array(
                'max_price' => 700,
                'sold_starting_bid' => 1000,
                'sold_buy_now_price' => 1600
            )
        ),
		4 => array(
            'player' => array(
                'name'=> 'Fabian Schär',
                'assetId' => 210047,
                'lev' => 'gold',
                'nat' => Nation::SWITZERLAND,
                'team'=> Team::FCBasel,
                'pos' => Position::DC
            ),
            'trade' => array(
                'max_price' => 300,
                'sold_starting_bid' => 1000,
                'sold_buy_now_price' => 1600
            )
        ),
    )
);

//Pour faciliter la suite...
$SOLD_PLAYER = $CONFIG['profils'][$CONFIG['sold_player']];
$BUY_PLAYER = $CONFIG['profils'][$CONFIG['buy_player']];
