<?php
include 'conexao.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
$uid = $_SESSION['usuario_id'];

// Adicionar ao carrinho
if (isset($_GET['adicionar'])) {
    $jid = $_GET['adicionar'];
    $check = mysqli_query($conn, "SELECT id, quantidade FROM carrinho WHERE usuario_id=$uid AND jogo_id=$jid");
    if (mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);
        mysqli_query($conn, "UPDATE carrinho SET quantidade=quantidade+1 WHERE id=".$row['id']);
    } else {
        mysqli_query($conn, "INSERT INTO carrinho (usuario_id, jogo_id) VALUES ($uid, $jid)");
    }
    header('Location: carrinho.php');
    exit;
}

// Remover do carrinho
if (isset($_GET['remover'])) {
    $cid = $_GET['remover'];
    mysqli_query($conn, "DELETE FROM carrinho WHERE id=$cid AND usuario_id=$uid");
    header('Location: carrinho.php');
    exit;
}

// Finalizar pedido
if (isset($_GET['finalizar'])) {
    mysqli_query($conn, "DELETE FROM carrinho WHERE usuario_id=$uid");
    $finalizado = true;
}

$items = mysqli_query($conn, "
    SELECT c.id, c.quantidade, j.nome, j.imagem, j.categoria,
    CASE WHEN j.em_promocao=1 AND j.preco_promo IS NOT NULL THEN j.preco_promo ELSE j.preco END as preco_final
    FROM carrinho c JOIN jogos j ON c.jogo_id=j.id
    WHERE c.usuario_id=$uid
");
$total = 0;
$rows = [];
while ($r = mysqli_fetch_assoc($items)) { $rows[] = $r; $total += $r['preco_final'] * $r['quantidade']; }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Carrinho</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:Arial; background:#0f0f1a; color:#fff; }
        header { background:#1a1a2e; padding:20px 30px; display:flex; justify-content:space-between; align-items:center; }
        header h1 { color:#e94560; }
        header a { color:#e94560; text-decoration:none; }
        .container { max-width:700px; margin:30px auto; padding:0 20px; }
        .item { background:#16213e; border-radius:10px; padding:15px; margin-bottom:15px; display:flex; align-items:center; gap:15px; }
        .item img { width:80px; height:60px; object-fit:cover; border-radius:8px; }
        .item-info { flex:1; }
        .item-info h3 { color:#e94560; margin-bottom:5px; }
        .item-info p { color:#aaa; font-size:0.85em; }
        .preco { color:#00ff88; font-weight:bold; font-size:1.1em; }
        .qty { display:flex; align-items:center; gap:10px; margin-top:8px; }
        .qty a { background:#0f3460; color:#fff; padding:4px 10px; border-radius:6px; text-decoration:none; font-size:1.1em; }
        .btn-remover { background:#e94560; color:#fff; padding:6px 14px; border-radius:6px; text-decoration:none; font-size:0.85em; }
        .total { background:#16213e; border-radius:10px; padding:20px; text-align:center; margin-top:20px; }
        .total h2 { color:#00ff88; font-size:1.8em; margin-bottom:15px; }
        .btn-finalizar { background:#00ff88; color:#0f0f1a; padding:14px 40px; border-radius:10px; text-decoration:none; font-size:1.1em; font-weight:bold; display:inline-block; }
        .btn-continuar { background:#0f3460; color:#fff; padding:14px 30px; border-radius:10px; text-decoration:none; font-size:1em; display:inline-block; margin-right:10px; }
        .vazio { text-align:center; padding:50px; color:#aaa; }
        .vazio a { color:#e94560; }
        .sucesso { background:#0f3460; border:1px solid #00ff88; border-radius:10px; padding:20px; text-align:center; margin-bottom:20px; }
        .sucesso h2 { color:#00ff88; }
        .sucesso p { color:#aaa; margin-top:8px; }
    </style>
</head>
<body>
<header>
    <h1>🛒 Carrinho</h1>
    <a href="loja.php">← Voltar à loja</a>
</header>
<div class="container">

<?php if (isset($finalizado)): ?>
<div class="sucesso">
    <h2>✅ Pedido Finalizado!</h2>
    <p>Obrigado pela compra, <?=htmlspecialchars($_SESSION['usuario_nome'])?>! Em breve entraremos em contato.</p>
</div>
<?php endif; ?>

<?php if (empty($rows)): ?>
<div class="vazio">
    <p style="font-size:3em">🛒</p>
    <p style="margin:15px 0">Seu carrinho está vazio!</p>
    <a href="loja.php">← Ver jogos</a>
</div>
<?php else: ?>
    <?php foreach ($rows as $r): ?>
    <div class="item">
        <?php if($r['imagem']): ?>
        <img src="imagens/<?=$r['imagem']?>" alt="capa">
        <?php else: ?>
        <div style="width:80px;height:60px;background:#0f3460;border-radius:8px;display:flex;align-items:center;justify-content:center;font-size:2em">🎮</div>
        <?php endif; ?>
        <div class="item-info">
            <h3><?=htmlspecialchars($r['nome'])?></h3>
            <p><?=$r['categoria']?></p>
            <p class="preco">R$ <?=number_format($r['preco_final'],2,",",".")?> x <?=$r['quantidade']?> = R$ <?=number_format($r['preco_final']*$r['quantidade'],2,",",".")?></p>
            <div class="qty">
                <a href="carrinho.php?remover=<?=$r['id']?>">🗑 Remover</a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <div class="total">
        <h2>Total: R$ <?=number_format($total,2,",",".")?></h2>
        <a href="loja.php" class="btn-continuar">← Continuar comprando</a>
        <a href="carrinho.php?finalizar=1" class="btn-finalizar" onclick="return confirm('Finalizar pedido?')">✅ Finalizar Pedido</a>
    </div>
<?php endif; ?>
</div>
</body>
</html>
