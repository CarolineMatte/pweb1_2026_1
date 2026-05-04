<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        echo "Olá mundo PHP<br>";

        $nome = "Caroline Matte";
        $idade = 19;

        echo "Nome: $nome - Idade: $idade";

        echo "<br>";
        if ($idade >= 18) {
            echo "Maior de idade";
        } else{
            echo "Menor de idade";
        }

        $notas = [5, 7, 10, 9];

        //print_r($notas);
        echo "<br>";
        for ($i = 0; $i < count($notas); $i++){
            echo $notas[$i]. "<br>";
        }
        echo "<br>";
        foreach ($notas as $item) {
            echo $item. "<br>";
        }

        $nomes = ["Lucas", "Caroline", "Kamilla"];

        echo "<br>";
        for ($i = 0; $i < count($nomes); $i++){
            echo $nomes[$i]. "<br>";
        }
        echo "<br>";
        foreach ($nomes as $item) {
            echo $item. "<br>";
        }

        echo "<br>";
        $carros = [ 
            ['modelo' => "Mustang", 'cor' => "Branco", "ano" => 2026],
            ['modelo' => "Fusca", 'cor' => "Azul", "ano" => 1973],
            ['modelo' => "Brasília", 'cor' => "Amarela", "ano" => 1969],
        ];
        
        echo  $carros[0]['modelo']. " - ". $carros[0]['cor'];
        echo "<br>";

        // CORREÇÃO AQUI: Adicionado $indice => $carro para separar a chave do valor
        foreach ($carros as $indice => $carro) {
            echo ($indice + 1) . " - ";
            echo "Modelo: " . $carro['modelo'] . " - Ano: " . $carro['ano'];

            echo "<br>";
        }

    ?> 

    <p> Meu Site  <?= $carro['modelo']. " - ". $carro['ano'];  ?></p>

    <?php
    include "./aula02.php";
    ?>

</body>
</html>

