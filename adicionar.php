<?php
include 'conexao.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
    $preco = $_POST['preco'];
    $categoria = mysqli_real_escape_string($conn, $_POST['categoria']);
    $estoque = $_POST['estoque'];
    $imagem = mysqli_real_escape_string($conn, $_POST['imagem']);
    mysqli_query($conn, "INSERT INTO jogos (nome,descricao,preco,categoria,estoque,imagem) VALUES ('$nome','$descricao','$preco','$categoria','$estoque','$imagem')");
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Jogo — GameStore</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',Arial,sans-serif; background:#0a0a0f; color:#fff; }
        nav { background:#111118; padding:0 30px; display:flex; align-items:center; justify-content:space-between; height:60px; border-bottom:1px solid #1e1e2e; position:sticky; top:0; z-index:100; }
        .logo { color:#4fc3f7; font-size:1.5em; font-weight:700; letter-spacing:2px; }
        .logo span { color:#fff; }
        .btn-nav { padding:7px 16px; border-radius:6px; text-decoration:none; font-size:0.85em; font-weight:600; background:#1e1e2e; color:#aaa; border:1px solid #2e2e3e; }
        .container { max-width:600px; margin:40px auto; padding:0 20px; }
        h2 { color:#4fc3f7; margin-bottom:25px; font-size:1.4em; border-left:3px solid #4fc3f7; padding-left:12px; }
        .form-box { background:#111118; border-radius:12px; padding:30px; border:1px solid #1e1e2e; }
        label { color:#aaa; font-size:0.9em; display:block; margin-bottom:6px; }
        input, textarea, select { width:100%; padding:11px 14px; margin-bottom:18px; background:#0a0a0f; border:1px solid #2e2e3e; border-radius:8px; color:#fff; font-size:0.95em; outline:none; transition:border 0.2s; }
        input:focus, textarea:focus, select:focus { border-color:#4fc3f7; }
        textarea { resize:vertical; min-height:100px; }
        .preview { width:100%; height:160px; object-fit:cover; border-radius:8px; margin-bottom:18px; display:none; border:1px solid #2e2e3e; }
        .btn-buscar { width:100%; padding:10px; background:#0f3460; color:#fff; border:none; border-radius:8px; font-size:0.9em; font-weight:600; cursor:pointer; margin-bottom:18px; transition:background 0.2s; }
        .btn-buscar:hover { background:#1a4a80; }
        .resultados { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:18px; display:none; }
        .resultado-item { cursor:pointer; border-radius:8px; overflow:hidden; border:2px solid transparent; transition:border 0.2s; }
        .resultado-item:hover { border-color:#4fc3f7; }
        .resultado-item.selecionado { border-color:#00c853; }
        .resultado-item img { width:100%; height:70px; object-fit:cover; }
        .resultado-item p { color:#aaa; font-size:0.7em; padding:4px; background:#111118; text-align:center; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; }
        .btn-salvar { width:100%; padding:13px; background:#4fc3f7; color:#0a0a0f; border:none; border-radius:8px; font-size:1em; font-weight:700; cursor:pointer; transition:background 0.2s; }
        .btn-salvar:hover { background:#81d4fa; }
        .btn-voltar { display:block; text-align:center; margin-top:15px; color:#aaa; text-decoration:none; font-size:0.9em; }
        .loading { text-align:center; color:#4fc3f7; font-size:0.9em; display:none; margin-bottom:10px; }
    </style>
</head>
<body>
<nav>
    <div class="logo">GAME<span>STORE</span> <span style="color:#e94560;font-size:0.6em;vertical-align:middle">ADMIN</span></div>
    <a href="index.php" class="btn-nav">← Voltar ao painel</a>
</nav>

<div class="container">
    <h2>+ Adicionar Jogo</h2>
    <div class="form-box">
        <form method="POST" id="formJogo">
            <label>Nome do Jogo</label>
            <input type="text" name="nome" id="nomeJogo" required placeholder="Ex: God of War" oninput="limparBusca()">

            <button type="button" class="btn-buscar" onclick="buscarImagem()">🔍 Buscar Imagem na Steam</button>

            <p class="loading" id="loading">Buscando...</p>

            <div class="resultados" id="resultados"></div>

            <label>URL da Imagem</label>
            <input type="text" name="imagem" id="imgUrl" placeholder="Selecione acima ou cole uma URL" oninput="previewImg(this.value)">
            <img id="preview" class="preview" alt="preview">

            <label>Descrição</label>
            <textarea name="descricao" placeholder="Descreva o jogo..."></textarea>

            <label>Preço (R$)</label>
            <input type="number" name="preco" step="0.01" required placeholder="0.00">

            <label>Categoria</label>
            <select name="categoria">
                <option>Ação</option>
                <option>RPG</option>
                <option>Esporte</option>
                <option>Aventura</option>
                <option>Estratégia</option>
            </select>

            <label>Estoque</label>
            <input type="number" name="estoque" value="0" min="0">

            <button type="submit" class="btn-salvar">✅ Cadastrar Jogo</button>
        </form>
        <a href="index.php" class="btn-voltar">← Cancelar e voltar</a>
    </div>
</div>

<script>
const jogosIds = {
    'god of war': '1593500',
    'elden ring': '1245620',
    'gta v': '271590',
    'gta 5': '271590',
    'minecraft': '1672970',
    'cyberpunk 2077': '1091500',
    'hollow knight': '367520',
    'the witcher 3': '292030',
    'red dead redemption 2': '1174180',
    'fifa 25': '2195250',
    'fifa 24': '2195250',
    'call of duty': '1938090',
    'spider-man': '1817070',
    'horizon': '1151640',
    'death stranding': '1190460',
    'sekiro': '814380',
    'dark souls 3': '374320',
    'doom eternal': '782330',
    'hades': '1145360',
    'stardew valley': '413150',
    'among us': '945360',
    'fortnite': '1262420',
    'apex legends': '1172470',
    'valorant': '1234567',
    'league of legends': '0',
    'batman': '208650',
    'assassins creed': '2252330',
    'far cry 6': '1682940',
    'watch dogs': '243470',
    'resident evil': '1196590',
    'devil may cry 5': '601150',
    'mortal kombat': '976310',
    'street fighter 6': '1841780',
    'tekken 8': '1778820',
    'grand theft auto': '271590',
};

function buscarImagem() {
    const nome = document.getElementById('nomeJogo').value.toLowerCase().trim();
    if (!nome) { alert('Digite o nome do jogo primeiro!'); return; }

    document.getElementById('loading').style.display = 'block';
    document.getElementById('resultados').style.display = 'none';

    let appId = null;
    for (const [key, id] of Object.entries(jogosIds)) {
        if (nome.includes(key) || key.includes(nome)) {
            appId = id;
            break;
        }
    }

    setTimeout(() => {
        document.getElementById('loading').style.display = 'none';
        const div = document.getElementById('resultados');

        if (appId && appId !== '0') {
            const urls = [
                `https://cdn.cloudflare.steamstatic.com/steam/apps/${appId}/header.jpg`,
                `https://cdn.cloudflare.steamstatic.com/steam/apps/${appId}/capsule_616x353.jpg`,
                `https://cdn.cloudflare.steamstatic.com/steam/apps/${appId}/capsule_231x87.jpg`,
            ];
            div.innerHTML = urls.map((url, i) => `
                <div class="resultado-item" onclick="selecionarImg('${url}', this)">
                    <img src="${url}" onerror="this.parentElement.style.display='none'">
                    <p>Opção ${i+1}</p>
                </div>
            `).join('');
        } else {
            div.innerHTML = '<p style="color:#aaa;font-size:0.85em;grid-column:1/-1">Jogo não encontrado na base. Cole a URL manualmente abaixo.</p>';
        }
        div.style.display = 'grid';
    }, 800);
}

function selecionarImg(url, el) {
    document.querySelectorAll('.resultado-item').forEach(i => i.classList.remove('selecionado'));
    el.classList.add('selecionado');
    document.getElementById('imgUrl').value = url;
    previewImg(url);
}

function previewImg(url) {
    const preview = document.getElementById('preview');
    if (url && url.startsWith('http')) {
        preview.src = url;
        preview.style.display = 'block';
        preview.onerror = () => preview.style.display = 'none';
    } else {
        preview.style.display = 'none';
    }
}

function limparBusca() {
    document.getElementById('resultados').style.display = 'none';
}
</script>
</body>
</html>
