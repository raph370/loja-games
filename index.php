<?php include 'conexao.php'; ?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Loja de Games</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: Arial; background:#0f0f1a; color:#fff; }
        header { background:#1a1a2e; padding:20px; text-align:center; }
        header h1 { color:#e94560; font-size:2em; }
        nav { text-align:center; padding:15px; background:#1a1a2e; }
        nav a { color:#e94560; margin:0 15px; text-decoration:none; font-weight:bold; }
        .jogos { display:flex; flex-wrap:wrap; gap:20px; padding:30px; justify-content:center; }
        .card { background:#16213e; border-radius:10px; padding:20px; width:250px; }
        .card img { width:100%; border-radius:8px; margin-bottom:10px; height:180px; object-fit:cover; }
        .card h3 { color:#e94560; margin-bottom:10px; }
        .card p { color:#aaa; font-size:0.9em; margin-bottom:8px; }
        .preco { color:#00ff88; font-size:1.2em; font-weight:bold; }
        .btn { display:inline-block; margin-top:10px; padding:8px 16px; background:#e94560; color:#fff; border-radius:5px; text-decoration:none; }
        .btn-del { background:#0f3460; margin-left:5px; }
    </style>
</head>
<body>
<header><h1>🎮 Loja de Games</h1></header>
<nav>
    <a href="promocoes.php">🔥 Promoções</a>
    <a href="index.php">Home</a>
    <a href="adicionar.php">Adicionar Jogo</a>
</nav>
<div class="jogos">
<?php
$result = mysqli_query($conn, "SELECT * FROM jogos ORDER BY criado_em DESC");
if (mysqli_num_rows($result) == 0) {
    echo '<p style="color:#aaa;padding:30px">Nenhum jogo cadastrado ainda.</p>';
} else {
    while ($j = mysqli_fetch_assoc($result)) {
        $img = $j['imagem'] ? '<img src="imagens/'.$j['imagem'].'" alt="capa">' : '';
        echo '<div class="card">'.$img.'
        <h3>'.htmlspecialchars($j['nome']).'</h3>
        <p>'.htmlspecialchars($j['descricao']).'</p>
        <p>Categoria: '.$j['categoria'].'</p>
        <p>Estoque: '.$j['estoque'].'</p>
        <span class="preco">R$ '.number_format($j['preco'],2,",",".").'</span><br>
        <a href="editar.php?id='.$j['id'].'" class="btn">Editar</a>
        <a href="deletar.php?id='.$j['id'].'" class="btn btn-del" onclick="return confirm(\'Deletar?\')">Deletar</a>
        </div>';
    }
}
?>
</div>
</body>
</html>
