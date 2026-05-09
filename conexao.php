<?php
$host = 'localhost';
$usuario = 'admin';
$senha = 'senha123';
$banco = 'loja_games';

$conn = mysqli_connect($host, $usuario, $senha, $banco);

if (!$conn) {
    die('Erro na conexão: ' . mysqli_connect_error());
}
?>
