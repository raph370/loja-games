<?php
include 'conexao.php';
session_start();
$id = $_GET['id'];
$jogo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM jogos WHERE id=$id"));
if (!$jogo) { header('Location: loja.php'); exit; }
$preco_final = ($jogo['em_promocao'] && $jogo['preco_promo']) ? $jogo['preco_promo'] : $jogo['preco'];
$desconto = ($jogo['em_promocao'] && $jogo['preco_promo']) ? round((1 - $jogo['preco_promo']/$jogo['preco'])*100) : 0;
$img = '';
if ($jogo['imagem'] && strpos($jogo['imagem'], 'http') === 0) $img = $jogo['imagem'];
elseif ($jogo['imagem']) $img = 'imagens/'.$jogo['imagem'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=htmlspecialchars($jogo['nome'])?> — GameStore</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',Arial,sans-serif; background:#0a0a0f; color:#fff; }
        nav { background:#111118; padding:0 30px; display:flex; align-items:center; justify-content:space-between; height:60px; border-bottom:1px solid #1e1e2e; position:sticky; top:0; z-index:100; }
        .logo { color:#4fc3f7; font-size:1.5em; font-weight:700; letter-spacing:2px; text-decoration:none; }
        .logo span { color:#fff; }
        .nav-right { display:flex; align-items:center; gap:15px; }
        .btn-nav { padding:7px 16px; border-radius:6px; text-decoration:none; font-size:0.85em; font-weight:600; }
        .btn-login { background:transparent; color:#4fc3f7; border:1px solid #4fc3f7; }
        .btn-cadastro { background:#4fc3f7; color:#0a0a0f; }
        .btn-carrinho { background:#1e1e2e; color:#fff; border:1px solid #2e2e3e; }
        .btn-sair { background:transparent; color:#aaa; border:1px solid #333; }

        .hero-wrap { position:relative; height:380px; overflow:hidden; }
        .hero-bg { width:100%; height:100%; object-fit:cover; filter:brightness(0.25); }
        .hero-overlay { position:absolute; inset:0; display:flex; align-items:flex-end; padding:35px; background:linear-gradient(to top, #0a0a0f 20%, transparent); }
        .hero-content { display:flex; gap:25px; align-items:flex-end; max-width:1100px; width:100%; }
        .hero-cover { width:220px; height:105px; object-fit:cover; border-radius:8px; box-shadow:0 8px 30px rgba(0,0,0,0.8); flex-shrink:0; }
        .hero-info h1 { font-size:2em; font-weight:700; margin-bottom:8px; }
        .hero-info .cat { background:#4fc3f7; color:#0a0a0f; padding:3px 12px; border-radius:10px; font-size:0.8em; font-weight:700; display:inline-block; }

        .container { max-width:1100px; margin:0 auto; padding:35px 25px; display:grid; grid-template-columns:1fr 320px; gap:25px; }
        .descricao-box { background:#111118; border-radius:12px; padding:28px; border:1px solid #1e1e2e; }
        .descricao-box h2 { color:#4fc3f7; margin-bottom:15px; font-size:1.1em; }
        .descricao-box p { color:#ccc; line-height:1.8; }

        .info-box { display:flex; flex-direction:column; gap:15px; }
        .comprar-box { background:#111118; border-radius:12px; padding:22px; border:1px solid #1e1e2e; }
        .preco-antigo { color:#666; text-decoration:line-through; font-size:0.9em; margin-bottom:4px; }
        .badge-desc { background:#3d0000; color:#ff6b6b; padding:3px 10px; border-radius:6px; font-size:0.8em; font-weight:700; display:inline-block; margin-bottom:10px; }
        .preco-final { color:#4fc3f7; font-size:2em; font-weight:700; margin-bottom:18px; }

        .btn-comprar-agora { width:100%; padding:13px; background:#4fc3f7; color:#0a0a0f; border:none; border-radius:8px; font-size:1em; font-weight:700; cursor:pointer; margin-bottom:10px; transition:background 0.2s; }
        .btn-comprar-agora:hover { background:#81d4fa; }
        .btn-add-carrinho { width:100%; padding:13px; background:transparent; color:#4fc3f7; border:2px solid #4fc3f7; border-radius:8px; font-size:1em; font-weight:700; cursor:pointer; margin-bottom:10px; transition:all 0.2s; }
        .btn-add-carrinho:hover { background:#4fc3f7; color:#0a0a0f; }
        .btn-disabled { width:100%; padding:13px; background:#333; color:#666; border:none; border-radius:8px; font-size:1em; cursor:not-allowed; }
        .btn-voltar { display:block; text-align:center; color:#aaa; text-decoration:none; font-size:0.85em; margin-top:5px; }
        .btn-voltar:hover { color:#4fc3f7; }

        .details-box { background:#111118; border-radius:12px; padding:22px; border:1px solid #1e1e2e; }
        .details-box h3 { color:#4fc3f7; margin-bottom:15px; font-size:1em; }
        .detail-row { display:flex; justify-content:space-between; padding:8px 0; border-bottom:1px solid #1a1a2e; font-size:0.88em; }
        .detail-row:last-child { border-bottom:none; }
        .detail-label { color:#aaa; }
        .detail-value { color:#fff; font-weight:600; }
        .estoque-ok { color:#4caf50; }
        .estoque-no { color:#f44336; }

        footer { background:#111118; border-top:1px solid #1e1e2e; padding:20px; text-align:center; color:#555; font-size:0.85em; margin-top:20px; }
        footer span { color:#4fc3f7; }

        @media(max-width:768px) {
            .container { grid-template-columns:1fr; }
            .hero-cover { width:150px; height:75px; }
            .hero-info h1 { font-size:1.4em; }
        }
    </style>
</head>
<body>
<nav>
    <a href="loja.php" class="logo">GAME<span>STORE</span></a>
    <div class="nav-right">
        <?php if(isset($_SESSION['usuario_nome'])): ?>
            <span style="color:#aaa;font-size:0.85em">👤 <?=htmlspecialchars($_SESSION['usuario_nome'])?></span>
            <a href="carrinho.php" class="btn-nav btn-carrinho">🛒 Carrinho</a>
            <a href="logout.php" class="btn-nav btn-sair">Sair</a>
        <?php else: ?>
            <a href="login.php" class="btn-nav btn-login">Entrar</a>
            <a href="cadastro.php" class="btn-nav btn-cadastro">Cadastrar</a>
        <?php endif; ?>
    </div>
</nav>

<?php if($img): ?>
<div class="hero-wrap">
    <img src="<?=$img?>" class="hero-bg" alt="bg">
    <div class="hero-overlay">
        <div class="hero-content">
            <img src="<?=$img?>" class="hero-cover" alt="<?=htmlspecialchars($jogo['nome'])?>">
            <div class="hero-info">
                <span class="cat"><?=htmlspecialchars($jogo['categoria'])?></span>
                <h1><?=htmlspecialchars($jogo['nome'])?></h1>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="container">
    <div class="descricao-box">
        <h2>Sobre o jogo</h2>
        <p><?=nl2br(htmlspecialchars($jogo['descricao']))?></p>
    </div>

    <div class="info-box">
        <div class="comprar-box">
            <?php if($jogo['em_promocao'] && $jogo['preco_promo']): ?>
                <div class="preco-antigo">R$ <?=number_format($jogo['preco'],2,",",".")?></div>
                <div class="badge-desc">-<?=$desconto?>% DE DESCONTO</div>
            <?php endif; ?>
            <div class="preco-final">R$ <?=number_format($preco_final,2,",",".")?></div>

            <?php if($jogo['estoque'] > 0): ?>
                <button class="btn-comprar-agora" onclick="comprarAgora(<?=$jogo['id']?>)">⚡ Comprar Agora</button>
                <button class="btn-add-carrinho" onclick="addCarrinho(<?=$jogo['id']?>)">🛒 Adicionar ao Carrinho</button>
            <?php else: ?>
                <button class="btn-disabled" disabled>❌ Produto Esgotado</button>
            <?php endif; ?>
            <a href="loja.php" class="btn-voltar">← Voltar à loja</a>
        </div>

        <div class="details-box">
            <h3>Detalhes</h3>
            <div class="detail-row">
                <span class="detail-label">Categoria</span>
                <span class="detail-value"><?=htmlspecialchars($jogo['categoria'])?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Estoque</span>
                <span class="detail-value <?=$jogo['estoque']>0?'estoque-ok':'estoque-no'?>">
                    <?=$jogo['estoque']>0?$jogo['estoque'].' unidades':'Esgotado'?>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Promoção</span>
                <span class="detail-value"><?=$jogo['em_promocao']?'🔥 Sim':'Não'?></span>
            </div>
        </div>
    </div>
</div>

<footer>© 2026 <span>GameStore</span> — Todos os direitos reservados</footer>

<script>
function addCarrinho(id) {
    <?php if(isset($_SESSION['usuario_id'])): ?>
    window.location.href = 'carrinho.php?adicionar=' + id;
    <?php else: ?>
    if(confirm('Faça login para adicionar ao carrinho!\nIr para o login?')) {
        window.location.href = 'login.php';
    }
    <?php endif; ?>
}
function comprarAgora(id) {
    <?php if(isset($_SESSION['usuario_id'])): ?>
    window.location.href = 'carrinho.php?adicionar=' + id + '&comprar=1';
    <?php else: ?>
    if(confirm('Faça login para comprar!\nIr para o login?')) {
        window.location.href = 'login.php';
    }
    <?php endif; ?>
}
</script>
</body>
</html>
