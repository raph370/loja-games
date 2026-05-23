<?php
include 'conexao.php';
session_start();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="google-site-verification" content="0-NKprxw9MOEHow6PuRoT9fq1kHNOESznPM0zdhBVdw" />
    <title>Admin — GameStore</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',Arial,sans-serif; background:#0a0a0f; color:#fff; }
        nav { background:#111118; padding:0 30px; display:flex; align-items:center; justify-content:space-between; height:60px; border-bottom:2px solid #e94560; position:sticky; top:0; z-index:100; }
        .logo { color:#e94560; font-size:1.5em; font-weight:700; letter-spacing:2px; }
        .logo span { color:#fff; }
        .nav-right { display:flex; align-items:center; gap:15px; }
        .btn-nav { padding:7px 16px; border-radius:6px; text-decoration:none; font-size:0.85em; font-weight:600; }
        .btn-loja { background:transparent; color:#e94560; border:1px solid #e94560; }
        .btn-promo { background:#e94560; color:#fff; }
        .btn-add { background:#00c853; color:#fff; }
        .secao { padding:30px; }
        .secao-titulo { font-size:1.2em; color:#aaa; margin-bottom:20px; border-left:3px solid #e94560; padding-left:12px; }
        .jogos { display:grid; grid-template-columns:repeat(auto-fill,minmax(220px,1fr)); gap:20px; }
        .card { background:#111118; border-radius:10px; overflow:hidden; border:1px solid #1e1e2e; position:relative; transition:border 0.2s; }
        .card:hover { border-color:#e94560; }
        .card img { width:100%; height:160px; object-fit:cover; }
        .card-sem-img { height:160px; background:#1e1e2e; display:flex; align-items:center; justify-content:center; font-size:3em; }
        .card-body { padding:14px; }
        .card-cat { font-size:0.72em; color:#e94560; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px; }
        .card h3 { font-size:0.95em; font-weight:600; margin-bottom:8px; color:#fff; }
        .preco-atual { color:#ff6b6b; font-size:1.1em; font-weight:700; margin-bottom:4px; }
        .estoque-ok { color:#4caf50; font-size:0.8em; }
        .estoque-no { color:#f44336; font-size:0.8em; }
        .badge-promo { position:absolute; top:10px; left:10px; background:#e94560; color:#fff; font-size:0.72em; padding:3px 8px; border-radius:4px; font-weight:700; }
        .card-actions { display:flex; gap:8px; margin-top:12px; }
        .btn-editar { flex:1; background:#1e1e2e; color:#fff; border:1px solid #2e2e3e; padding:8px; border-radius:6px; cursor:pointer; font-size:0.82em; text-decoration:none; text-align:center; transition:border 0.2s; }
        .btn-editar:hover { border-color:#e94560; color:#e94560; }
        .btn-deletar { flex:1; background:#e94560; color:#fff; border:none; padding:8px; border-radius:6px; cursor:pointer; font-size:0.82em; transition:background 0.2s; }
        .btn-deletar:hover { background:#c73652; }
        footer { background:#111118; border-top:1px solid #1e1e2e; padding:20px; text-align:center; color:#555; font-size:0.85em; margin-top:20px; }
        footer span { color:#e94560; }
    </style>
</head>
<body>
<nav>
    <div class="logo">GAME<span>STORE</span> <span style="color:#e94560;font-size:0.6em;vertical-align:middle">ADMIN</span></div>
    <div class="nav-right">
        <a href="loja.php" class="btn-nav btn-loja">Ver Loja</a>
        <a href="promocoes.php" class="btn-nav btn-promo">🔥 Promoções</a>
        <a href="adicionar.php" class="btn-nav btn-add">+ Novo Jogo</a>
    </div>
</nav>

<div class="secao">
    <p class="secao-titulo">Gerenciar Jogos</p>
    <div class="jogos">
    <?php
    $result = mysqli_query($conn, "SELECT * FROM jogos ORDER BY criado_em DESC");
    while ($j = mysqli_fetch_assoc($result)) {
        if ($j['imagem'] && strpos($j['imagem'], 'http') === 0) {
            $img = '<img src="'.$j['imagem'].'" alt="'.htmlspecialchars($j['nome']).'">';
        } elseif ($j['imagem']) {
            $img = '<img src="imagens/'.$j['imagem'].'" alt="'.htmlspecialchars($j['nome']).'">';
        } else {
            $img = '<div class="card-sem-img">🎮</div>';
        }
        $badge = $j['em_promocao'] ? '<span class="badge-promo">🔥 PROMO</span>' : '';
        echo '
        <div class="card">
            '.$badge.$img.'
            <div class="card-body">
                <div class="card-cat">'.$j['categoria'].'</div>
                <h3>'.htmlspecialchars($j['nome']).'</h3>
                <p class="preco-atual">R$ '.number_format($j['preco'],2,",",".").'</p>
                <p class="'.($j['estoque']>0?'estoque-ok':'estoque-no').'">Estoque: '.$j['estoque'].'</p>
                <div class="card-actions">
                    <a href="editar.php?id='.$j['id'].'" class="btn-editar">✏️ Editar</a>
                    <button class="btn-deletar" onclick="deletar('.$j['id'].',\''.htmlspecialchars($j['nome']).'\')">🗑 Deletar</button>
                </div>
            </div>
        </div>';
    }
    ?>
    </div>
</div>

<footer>© 2026 <span>GameStore</span> — Painel Administrativo</footer>

<script>
function deletar(id, nome) {
    if(confirm('Deletar "' + nome + '"? Esta ação não pode ser desfeita!')) {
        window.location.href = 'deletar.php?id=' + id;
    }
}
</script>
</body>
</html>
