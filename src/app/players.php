<?php

    $players = json_decode(file_get_contents('assets/players.json'), true);

    //Tri par nom dans l'ordre alphabetique
    function cmp($p1, $p2)
    {
        if ($p1['l'] == $p2['l']) {
            return 0;
        }

        return ($p1['l'] < $p2['l']) ? -1 : 1;
    }

    usort($players['Players'], 'cmp');

    //Affichage
    foreach ($players['Players'] as $p) {
        echo '<div class="container">
                    <div class="name">'.$p['f'].' '.$p['l'].'</div>
                    <div class="skills">id: '.$p['id'].', r: '.$p['r'].' nationalité: '.$p['n'].'</div>
            </div>';
    }
?>
