<?php


include_once './database/db.class.php';


//instanciar um objeto da classe DB
$conn = new db("usuario");

$dados = [
    'nome' =>"Caroline Matte",
    'telefone' => "49 8504-0369",
    'email' => 'caroline.m2007@aluno.ifsc.edu.br',

];

$conn->store($dados);
echo "Inserido com Sucesso!";