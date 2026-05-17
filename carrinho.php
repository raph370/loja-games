<?php
include 'conexao.php';
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
$uid = $_SESSION['usuario_id'];

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

if (isset($_GET['remover'])) {
    $cid = $_GET['remover'];
    mysqli_query($conn, "DELETE FROM carrinho WHERE id=$cid AND usuario_id=$uid");
    header('Location: carrinho.php');
    exit;
}

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho — GameStore</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',Arial,sans-serif; background:#0a0a0f; color:#fff; }
        nav { background:#111118; padding:0 30px; display:flex; align-items:center; justify-content:space-between; height:60px; border-bottom:1px solid #1e1e2e; position:sticky; top:0; z-index:100; }
        .logo { color:#4fc3f7; font-size:1.5em; font-weight:700; letter-spacing:2px; text-decoration:none; }
        .logo span { color:#fff; }
        .nav-right { display:flex; align-items:center; gap:15px; }
        .btn-nav { padding:7px 16px; border-radius:6px; text-decoration:none; font-size:0.85em; font-weight:600; }
        .btn-carrinho { background:#1e1e2e; color:#fff; border:1px solid #2e2e3e; }
        .btn-sair { background:transparent; color:#aaa; border:1px solid #333; }
        .container { max-width:700px; margin:30px auto; padding:0 20px; }
        h2 { color:#4fc3f7; margin-bottom:25px; font-size:1.4em; border-left:3px solid #4fc3f7; padding-left:12px; }
        .item { background:#111118; border-radius:10px; padding:15px; margin-bottom:15px; display:flex; align-items:center; gap:15px; border:1px solid #1e1e2e; }
        .item img { width:100px; height:60px; object-fit:cover; border-radius:8px; }
        .item-sem-img { width:100px; height:60px; background:#1e1e2e; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:1.8em; flex-shrink:0; }
        .item-info { flex:1; }
        .item-info h3 { color:#fff; margin-bottom:4px; font-size:1em; }
        .item-info p { color:#aaa; font-size:0.82em; margin-bottom:6px; }
        .preco { color:#4fc3f7; font-weight:700; font-size:1em; }
        .qty { display:flex; align-items:center; gap:8px; margin-top:8px; }
        .qty span { color:#aaa; font-size:0.85em; }
        .btn-remover { background:#e94560; color:#fff; padding:5px 12px; border-radius:6px; text-decoration:none; font-size:0.8em; border:none; cursor:pointer; }
        .total-box { background:#111118; border-radius:12px; padding:25px; text-align:center; margin-top:20px; border:1px solid #1e1e2e; }
        .total-box h2 { color:#4fc3f7; font-size:1.8em; margin-bottom:20px; border:none; padding:0; }
        .btn-finalizar { background:#4fc3f7; color:#0a0a0f; padding:14px 40px; border-radius:10px; text-decoration:none; font-size:1.1em; font-weight:700; display:inline-block; border:none; cursor:pointer; }
        .btn-continuar { background:#1e1e2e; color:#fff; padding:14px 30px; border-radius:10px; text-decoration:none; font-size:1em; display:inline-block; margin-right:10px; border:1px solid #2e2e3e; }
        .vazio { text-align:center; padding:60px; color:#aaa; }
        .vazio p { font-size:3em; margin-bottom:15px; }
        .vazio a { color:#4fc3f7; text-decoration:none; }
        .sucesso { background:#0f1e0f; border:1px solid #4caf50; border-radius:10px; padding:20px; text-align:center; margin-bottom:20px; }
        .sucesso h2 { color:#4caf50; border:none; padding:0; }
        .sucesso p { color:#aaa; margin-top:8px; }
        footer { background:#111118; border-top:1px solid #1e1e2e; padding:20px; text-align:center; color:#555; font-size:0.85em; margin-top:30px; }
        footer span { color:#4fc3f7; }
    </style>
</head>
<body>
<nav>
    <a href="loja.php" class="logo">GAME<span>STORE</span></a>
    <div class="nav-right">
        <span style="color:#aaa;font-size:0.85em">👤 <?=htmlspecialchars($_SESSION['usuario_nome'])?></span>
        <a href="logout.php" class="btn-nav btn-sair">Sair</a>
    </div>
</nav>

<div class="container">
    <h2>🛒 Meu Carrinho</h2>

    <?php if (isset($finalizado)): ?>
    <div class="sucesso">
        <h2>✅ Pedido Finalizado!</h2>
        <p>Obrigado pela compra, <?=htmlspecialchars($_SESSION['usuario_nome'])?>! Em breve entraremos em contato.</p>
    </div>
    <?php endif; ?>

    <?php if (empty($rows)): ?>
    <div class="vazio">
        <p>🛒</p>
        <p style="font-size:1em;margin-bottom:15px">Seu carrinho está vazio!</p>
        <a href="loja.php">← Ver jogos</a>
    </div>
    <?php else: ?>
        <?php foreach ($rows as $r): ?>
        <div class="item">
            <?php
            if ($r['imagem'] && strpos($r['imagem'], 'http') === 0) {
                echo '<img src="'.$r['imagem'].'" alt="'.htmlspecialchars($r['nome']).'">';
            } elseif ($r['imagem']) {
                echo '<img src="imagens/'.$r['imagem'].'" alt="'.htmlspecialchars($r['nome']).'">';
            } else {
                echo '<div class="item-sem-img">🎮</div>';
            }
            ?>
            <div class="item-info">
                <h3><?=htmlspecialchars($r['nome'])?></h3>
                <p><?=$r['categoria']?></p>
                <p class="preco">R$ <?=number_format($r['preco_final'],2,",",".")?> × <?=$r['quantidade']?> = <strong>R$ <?=number_format($r['preco_final']*$r['quantidade'],2,",",".")?></strong></p>
                <div class="qty">
                    <a href="carrinho.php?remover=<?=$r['id']?>" class="btn-remover">🗑 Remover</a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <div class="total-box">
            <h2>Total: R$ <?=number_format($total,2,",",".")?></h2>
            <a href="loja.php" class="btn-continuar">← Continuar comprando</a>
            <a href="carrinho.php?finalizar=1" class="btn-finalizar" onclick="return confirm('Finalizar pedido?')">✅ Finalizar Pedido</a>
        </div>
    <?php endif; ?>
</div>
<footer>© 2026 <span>GameStore</span> — Todos os direitos reservados</footer>
</body>
</html>
