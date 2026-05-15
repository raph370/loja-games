<?php
$host = getenv('MYSQLHOST') ?: 'mysql.railway.internal';
$usuario = getenv('MYSQLUSER') ?: 'root';
$senha = getenv('MYSQLPASSWORD') ?: 'snLhcUzQdrSgdIYmmwMValauxOgDEtWN';
$banco = getenv('MYSQLDATABASE') ?: 'railway';
$porta = getenv('MYSQLPORT') ?: '3306';

$conn = mysqli_connect($host, $usuario, $senha, $banco, $porta);

if (!$conn) {
    die('Erro na conexão: ' . mysqli_connect_error());
}
?>
