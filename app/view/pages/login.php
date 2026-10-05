<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

if (isset($_SESSION['codigoUsuario'])) {
    header("Location: dashboard.php");
    exit;
}

include_once __DIR__ . '/../../config/conexao.php';
include_once __DIR__ . '/../../controller/controllerLogin.php';
require_once __DIR__ . '/../../model/Logs.php';
require_once __DIR__ . '/../../controller/ControllerLogs.php';


$controllerUsuario = new controllerLogin($conexao);
$erro = "";

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $senha = $_POST['senha'];
    $usuario = $controllerUsuario->login($username, $senha);
    if ($usuario !== null) {
        session_regenerate_id(true);

        $_SESSION['codigoUsuario'] = $usuario->getCodigoUsuario();
        $_SESSION['nome'] = $usuario->getNome();
        $_SESSION['apelido'] = $usuario->getApelido();
        $_SESSION['username'] = $usuario->getUsername();
        $_SESSION['email'] = $usuario->getEmail();
        $_SESSION['codigoPerfil'] = $usuario->getCodigoPerfil();
        $logController = new ControllerLog($conexao);
        $log = new Logs(null, $_SESSION['codigoUsuario'], "LOGIN", "Iniciou sessão no sistema");
        $logController->criar($log);
        header("Location: dashboard.php");
        exit;
    } else {
        $erro = "Username ou senha incorretos.";
    }
}
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Login | Sistema de Gestão de Celulares</title>
    <link rel='stylesheet' href='../css/login.css'>
</head>

<body>
    <div class="main">
        <div class="esquerda">
            <form class="form" id="cadastro" method="POST">
                <h2><b>Seja bem vindo de volta</b></h2>
                <label for="nome">Username</label>
                <input type="text" id="nome" placeholder="Anacleto" name="username">

                <div class="pw">
                    <label for="pw">Palavra-passe</label>
                    <input type="password" name="senha" id="pw" placeholder="••••••">
                </div>
                <div class="actions">
                    <button type="submit" name="login" class="btn1" form="cadastro">Entrar</button>
                    <a class="link" href="../pages/recuperarSenha.php"> esqueceu a sua senha? clique aqui</a>

                </div>
            </form>
        </div>
        <div class="direita">
            <img src="../images/tablet-login-animate.svg" alt="">

        </div>

    </div>

</body>

</html>