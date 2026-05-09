<?php
include 'conexao.php';
$id = $_GET['id'];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $descricao = mysqli_real_escape_string($conn, $_POST['descricao']);
    $preco = $_POST['preco'];
    $categoria = mysqli_real_escape_string($conn, $_POST['categoria']);
    $estoque = $_POST['estoque'];
    mysqli_query($conn, "UPDATE jogos SET nome='$nome',descricao='$descricao',preco='$preco',categoria='$categoria',estoque='$estoque' WHERE id=$id");
    header('Location: index.php');
}
$jogo = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM jogos WHERE id=$id"));
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Jogo</title>
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
<header><h1>🎮 Editar Jogo</h1></header>
<form method="POST">
    <label>Nome do Jogo</label>
    <input type="text" name="nome" value="<?=$jogo['nome']?>" required>
    <label>Descrição</label>
    <textarea name="descricao" rows="3"><?=$jogo['descricao']?></textarea>
    <label>Preço (R$)</label>
    <input type="number" name="preco" step="0.01" value="<?=$jogo['preco']?>" required>
    <label>Categoria</label>
    <select name="categoria">
        <?php foreach(['Ação','RPG','Esporte','Aventura','Estratégia'] as $cat): ?>
        <option <?=$jogo['categoria']==$cat?'selected':''?>><?=$cat?></option>
        <?php endforeach; ?>
    </select>
    <label>Estoque</label>
    <input type="number" name="estoque" value="<?=$jogo['estoque']?>">
    <button type="submit">Salvar Alterações</button>
    <a href="index.php">← Voltar</a>
</form>
</body>
</html>
