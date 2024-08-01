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
    <script src="https://d3js.org/d3.v6.min.js"></script>
</head>
<body>
    <?php
        $jogos = $hm->listagem();
        foreach ($jogos as $j) {
            $info = $hm->jogo($j['appid']);
            $dados[] = [
                'name' => $info->{$j['appid']}->data->name,
                'value' => $j['peak_in_game'],
                'percent' => $hm->porcentagem($j['peak_in_game'])
            ];
        }
        $jsonDados = json_encode($dados);
    ?>
    <div id="treemap"></div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Dados do PHP
        const data = <?php echo $jsonDados; ?>;

        const width = window.innerWidth;
        const height = window.innerHeight;

        const svg = d3.select('#treemap')
            .append('svg')
            .attr('width', width)
            .attr('height', height);

            
        // Encontrar os valores mínimo e máximo
        const values = data.map(d => d.value);
        const minValue = d3.min(values);
        const maxValue = d3.max(values);

        // Criar uma escala de cores
        const colorScale = d3.scaleLinear()
            .domain([minValue, maxValue])
            .range(['#8bc34a', '#4caf50']); // Verde claro a verde escuro

        const root = d3.hierarchy({values: data}, d => d.values)
            .sum(d => d.value)
            .sort((a, b) => b.value - a.value);

        d3.treemap()
            .size([width, height])
            .padding(1)
            (root);

        const cell = svg.selectAll('g')
            .data(root.leaves())
            .enter().append('g')
            .attr('transform', d => `translate(${d.x0},${d.y0})`);

        cell.append('rect')
            .attr('id', d => d.data.name)
            .attr('width', d => d.x1 - d.x0)
            .attr('height', d => d.y1 - d.y0)
            .attr('fill', d => colorScale(d.data.value));

        cell.append('text')
            .attr('x', 5)
            .attr('y', 20)
            .text(d => `${d.data.name} (${d.data.percent.toFixed(2)}%)`)
            .attr('font-size', '14px')
            .attr('fill', 'black');
    });
</script>
</body>
</html>