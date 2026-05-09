<?php
include 'conexao.php';
session_start();
$erro = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $senha = $_POST['senha'];
    $result = mysqli_query($conn, "SELECT * FROM usuarios WHERE email='$email'");
    $user = mysqli_fetch_assoc($result);
    if ($user && password_verify($senha, $user['senha'])) {
        $_SESSION['usuario_id'] = $user['id'];
        $_SESSION['usuario_nome'] = $user['nome'];
        header('Location: loja.php');
    } else {
        $erro = 'Email ou senha incorretos!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body { font-family:Arial; background:#0f0f1a; color:#fff; display:flex; justify-content:center; align-items:center; height:100vh; margin:0; }
        .box { background:#16213e; padding:40px; border-radius:12px; width:350px; }
        h2 { color:#e94560; text-align:center; margin-bottom:25px; }
        label { color:#aaa; font-size:0.9em; }
        input { width:100%; padding:10px; margin:6px 0 16px; background:#0f3460; border:none; border-radius:8px; color:#fff; font-size:1em; }
        button { width:100%; padding:12px; background:#e94560; color:#fff; border:none; border-radius:8px; font-size:1em; cursor:pointer; }
        .erro { color:#ff6b6b; text-align:center; margin-bottom:15px; }
        a { color:#e94560; display:block; text-align:center; margin-top:15px; font-size:0.9em; }
    </style>
</head>
<body>
<div class="box">
    <h2>🎮 Entrar</h2>
    <?php if($erro) echo '<p class="erro">'.$erro.'</p>'; ?>
    <form method="POST">
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Senha</label>
        <input type="password" name="senha" required>
        <button type="submit">Entrar</button>
    </form>
    <a href="recuperar.php">Esqueci minha senha</a>
    <a href="cadastro.php">Não tenho conta → Cadastrar</a>
    <a href="loja.php">← Voltar para loja</a>
</div>
</body>
</html>
