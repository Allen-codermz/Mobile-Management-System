<?php

session_start();

if (!isset($_SESSION['codigoUsuario'])) {
    header("Location: login.php");
    exit;
}

include_once __DIR__ . '/../../config/conexao.php';
include_once __DIR__ . '/../../controller/ControllerUsuario.php';

$controllerUsuario = new ControllerUsuario($conexao);

$erro = "";
$sucesso = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['alterarSenha'])) {

    $senhaAntiga = $_POST['senhaAntiga'] ?? '';
    $senhaNova = $_POST['senhaNova'] ?? '';
    $confirmarSenha = $_POST['confirmarSenha'] ?? '';
    $codigoUsuario = $_SESSION['codigoUsuario'];

    if (empty($senhaAntiga) || empty($senhaNova) || empty($confirmarSenha)) {
        $erro = "Preencha todos os campos.";
    } elseif ($senhaNova !== $confirmarSenha) {
        $erro = "As novas senhas não coincidem.";
    } elseif (strlen($senhaNova) < 6) {
        $erro = "A nova senha deve ter pelo menos 6 caracteres.";
    } elseif ($senhaAntiga === $senhaNova) {
        $erro = "A nova senha deve ser diferente da senha actual.";
    } else {
        $usuario = $controllerUsuario->encontrarId($codigoUsuario);

        if ($usuario === null) {
            $erro = "Utilizador não encontrado.";
        } elseif (!password_verify($senhaAntiga, $usuario->getSenha())) {
            $erro = "A senha actual está incorrecta.";
        } else {
            $resultado = $controllerUsuario->alterarSenha(
                $codigoUsuario,
                $senhaNova
            );

            if ($resultado) {
                $_SESSION['primeiro_acesso'] = 0;
                header("Location: dashboard.php");
                exit;
            } else {
                $erro = "Não foi possível alterar a senha.";
            }
        }
    }
}

$primeiroAcesso = (int) ($_SESSION['primeiro_acesso'] ?? 0);

?>

<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar senha | Sistema de Gestão de Celulares</title>
    <link rel="stylesheet" href="../css/recuperar.css">
</head>

<body>
    <div class="main">
        <div class="esquerda">
            <img src="../images/login-animate.svg">
        </div>

        <div class="direita">
            <form class="form" id="alterarSenha" method="POST">
                <h2> <?= $primeiroAcesso === 1 ? 'Defina a sua nova senha' : 'Alterar a minha senha' ?> </h2>

                <?php if ($primeiroAcesso === 1): ?>
                    <h4>Por segurança, deve alterar a <br> senha padrão antes de continuar.</h4>
                <?php endif; ?>

                <?php if (!empty($erro)): ?>
                    <p class="erro"> <?= htmlspecialchars($erro) ?> </p>
                <?php endif; ?>

                <label for="senhaAntiga">Senha actual</label>
                <input type="password" id="senhaAntiga" name="senhaAntiga" placeholder="Introduza a senha actual" autocomplete="current-password" required>

                <label for="senhaNova">Nova senha</label>
                <input type="password" id="senhaNova" name="senhaNova" placeholder="Introduza a nova senha" minlength="6" autocomplete="new-password" required>

                <label for="confirmarSenha">Confirmar nova senha</label>
                <input type="password" id="confirmarSenha" name="confirmarSenha" placeholder="Repita a nova senha" minlength="6" autocomplete="new-password" required>

                <div class="actions">
                    <button type="submit" name="alterarSenha" class="btn1"> Alterar senha </button>

                    <?php if ($primeiroAcesso !== 1): ?>
                        <a class="link" href="../pages/dashboard.php"> Voltar ao dashboard </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>
</body>

</html>