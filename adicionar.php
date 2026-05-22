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
        .logo { color:#e94560; font-size:1.5em; font-weight:700; letter-spacing:2px; }
        .logo span { color:#fff; }
        .btn-nav { padding:7px 16px; border-radius:6px; text-decoration:none; font-size:0.85em; font-weight:600; background:#1e1e2e; color:#aaa; border:1px solid #2e2e3e; }
        .container { max-width:600px; margin:40px auto; padding:0 20px; }
        h2 { color:#e94560; margin-bottom:25px; font-size:1.4em; border-left:3px solid #e94560; padding-left:12px; }
        .form-box { background:#111118; border-radius:12px; padding:30px; border:1px solid #1e1e2e; }
        label { color:#aaa; font-size:0.9em; display:block; margin-bottom:6px; }
        input, textarea, select { width:100%; padding:11px 14px; margin-bottom:18px; background:#0a0a0f; border:1px solid #2e2e3e; border-radius:8px; color:#fff; font-size:0.95em; outline:none; transition:border 0.2s; }
        input:focus, textarea:focus, select:focus { border-color:#e94560; }
        textarea { resize:vertical; min-height:100px; }
        .upload-area { border:2px dashed #2e2e3e; border-radius:8px; padding:30px; text-align:center; margin-bottom:18px; cursor:pointer; transition:border 0.2s; }
        .upload-area:hover { border-color:#e94560; }
        .upload-area p { color:#aaa; font-size:0.9em; margin-top:8px; }
        .upload-area .icon { font-size:2.5em; }
        .preview { width:100%; height:180px; object-fit:cover; border-radius:8px; margin-bottom:18px; display:none; border:1px solid #2e2e3e; }
        .progress { background:#1e1e2e; border-radius:8px; height:8px; margin-bottom:18px; display:none; }
        .progress-bar { background:#e94560; height:8px; border-radius:8px; width:0%; transition:width 0.3s; }
        .status { color:#aaa; font-size:0.85em; margin-bottom:18px; display:none; text-align:center; }
        .btn-salvar { width:100%; padding:13px; background:#e94560; color:#fff; border:none; border-radius:8px; font-size:1em; font-weight:700; cursor:pointer; transition:background 0.2s; }
        .btn-salvar:hover { background:#c73652; }
        .btn-salvar:disabled { background:#555; cursor:not-allowed; }
        .btn-voltar { display:block; text-align:center; margin-top:15px; color:#aaa; text-decoration:none; font-size:0.9em; }
        .btn-voltar:hover { color:#e94560; }
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
            <input type="text" name="nome" required placeholder="Ex: God of War">

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

            <label>Imagem do Jogo</label>
            <div class="upload-area" onclick="document.getElementById('fileInput').click()">
                <div class="icon">🖼️</div>
                <p>Clique para selecionar uma imagem do seu computador</p>
                <p style="font-size:0.8em;color:#555">JPG, PNG, WEBP até 10MB</p>
            </div>
            <input type="file" id="fileInput" accept="image/*" style="display:none" onchange="uploadImagem(this)">
            <div class="progress"><div class="progress-bar" id="progressBar"></div></div>
            <p class="status" id="status"></p>
            <img id="preview" class="preview" alt="preview">
            <input type="hidden" name="imagem" id="imgUrl">

            <button type="submit" class="btn-salvar" id="btnSalvar">✅ Cadastrar Jogo</button>
        </form>
        <a href="index.php" class="btn-voltar">← Cancelar e voltar</a>
    </div>
</div>

<script>
async function uploadImagem(input) {
    const file = input.files[0];
    if (!file) return;

    document.getElementById('progressBar').style.width = '0%';
    document.getElementById('progress') && (document.querySelector('.progress').style.display = 'block');
    document.querySelector('.progress').style.display = 'block';
    document.getElementById('status').style.display = 'block';
    document.getElementById('status').textContent = 'Enviando imagem...';
    document.getElementById('btnSalvar').disabled = true;

    const formData = new FormData();
    formData.append('file', file);
    formData.append('upload_preset', 'loja_games');
    formData.append('cloud_name', 'dqm6eceoc');

    try {
        const xhr = new XMLHttpRequest();
        xhr.upload.onprogress = (e) => {
            const pct = Math.round(e.loaded / e.total * 100);
            document.getElementById('progressBar').style.width = pct + '%';
        };
        xhr.onload = () => {
            const data = JSON.parse(xhr.responseText);
            if (data.secure_url) {
                document.getElementById('imgUrl').value = data.secure_url;
                document.getElementById('preview').src = data.secure_url;
                document.getElementById('preview').style.display = 'block';
                document.getElementById('status').textContent = '✅ Imagem enviada com sucesso!';
                document.getElementById('status').style.color = '#4caf50';
                document.getElementById('btnSalvar').disabled = false;
            } else {
                document.getElementById('status').textContent = '❌ Erro ao enviar imagem!';
                document.getElementById('status').style.color = '#f44336';
                document.getElementById('btnSalvar').disabled = false;
            }
        };
        xhr.open('POST', 'https://api.cloudinary.com/v1_1/dqm6eceoc/image/upload');
        xhr.send(formData);
    } catch(e) {
        document.getElementById('status').textContent = '❌ Erro ao enviar imagem!';
        document.getElementById('btnSalvar').disabled = false;
    }
}
</script>
</body>
</html>
