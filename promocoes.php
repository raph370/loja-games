<?php
include 'conexao.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $preco_promo = $_POST['preco_promo'];
    $em_promocao = isset($_POST['em_promocao']) ? 1 : 0;
    mysqli_query($conn, "UPDATE jogos SET preco_promo='$preco_promo', em_promocao='$em_promocao' WHERE id=$id");
    header('Location: promocoes.php');
}
$jogos = mysqli_query($conn, "SELECT * FROM jogos ORDER BY nome");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerenciar Promoções</title>
    <style>
        body { font-family:Arial; background:#0f0f1a; color:#fff; }
        header { background:#1a1a2e; padding:20px; text-align:center; }
        header h1 { color:#e94560; }
        .container { max-width:800px; margin:30px auto; padding:0 20px; }
        .card { background:#16213e; border-radius:10px; padding:20px; margin-bottom:15px; display:flex; align-items:center; gap:20px; }
        .card img { width:80px; height:60px; object-fit:cover; border-radius:8px; }
        .card-info { flex:1; }
        .card-info h3 { color:#e94560; margin-bottom:5px; }
        .card-info p { color:#aaa; font-size:0.85em; }
        .preco-original { color:#aaa; text-decoration:line-through; font-size:0.9em; }
        .preco-promo { color:#00ff88; font-weight:bold; }
        input[type=number] { padding:8px; background:#0f3460; border:none; border-radius:6px; color:#fff; width:120px; }
        .btn { padding:8px 16px; background:#e94560; color:#fff; border:none; border-radius:6px; cursor:pointer; }
        .badge-promo { background:#e94560; color:#fff; padding:2px 8px; border-radius:10px; font-size:0.75em; margin-left:8px; }
        a { color:#e94560; }
        nav { text-align:center; padding:15px; background:#1a1a2e; }
        nav a { color:#e94560; margin:0 15px; text-decoration:none; font-weight:bold; }
        label { color:#aaa; font-size:0.9em; }
    </style>
</head>
<body>
<header><h1>🔥 Gerenciar Promoções</h1></header>
<nav>
    <a href="index.php">← Painel Admin</a>
    <a href="loja.php">Ver Loja</a>
</nav>
<div class="container">
<?php while ($j = mysqli_fetch_assoc($jogos)): ?>
<div class="card">
    <?php if($j['imagem']): ?>
    <img src="imagens/<?=$j['imagem']?>" alt="capa">
    <?php endif; ?>
    <div class="card-info">
        <h3><?=htmlspecialchars($j['nome'])?>
            <?php if($j['em_promocao']): ?>
            <span class="badge-promo">🔥 PROMO</span>
            <?php endif; ?>
        </h3>
        <p>Preço normal: <span class="preco-original">R$ <?=number_format($j['preco'],2,",",".")?></span></p>
        <?php if($j['preco_promo']): ?>
        <p>Preço promo: <span class="preco-promo">R$ <?=number_format($j['preco_promo'],2,",",".")?></span></p>
        <?php endif; ?>
    </div>
    <form method="POST">
        <input type="hidden" name="id" value="<?=$j['id']?>">
        <label>Preço promo</label><br>
        <input type="number" name="preco_promo" step="0.01" value="<?=$j['preco_promo']?>" placeholder="0.00"><br><br>
        <label><input type="checkbox" name="em_promocao" <?=$j['em_promocao']?'checked':''?>> Ativar promoção</label><br><br>
        <button type="submit" class="btn">Salvar</button>
    </form>
</div>
<?php endwhile; ?>
</div>
</body>
</html>
