<?php
include 'conexao.php';
session_start();
$erro = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = mysqli_real_escape_string($conn, $_POST['nome']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);
    $pergunta = mysqli_real_escape_string($conn, $_POST['pergunta']);
    $resposta = strtolower(mysqli_real_escape_string($conn, $_POST['resposta']));
    $check = mysqli_query($conn, "SELECT id FROM usuarios WHERE email='$email'");
    if (mysqli_num_rows($check) > 0) {
        $erro = 'Email já cadastrado!';
    } else {
        mysqli_query($conn, "INSERT INTO usuarios (nome,email,senha,pergunta,resposta) VALUES ('$nome','$email','$senha','$pergunta','$resposta')");
        header('Location: login.php');
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro</title>
    <style>
        body { font-family:Arial; background:#0f0f1a; color:#fff; display:flex; justify-content:center; align-items:center; min-height:100vh; margin:0; }
        .box { background:#16213e; padding:40px; border-radius:12px; width:380px; }
        h2 { color:#e94560; text-align:center; margin-bottom:25px; }
        label { color:#aaa; font-size:0.9em; }
        input, select { width:100%; padding:10px; margin:6px 0 16px; background:#0f3460; border:none; border-radius:8px; color:#fff; font-size:1em; }
        button { width:100%; padding:12px; background:#e94560; color:#fff; border:none; border-radius:8px; font-size:1em; cursor:pointer; }
        .erro { color:#ff6b6b; text-align:center; margin-bottom:15px; }
        a { color:#e94560; display:block; text-align:center; margin-top:15px; font-size:0.9em; }
    </style>
</head>
<body>
<div class="box">
    <h2>🎮 Criar Conta</h2>
    <?php if($erro) echo '<p class="erro">'.$erro.'</p>'; ?>
    <form method="POST">
        <label>Nome</label>
        <input type="text" name="nome" required>
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Senha</label>
        <input type="password" name="senha" required>
        <label>Pergunta secreta</label>
        <select name="pergunta">
            <option>Qual o nome do seu primeiro pet?</option>
            <option>Qual o nome da sua mãe?</option>
            <option>Qual sua cidade natal?</option>
            <option>Qual seu time de futebol?</option>
            <option>Qual o nome da sua escola?</option>
        </select>
        <label>Resposta secreta</label>
        <input type="text" name="resposta" required placeholder="Sua resposta (sem acento)">
        <button type="submit">Cadastrar</button>
    </form>
    <a href="login.php">Já tenho conta → Entrar</a>
</div>
</body>
</html>
