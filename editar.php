<?php
include 'conexao.php';
$id = $_GET['id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
    $preco = $_POST['preco'];
    $categoria = mysqli_real_escape_string($conn, $_POST['categoria']);
    $estoque = $_POST['estoque'];
    $imagem = mysqli_real_escape_string($conn, $_POST['imagem']);
    mysqli_query($conn, "UPDATE jogos SET nome='$nome',descricao='$descricao',preco='$preco',categoria='$categoria',estoque='$estoque',imagem='$imagem' WHERE id=$id");
    header('Location: index.php');
}
$jogo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM jogos WHERE id=$id"));
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Jogo — GameStore</title>
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
        .preview { width:100%; height:160px; object-fit:cover; border-radius:8px; margin-bottom:18px; border:1px solid #2e2e3e; }
        .btn-salvar { width:100%; padding:13px; background:#4fc3f7; color:#0a0a0f; border:none; border-radius:8px; font-size:1em; font-weight:700; cursor:pointer; transition:background 0.2s; }
        .btn-salvar:hover { background:#81d4fa; }
        .btn-voltar { display:block; text-align:center; margin-top:15px; color:#aaa; text-decoration:none; font-size:0.9em; }
        .btn-voltar:hover { color:#4fc3f7; }
    </style>
</head>
<body>
<nav>
    <div class="logo">GAME<span>STORE</span> <span style="color:#e94560;font-size:0.6em;vertical-align:middle">ADMIN</span></div>
    <a href="index.php" class="btn-nav">← Voltar ao painel</a>
</nav>

<div class="container">
    <h2>✏️ Editar Jogo</h2>
    <div class="form-box">
        <form method="POST">
            <label>Nome do Jogo</label>
            <input type="text" name="nome" value="<?=htmlspecialchars($jogo['nome'])?>" required>

            <label>Descrição</label>
            <textarea name="descricao"><?=htmlspecialchars($jogo['descricao'])?></textarea>

            <label>Preço (R$)</label>
            <input type="number" name="preco" step="0.01" value="<?=$jogo['preco']?>" required>

            <label>Categoria</label>
            <select name="categoria">
                <?php foreach(['Ação','RPG','Esporte','Aventura','Estratégia'] as $cat): ?>
                <option <?=$jogo['categoria']==$cat?'selected':''?>><?=$cat?></option>
                <?php endforeach; ?>
            </select>

            <label>Estoque</label>
            <input type="number" name="estoque" value="<?=$jogo['estoque']?>" min="0">

            <label>URL da Imagem</label>
            <input type="text" name="imagem" id="imgUrl" value="<?=htmlspecialchars($jogo['imagem'])?>" placeholder="https://..." oninput="previewImg(this.value)">
            <?php if($jogo['imagem']): ?>
            <img id="preview" class="preview" src="<?=htmlspecialchars($jogo['imagem'])?>" alt="preview">
            <?php else: ?>
            <img id="preview" class="preview" style="display:none" alt="preview">
            <?php endif; ?>

            <button type="submit" class="btn-salvar">💾 Salvar Alterações</button>
        </form>
        <a href="index.php" class="btn-voltar">← Cancelar e voltar</a>
    </div>
</div>

<script>
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
</script>
</body>
</html>
