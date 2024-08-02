<?php

require_once('config/iniSis.php');
require_once('config/autoload.php');

$hm = new Home;

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="src/style.css?v=<?=VERSION?>">
    <title><?= TITULO ?></title>
    <meta property="og:site_name" content="<?=TITULO?>">
    <meta property="og:title" content="<?=TITULO?>" />
    <meta property="og:description" content="<?=DESCRICAO?>" />
</head>
<body>
    <div class="treemap-container">
        <?php
            $jogos = $hm->listagem();
            foreach ($jogos as $j) {
                $info = $hm->jogo($j['appid']);
                $porc = number_format($hm->porcentagem($j['peak_in_game']));
                echo '
                <div class="box" style="grid-column: span '.$porc.'; grid-row: span '.$porc.';"">
                    <span><b>'.$info->{$j['appid']}->data->name.'</b></span>
                    <span>Jogadores online : <b>'.$j['peak_in_game'].'</b></span>
                    <span>Porcenteagem : <b>'.$porc.'%</b></span>
                </div>';
            }
        ?>
    </div>
</body>
</html>