# FIFA ULTIMATE TEAM 15 - Autobuyer

> `Fifa Ultimate Team 15` is a game mode of the popular ` FIFA 15` soccer video game.

The program presented makes it possible to take advantage of the presence of a marketplace to buy players at a low price and resell them at a higher price, all in an automated manner.

This application is made in PHP, because it uses a library which allowed to connect to the servers (API) of `EA` by simulating an official application (android / xbox / playstation) and human activity.

## Execution

This program was running on an `Apache` server, during the 2014/2015 season. Since then **it is no longer compatible**.

## Extract from program activity logs

Below is an extract from the activity logs generated by the program.

```log
2015-01-26 18:44:07 [info] ====== Connexion réussie. [Credits: 10909]
2015-01-26 18:44:17 [info] 4 joueurs sous-côtés trouvés.
2015-01-26 18:44:17 [debu] Estimation bénef.: 12, tradeId: 153267150248, startingBid: 300, currentBid: 0, buyNowPrice: 650, nextBid: 300, discardValue: 312, expire: 37
2015-01-26 18:44:17 [debu] Estimation bénef.: 8, tradeId: 153267144243, startingBid: 600, currentBid: 0, buyNowPrice: 650, nextBid: 600, discardValue: 608, expire: 38
2015-01-26 18:44:17 [debu] Estimation bénef.: 16, tradeId: 153267175803, startingBid: 300, currentBid: 0, buyNowPrice: 400, nextBid: 300, discardValue: 316, expire: 62
2015-01-26 18:44:17 [debu] Estimation bénef.: 8, tradeId: 153267218487, startingBid: 300, currentBid: 0, buyNowPrice: 500, nextBid: 300, discardValue: 308, expire: 65
2015-01-26 18:44:18 [info] Quick buy réussi... attente fin des enchères.
2015-01-26 18:44:21 [info] ====== Crédit: [10909 points], Budget: [8909 points]
2015-01-26 18:44:26 [info] Revente rapide [+8] - tradeId: 153266980857, lastSalePrice: 300,  startingBid: 300, currentBid: 300, buyNowPrice: 400, discardValue: 308, expires: -1
2015-01-26 18:44:31 [info] ====== Termine.
2015-01-26 18:48:07 [info] ====== Connexion réussie. [Credits: 10917]
2015-01-26 18:48:17 [info] 10 joueurs sous-côtés trouvés.
2015-01-26 18:48:17 [debu] Estimation bénef.: 12, tradeId: 153267364380, startingBid: 300, currentBid: 0, buyNowPrice: 500, nextBid: 300, discardValue: 312, expire: 47
2015-01-26 18:48:17 [debu] Estimation bénef.: 8, tradeId: 153267355574, startingBid: 600, currentBid: 0, buyNowPrice: 700, nextBid: 600, discardValue: 608, expire: 52
2015-01-26 18:48:17 [debu] Estimation bénef.: 16, tradeId: 153267368019, startingBid: 300, currentBid: 0, buyNowPrice: 450, nextBid: 300, discardValue: 316, expire: 60
2015-01-26 18:48:18 [debu] Estimation bénef.: 4, tradeId: 153267401748, startingBid: 300, currentBid: 0, buyNowPrice: 650, nextBid: 300, discardValue: 304, expire: 62
2015-01-26 18:48:18 [debu] Estimation bénef.: 4, tradeId: 153267368301, startingBid: 300, currentBid: 0, buyNowPrice: 350, nextBid: 300, discardValue: 304, expire: 66
2015-01-26 18:48:18 [debu] Estimation bénef.: 16, tradeId: 153267402116, startingBid: 300, currentBid: 0, buyNowPrice: 400, nextBid: 300, discardValue: 316, expire: 68
2015-01-26 18:48:18 [debu] Estimation bénef.: 4, tradeId: 153267426327, startingBid: 300, currentBid: 0, buyNowPrice: 550, nextBid: 300, discardValue: 304, expire: 69
2015-01-26 18:48:18 [debu] Estimation bénef.: 4, tradeId: 153267374669, startingBid: 300, currentBid: 0, buyNowPrice: 350, nextBid: 300, discardValue: 304, expire: 71
2015-01-26 18:48:18 [debu] Estimation bénef.: 4, tradeId: 153267387099, startingBid: 300, currentBid: 0, buyNowPrice: 350, nextBid: 300, discardValue: 304, expire: 74
2015-01-26 18:48:18 [info] Quick buy réussi... attente fin des enchères.
2015-01-26 18:48:19 [debu] Estimation bénef.: 4, tradeId: 153267387188, startingBid: 300, currentBid: 0, buyNowPrice: 400, nextBid: 300, discardValue: 304, expire: 75
2015-01-26 18:48:19 [info] Quick buy réussi... attente fin des enchères.
2015-01-26 18:48:22 [info] ====== Crédit: [10917 points], Budget: [8917 points]
2015-01-26 18:48:27 [info] Revente rapide [+8] - tradeId: 153267218487, lastSalePrice: 300,  startingBid: 300, currentBid: 300, buyNowPrice: 500, discardValue: 308, expires: -1
2015-01-26 18:48:32 [info] ====== Termine.
2015-01-26 18:52:08 [info] ====== Connexion réussie. [Credits: 10625]
2015-01-26 18:52:19 [info] 2 joueurs sous-côtés trouvés.
2015-01-26 18:52:19 [debu] Estimation bénef.: 4, tradeId: 153267605608, startingBid: 300, currentBid: 0, buyNowPrice: 550, nextBid: 300, discardValue: 304, expire: 52
2015-01-26 18:52:19 [debu] Estimation bénef.: 16, tradeId: 153267596844, startingBid: 300, currentBid: 0, buyNowPrice: 600, nextBid: 300, discardValue: 316, expire: 55
2015-01-26 18:52:22 [info] ====== Crédit: [10625 points], Budget: [8625 points]
2015-01-26 18:52:26 [info] Revente rapide [+4] - tradeId: 153267387099, lastSalePrice: 300,  startingBid: 300, currentBid: 300, buyNowPrice: 350, discardValue: 304, expires: -1
2015-01-26 18:52:29 [info] Revente rapide [+4] - tradeId: 153267387188, lastSalePrice: 300,  startingBid: 300, currentBid: 300, buyNowPrice: 400, discardValue: 304, expires: -1
2015-01-26 18:52:34 [info] ====== Termine.


2015-01-27 22:12:07 [info] ====== Connexion réussie. [Credits: 18943]
2015-01-27 22:12:17 [info] 3 joueurs sous-côtés trouvés.
2015-01-27 22:12:17 [debu] Estimation bénef.: 4, tradeId: 153328413217, startingBid: 300, currentBid: 0, buyNowPrice: 450, nextBid: 300, discardValue: 304, expire: 46
2015-01-27 22:12:17 [debu] Estimation bénef.: 208, tradeId: 153334854284, startingBid: 400, currentBid: 0, buyNowPrice: 700, nextBid: 400, discardValue: 608, expire: 55
2015-01-27 22:12:17 [debu] Estimation bénef.: 16, tradeId: 153334885615, startingBid: 600, currentBid: 0, buyNowPrice: 650, nextBid: 600, discardValue: 616, expire: 68
2015-01-27 22:12:17 [info] Quick buy réussi... attente fin des enchères.
2015-01-27 22:12:20 [info] ====== Crédit: [18943 points], Budget: [16943 points]
2015-01-27 22:12:30 [info] ====== Termine.
2015-01-27 22:16:06 [info] ====== Connexion réussie. [Credits: 18343]
2015-01-27 22:16:17 [info] 9 joueurs sous-côtés trouvés.
2015-01-27 22:16:17 [debu] Estimation bénef.: 4, tradeId: 153335058543, startingBid: 300, currentBid: 0, buyNowPrice: 400, nextBid: 300, discardValue: 304, expire: 46
2015-01-27 22:16:17 [debu] Estimation bénef.: 8, tradeId: 153335022365, startingBid: 600, currentBid: 0, buyNowPrice: 650, nextBid: 600, discardValue: 608, expire: 53
2015-01-27 22:16:18 [debu] Estimation bénef.: 8, tradeId: 153335028360, startingBid: 600, currentBid: 0, buyNowPrice: 650, nextBid: 600, discardValue: 608, expire: 53
2015-01-27 22:16:18 [debu] Estimation bénef.: 4, tradeId: 153328633916, startingBid: 300, currentBid: 0, buyNowPrice: 450, nextBid: 300, discardValue: 304, expire: 58
2015-01-27 22:16:18 [debu] Estimation bénef.: 8, tradeId: 153335034714, startingBid: 600, currentBid: 0, buyNowPrice: 650, nextBid: 600, discardValue: 608, expire: 58
2015-01-27 22:16:18 [debu] Estimation bénef.: 8, tradeId: 153335083954, startingBid: 600, currentBid: 0, buyNowPrice: 650, nextBid: 600, discardValue: 608, expire: 61
2015-01-27 22:16:18 [debu] Estimation bénef.: 8, tradeId: 153335050446, startingBid: 600, currentBid: 0, buyNowPrice: 650, nextBid: 600, discardValue: 608, expire: 62
2015-01-27 22:16:18 [debu] Estimation bénef.: 8, tradeId: 153335080858, startingBid: 600, currentBid: 0, buyNowPrice: 700, nextBid: 600, discardValue: 608, expire: 62
2015-01-27 22:16:18 [debu] Estimation bénef.: 16, tradeId: 153335078247, startingBid: 600, currentBid: 0, buyNowPrice: 700, nextBid: 600, discardValue: 616, expire: 68
2015-01-27 22:16:21 [info] ====== Crédit: [18343 points], Budget: [16343 points]
2015-01-27 22:16:26 [info] Revente rapide [+16] - tradeId: 153334885615, lastSalePrice: 600,  startingBid: 600, currentBid: 600, buyNowPrice: 650, discardValue: 616, expires: -1
2015-01-27 22:16:34 [info] Remis en vente - startingBid: 1200, buyNowPrice: 1400, discardValue: 656
2015-01-27 22:16:38 [info] Remis en vente - startingBid: 1200, buyNowPrice: 1400, discardValue: 656
2015-01-27 22:16:41 [info] Remis en vente - startingBid: 1200, buyNowPrice: 1400, discardValue: 656
2015-01-27 22:16:41 [info] ====== Termine.
```

