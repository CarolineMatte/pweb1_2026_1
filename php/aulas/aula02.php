<?php


    $pessoas = [
        ["nome" =>"Lucas Fernades", "idade" => 17],
        ["nome" =>"Caroline Matte", "idade" => 18],
        ["nome" =>"Kamilla Vitória Matte", "idade" => 17],


    ];


    foreach ($pessoas as $key => $item) {
        $nome = $item['nome'];
        $idade = $item['idade'];
        echo "Indice: $key Nome: $nome Idade: $idade  <br>";
    }


?>
