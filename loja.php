<?php
include 'conexao.php';
session_start();
$tem_promo = mysqli_query($conn, "SELECT id FROM jogos WHERE em_promocao=1 LIMIT 1");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GameStore</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',Arial,sans-serif; background:#0a0a0f; color:#fff; }
        nav { background:#111118; padding:0 30px; display:flex; align-items:center; justify-content:space-between; height:60px; border-bottom:1px solid #1e1e2e; position:sticky; top:0; z-index:100; }
        .logo { color:#e94560; font-size:1.5em; font-weight:700; letter-spacing:2px; }
        .logo span { color:#fff; }
        .nav-right { display:flex; align-items:center; gap:15px; }
        .nav-right span { color:#aaa; font-size:0.85em; }
        .btn-nav { padding:7px 16px; border-radius:6px; text-decoration:none; font-size:0.85em; font-weight:600; }
        .btn-login { background:transparent; color:#e94560; border:1px solid #e94560; }
        .btn-cadastro { background:#e94560; color:#fff; }
        .btn-carrinho { background:#1e1e2e; color:#fff; border:1px solid #2e2e3e; }
        .btn-sair { background:transparent; color:#aaa; border:1px solid #333; }
        .hero { background:linear-gradient(135deg,#1a0a0a 0%,#2d0a0a 50%,#1a0a0a 100%); padding:50px 30px; text-align:center; border-bottom:2px solid #e94560; }
        .hero h1 { font-size:2.5em; font-weight:700; margin-bottom:10px; }
        .hero h1 span { color:#e94560; }
        .hero p { color:#aaa; font-size:1.1em; margin-bottom:25px; }
        .busca-hero { display:flex; justify-content:center; }
        .busca-hero input { padding:12px 20px; width:100%; max-width:400px; border-radius:8px; border:1px solid #e94560; background:#1e1e2e; color:#fff; font-size:1em; outline:none; }
        .busca-hero input:focus { border-color:#ff6b6b; }
        .promo-banner { background:linear-gradient(90deg,#1a0a0a,#3d0000,#1a0a0a); padding:10px 30px; text-align:center; color:#ff6b6b; font-weight:600; font-size:0.95em; border-bottom:1px solid #3d0000; }
        .filtros { background:#111118; padding:12px 30px; display:flex; gap:8px; flex-wrap:wrap; border-bottom:1px solid #1e1e2e; }
        .filtros button { background:#1e1e2e; color:#aaa; border:1px solid #2e2e3e; padding:7px 18px; border-radius:20px; cursor:pointer; font-size:0.85em; transition:all 0.2s; }
        .filtros button:hover { border-color:#e94560; color:#e94560; }
        .filtros button.ativo { background:#e94560; color:#fff; border-color:#e94560; font-weight:600; }
        .secao { padding:30px; }
        .secao-titulo { font-size:1.1em; color:#aaa; margin-bottom:20px; border-left:3px solid #e94560; padding-left:12px; }
        .jogos { display:grid; grid-template-columns:repeat(auto-fill,minmax(200px,1fr)); gap:20px; }
        .card { background:#111118; border-radius:10px; overflow:hidden; border:1px solid #1e1e2e; transition:all 0.25s; cursor:pointer; position:relative; }
        .card:hover { transform:translateY(-4px); border-color:#e94560; box-shadow:0 8px 25px rgba(233,69,96,0.2); }
        .card img { width:100%; height:160px; object-fit:cover; }
        .card-sem-img { height:160px; background:#1e1e2e; display:flex; align-items:center; justify-content:center; font-size:3em; }
        .card-body { padding:14px; }
        .card-cat { font-size:0.72em; color:#e94560; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px; }
        .card h3 { font-size:0.95em; font-weight:600; margin-bottom:8px; color:#fff; }
        .card-preco { display:flex; align-items:center; gap:8px; margin-bottom:10px; flex-wrap:wrap; }
        .preco-atual { color:#ff6b6b; font-size:1.1em; font-weight:700; }
        .preco-antigo { color:#666; text-decoration:line-through; font-size:0.85em; }
        .badge-off { background:#3d0000; color:#ff6b6b; font-size:0.72em; padding:2px 7px; border-radius:4px; font-weight:700; }
        .card-bottom { display:flex; align-items:center; justify-content:space-between; }
        .estoque-ok { color:#4caf50; font-size:0.75em; }
        .estoque-no { color:#f44336; font-size:0.75em; }
        .btn-comprar { background:#e94560; color:#fff; border:none; padding:7px 14px; border-radius:6px; font-size:0.82em; font-weight:700; cursor:pointer; transition:background 0.2s; }
        .btn-comprar:hover { background:#c73652; }
        .btn-comprar:disabled { background:#333; color:#666; cursor:not-allowed; }
        .badge-promo-card { position:absolute; top:10px; left:10px; background:#e94560; color:#fff; font-size:0.72em; padding:3px 8px; border-radius:4px; font-weight:700; }
        footer { background:#111118; border-top:1px solid #1e1e2e; padding:25px 30px; text-align:center; color:#555; font-size:0.85em; margin-top:20px; }
        footer span { color:#e94560; }
        @media(max-width:768px) { .hero h1 { font-size:1.8em; } }
    </style>
</head>
<body>
<nav>
    <div class="logo">GAME<span>STORE</span></div>
    <div class="nav-right">
        <?php if(isset($_SESSION['usuario_nome'])): ?>
            <span>👤 <?=htmlspecialchars($_SESSION['usuario_nome'])?></span>
            <a href="carrinho.php" class="btn-nav btn-carrinho">🛒</a>
            <a href="logout.php" class="btn-nav btn-sair">Sair</a>
        <?php else: ?>
            <a href="login.php" class="btn-nav btn-login">Entrar</a>
            <a href="cadastro.php" class="btn-nav btn-cadastro">Cadastrar</a>
        <?php endif; ?>
    </div>
</nav>

<?php if(mysqli_num_rows($tem_promo) > 0): ?>
<div class="promo-banner">🔥 PROMOÇÕES ATIVAS — Aproveite os melhores preços antes que acabem!</div>
<?php endif; ?>

<div class="hero">
    <h1>Os melhores jogos <span>estão aqui</span></h1>
    <p>Explore, compre e jogue. Sua próxima aventura começa agora.</p>
    <div class="busca-hero">
        <input type="text" id="busca" placeholder="🔍 Buscar jogo..." onkeyup="filtrar()">
    </div>
</div>

<div class="filtros">
    <button class="ativo" onclick="filtrarCat('todos',this)">🎮 Todos</button>
    <button onclick="filtrarCat('acao',this)">💥 Ação</button>
    <button onclick="filtrarCat('rpg',this)">⚔️ RPG</button>
    <button onclick="filtrarCat('esporte',this)">⚽ Esporte</button>
    <button onclick="filtrarCat('aventura',this)">🗺️ Aventura</button>
    <button onclick="filtrarCat('estrategia',this)">🧠 Estratégia</button>
    <button onclick="filtrarCat('tiro',this)">🎯 Tiro</button>
    <button onclick="filtrarCat('terror',this)">👻 Terror</button>
    <button onclick="filtrarCat('indie',this)">🎨 Indie</button>
    <button onclick="filtrarCat('mundo aberto',this)">🌍 Mundo Aberto</button>
    <button onclick="filtrarCat('corrida',this)">🏎️ Corrida</button>
    <button onclick="filtrarCat('luta',this)">🥊 Luta</button>
    <button onclick="filtrarCat('plataforma',this)">🕹️ Plataforma</button>
    <button onclick="filtrarCat('musical',this)">🎵 Musical</button>
    <button onclick="filtrarCat('promo',this)">🔥 Promoções</button>
</div>

<div class="secao">
    <p class="secao-titulo">Todos os jogos</p>
    <div class="jogos" id="lista">
    <?php
    $result = mysqli_query($conn, "SELECT * FROM jogos ORDER BY em_promocao DESC, criado_em DESC");
    while ($j = mysqli_fetch_assoc($result)) {
        $esgotado = $j['estoque'] <= 0;
        $tem_promo_j = $j['em_promocao'] && $j['preco_promo'];
        $preco_final = $tem_promo_j ? $j['preco_promo'] : $j['preco'];
        $desconto = $tem_promo_j ? round((1 - $j['preco_promo']/$j['preco'])*100) : 0;
        $cat = strtolower(iconv('UTF-8','ASCII//TRANSLIT',$j['categoria']));
        if ($j['imagem'] && strpos($j['imagem'], 'http') === 0) {
            $img = '<img src="'.$j['imagem'].'" alt="'.htmlspecialchars($j['nome']).'">';
        } elseif ($j['imagem']) {
            $img = '<img src="imagens/'.$j['imagem'].'" alt="'.htmlspecialchars($j['nome']).'">';
        } else {
            $img = '<div class="card-sem-img">🎮</div>';
        }
        $badge = $tem_promo_j ? '<span class="badge-promo-card">-'.$desconto.'%</span>' : '';
        $preco_html = $tem_promo_j
            ? '<span class="preco-antigo">R$ '.number_format($j['preco'],2,",",".").'</span><span class="preco-atual">R$ '.number_format($j['preco_promo'],2,",",".").'</span><span class="badge-off">-'.$desconto.'%</span>'
            : '<span class="preco-atual">R$ '.number_format($j['preco'],2,",",".").'</span>';
        echo '
        <div class="card" onclick="window.location.href=\'jogo.php?id='.$j['id'].'\'" data-nome="'.strtolower($j['nome']).'" data-cat="'.$cat.'" data-promo="'.($j['em_promocao']?'promo':'').'">
            '.$badge.$img.'
            <div class="card-body">
                <div class="card-cat">'.$j['categoria'].'</div>
                <h3>'.htmlspecialchars($j['nome']).'</h3>
                <div class="card-preco">'.$preco_html.'</div>
                <div class="card-bottom">
                    <span class="'.($esgotado?'estoque-no':'estoque-ok').'">'.($esgotado?'❌ Esgotado':'✅ '.$j['estoque'].' und.').'</span>
                    <button class="btn-comprar" '.($esgotado?'disabled':'onclick="event.stopPropagation();comprar('.$j['id'].')"').'>
                        '.($esgotado?'Esgotado':'+ Carrinho').'
                    </button>
                </div>
            </div>
        </div>';
    }
    ?>
    </div>
</div>
<footer>© 2026 <span>GameStore</span> — Todos os direitos reservados</footer>
<script>
function comprar(id) {
    <?php if(isset($_SESSION['usuario_id'])): ?>
    window.location.href = 'carrinho.php?adicionar=' + id;
    <?php else: ?>
    if(confirm('Faça login para adicionar ao carrinho!\nIr para o login?')) {
        window.location.href = 'login.php';
    }
    <?php endif; ?>
}
function filtrar() {
    const b = document.getElementById('busca').value.toLowerCase();
    document.querySelectorAll('.card').forEach(c => {
        c.style.display = c.dataset.nome.includes(b) ? '' : 'none';
    });
}
function filtrarCat(cat, btn) {
    document.querySelectorAll('.filtros button').forEach(b => b.classList.remove('ativo'));
    btn.classList.add('ativo');
    document.querySelectorAll('.card').forEach(c => {
        if(cat==='todos') c.style.display='';
        else if(cat==='promo') c.style.display=c.dataset.promo==='promo'?'':'none';
        else c.style.display=c.dataset.cat===cat?'':'none';
    });
}
</script>
</body>
</html>