### Ban / request for validation

If the automatic activity was detected by the `EA / FIFA` servers, the latter refused the connection and requested the validation of a *captcha*. The program was therefore blocked.

```log
2015-02-01 13:56:06 [erro] == Code 401, reason: Client error response [url] https://utas.s2.fut.ea.com/ut/game/fifa15/phishing/validate?answer=7860d4d6aa29c2b3458597005653df34&timestamp= [status code] 401 [reason phrase] Unauthorized
2015-02-01 14:00:07 [erro] Connexion impossible
2015-02-01 14:00:07 [erro] == Code 459, reason: Client error response [url] https://utas.s2.fut.ea.com/ut/game/fifa15/tradepile [status code] 459 [reason phrase] 459
2015-02-01 14:04:06 [erro] Connexion impossible
2015-02-01 14:04:06 [erro] == Code 401, reason: Client error response [url] https://utas.s2.fut.ea.com/ut/game/fifa15/phishing/validate?answer=7860d4d6aa29c2b3458597005653df34&timestamp= [status code] 401 [reason phrase] Unauthorized
2015-02-01 14:08:06 [erro] Connexion impossible
2015-02-01 14:08:06 [erro] == Code 401, reason: Client error response [url] https://utas.s2.fut.ea.com/ut/game/fifa15/phishing/validate?answer=7860d4d6aa29c2b3458597005653df34&timestamp= [status code] 401 [reason phrase] Unauthorized
```


### Program statistics

Below are listed the daily statistics of the program.


```json
{
    "2015-01-26": {
        "credits": 9269,
        "players_quicksell": 29,
        "quick_benef": 180,
        "players_bought": 3
    },
    "2015-01-27": {
        "credits": 10391,
        "players_quicksell": 34,
        "quick_benef": 296,
        "players_bought": 20
    },
    "2015-01-28": {
        "credits": 20817,
        "players_quicksell": 131,
        "quick_benef": 1514,
        "players_bought": 20
    },
    "2015-01-29": {
        "credits": 39038,
        "players_quicksell": 130,
        "quick_benef": 946,
        "players_bought": 7
    },
    "2015-01-30": {
        "credits": 46622,
        "players_quicksell": 98,
        "quick_benef": 734,
        "players_bought": 27
    }
}
```
