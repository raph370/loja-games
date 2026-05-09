<?php
include 'conexao.php';
$id = $_GET['id'];
mysqli_query($conn, "DELETE FROM jogos WHERE id=$id");
header('Location: index.php');
?>
