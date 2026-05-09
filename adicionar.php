<?php
include 'conexao.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
    $preco = $_POST['preco'];
    $categoria = mysqli_real_escape_string($conn, $_POST['categoria']);
    $estoque = $_POST['estoque'];
    $imagem = '';
    if ($_FILES['imagem']['name'] != '') {
        $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
        $nomearq = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['imagem']['tmp_name'], 'imagens/' . $nomearq);
        $imagem = $nomearq;
    }
    mysqli_query($conn, "INSERT INTO jogos (nome,descricao,preco,categoria,estoque,imagem) VALUES ('$nome','$descricao','$preco','$categoria','$estoque','$imagem')");
    header('Location: index.php');
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Adicionar Jogo</title>
    <style>
        body { font-family:Arial; background:#0f0f1a; color:#fff; }
        header { background:#1a1a2e; padding:20px; text-align:center; }
        header h1 { color:#e94560; }
        form { max-width:500px; margin:40px auto; background:#16213e; padding:30px; border-radius:10px; }
        input, textarea, select { width:100%; padding:10px; margin:8px 0 16px;
            background:#0f3460; border:none; border-radius:5px; color:#fff; }
        label { color:#aaa; }
        button { background:#e94560; color:#fff; padding:12px 30px;
                 border:none; border-radius:5px; cursor:pointer; font-size:1em; }
        a { color:#e94560; display:block; text-align:center; margin-top:15px; }
    </style>
</head>
<body>
<header><h1>🎮 Adicionar Jogo</h1></header>
<form method="POST" enctype="multipart/form-data">
    <label>Nome do Jogo</label>
    <input type="text" name="nome" required>
    <label>Descrição</label>
    <textarea name="descricao" rows="3"></textarea>
    <label>Preço (R$)</label>
    <input type="number" name="preco" step="0.01" required>
    <label>Categoria</label>
    <select name="categoria">
        <option>Ação</option>
        <option>RPG</option>
        <option>Esporte</option>
        <option>Aventura</option>
        <option>Estratégia</option>
    </select>
    <label>Estoque</label>
    <input type="number" name="estoque" value="0">
    <label>Imagem do Jogo</label>
    <input type="file" name="imagem" accept="image/*">
    <button type="submit">Cadastrar Jogo</button>
    <a href="index.php">← Voltar</a>
</form>
</body>
</html>
