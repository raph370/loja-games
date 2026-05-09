<?php
include 'conexao.php';
$etapa = 1;
$erro = '';
$sucesso = '';
$usuario = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['email'])) {
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $result = mysqli_query($conn, "SELECT * FROM usuarios WHERE email='$email'");
        $usuario = mysqli_fetch_assoc($result);
        if ($usuario) {
            $etapa = 2;
        } else {
            $erro = 'Email não encontrado!';
        }
    } elseif (isset($_POST['resposta'])) {
        $id = $_POST['id'];
        $resposta = strtolower(mysqli_real_escape_string($conn, $_POST['resposta']));
        $result = mysqli_query($conn, "SELECT * FROM usuarios WHERE id=$id");
        $usuario = mysqli_fetch_assoc($result);
        if ($usuario && $usuario['resposta'] == $resposta) {
            $etapa = 3;
        } else {
            $etapa = 2;
            $erro = 'Resposta incorreta!';
        }
    } elseif (isset($_POST['nova_senha'])) {
        $id = $_POST['id'];
        $nova = password_hash($_POST['nova_senha'], PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE usuarios SET senha='$nova' WHERE id=$id");
        $sucesso = 'Senha alterada com sucesso!';
        $etapa = 4;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recuperar Senha</title>
    <style>
        body { font-family:Arial; background:#0f0f1a; color:#fff; display:flex; justify-content:center; align-items:center; min-height:100vh; margin:0; }
        .box { background:#16213e; padding:40px; border-radius:12px; width:380px; }
        h2 { color:#e94560; text-align:center; margin-bottom:10px; }
        .steps { display:flex; justify-content:center; gap:8px; margin-bottom:25px; }
        .step { width:30px; height:30px; border-radius:50%; background:#0f3460; display:flex; align-items:center; justify-content:center; font-size:0.85em; color:#aaa; }
        .step.ativo { background:#e94560; color:#fff; }
        .step.feito { background:#00ff88; color:#0f0f1a; }
        label { color:#aaa; font-size:0.9em; }
        input { width:100%; padding:10px; margin:6px 0 16px; background:#0f3460; border:none; border-radius:8px; color:#fff; font-size:1em; }
        button { width:100%; padding:12px; background:#e94560; color:#fff; border:none; border-radius:8px; font-size:1em; cursor:pointer; }
        .erro { color:#ff6b6b; text-align:center; margin-bottom:15px; }
        .sucesso { color:#00ff88; text-align:center; margin-bottom:15px; }
        a { color:#e94560; display:block; text-align:center; margin-top:15px; font-size:0.9em; }
        .pergunta { background:#0f3460; padding:12px; border-radius:8px; color:#aaa; margin-bottom:16px; font-style:italic; }
    </style>
</head>
<body>
<div class="box">
    <h2>🔑 Recuperar Senha</h2>
    <div class="steps">
        <div class="step <?=$etapa>=1?'ativo':''?>">1</div>
        <div class="step <?=$etapa>=2?($etapa>2?'feito':'ativo'):''?>">2</div>
        <div class="step <?=$etapa>=3?($etapa>3?'feito':'ativo'):''?>">3</div>
    </div>

    <?php if($erro) echo '<p class="erro">'.$erro.'</p>'; ?>
    <?php if($sucesso) echo '<p class="sucesso">'.$sucesso.'</p>'; ?>

    <?php if($etapa == 1): ?>
    <form method="POST">
        <label>Digite seu email cadastrado</label>
        <input type="email" name="email" required placeholder="seu@email.com">
        <button type="submit">Continuar</button>
    </form>

    <?php elseif($etapa == 2): ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?=$usuario['id']?>">
        <div class="pergunta">❓ <?=htmlspecialchars($usuario['pergunta'])?></div>
        <label>Sua resposta</label>
        <input type="text" name="resposta" required placeholder="Responda sem acento">
        <button type="submit">Verificar</button>
    </form>

    <?php elseif($etapa == 3): ?>
    <form method="POST">
        <input type="hidden" name="id" value="<?=$usuario['id']?>">
        <label>Nova senha</label>
        <input type="password" name="nova_senha" required minlength="6">
        <label>Confirmar nova senha</label>
        <input type="password" name="confirmar" required minlength="6">
        <button type="submit" onclick="
            if(this.form.nova_senha.value != this.form.confirmar.value){
                alert('Senhas não conferem!'); return false;
            }">Salvar Nova Senha</button>
    </form>

    <?php elseif($etapa == 4): ?>
    <a href="login.php" style="background:#e94560;color:#fff;padding:12px;border-radius:8px;text-decoration:none;display:block;text-align:center;">Ir para o Login</a>

    <?php endif; ?>

    <?php if($etapa == 1): ?>
    <a href="login.php">← Voltar ao login</a>
    <?php endif; ?>
</div>
</body>
</html>
