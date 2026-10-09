<?php
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../auth/permissoes.php';

exigirGestaoUsuarios();

require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../model/usuario.php';
require_once __DIR__ . '/../../controller/ControllerUsuario.php';


$usuarioController = new ControllerUsuario($conexao);
$erro = "";
$sucesso = "";

$perfis = $usuarioController->listarPerfis();
$estados = $usuarioController->listarEstadoCivil();

if (isset($_POST['criar'])) {

    $nome = $_POST['nome'];
    $apelido = $_POST['apelido'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $contacto = $_POST['contacto'];
    $genero = $_POST['genero'];
    $estadoCivil = $_POST['estadoCivil'];
    $bilhete = $_POST['bilhete'];
    $codigoPerfil = $_POST['codigoPerfil'];


    // Verificar se os campos obrigatórios foram preenchidos

    if (empty($nome) || empty($apelido) || empty($username) || empty($email) || empty($contacto) || empty($genero) || empty($estadoCivil)   || empty($bilhete) || empty($codigoPerfil)) {
        $erro = "Preencha todos os campos.";
    } else {

        $usuario = new usuario(null, $nome, $apelido, $username, $email, $estadoCivil, null, null, $genero, $bilhete, $contacto, '', $codigoPerfil, null);
        $resultado = $usuarioController->criar($usuario);
        if ($resultado) {
            $sucesso = "Usuário criado com sucesso!";
        } else {
            $erro = "Não foi possível criar o usuário.";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/cadastro.css">
    <title>Cadastro | Sistema de Gestão de Celulares</title>
</head>

<body>
    <div class="main">
        <div class="esquerda">
            <img src="../images/sign-up-animate.svg" alt="">
        </div>
        <div class="direita">
            <form class="form" id="cadastro" method="POST">

                <h2><b>Criar Usuario</b></h2>
                <div class="formulario">
                    <div>
                        <label for="nome">Nome</label>
                        <input name="nome" type="text" id="nome" placeholder="Anacleto">

                        <label for="apelido">Apelido</label>
                        <input name="apelido" type="text" id="apelido" placeholder="juvenal">

                        <label for="username">Username</label>
                        <input name="username" type="text" id="username" placeholder="AnacletoAgenteSecreto">

                        <label>Género</label>
                        <div class="opcoes">
                            <label>
                                <input type="radio" name="genero" value="Masculino" required>
                                Masculino
                            </label>
                            <label>
                                <input type="radio" name="genero" value="Feminino">
                                Feminino
                            </label>
                        </div>

                        <label for="estadoCivil">Estado Civil</label>
                        <select name="estadoCivil" id="estadoCivil" required>
                            <option value=""> Selecione o estado civil</option>
                            <?php foreach ($estados as $estado): ?>
                                <option value="<?= $estado['codigoEstadoCivil'] ?>"> <?= htmlspecialchars($estado['estado_civil']) ?> </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="part">
                        <label for="contacto">Contacto</label>
                        <input name="contacto" type="text" id="contacto" placeholder="+258 84 1234 567" maxlength="9">

                        <label for="email">Email</label>
                        <input name="email" type="email" id="email" placeholder="Anacleto@gmail.com">

                        <label for="bilhete">Bilhete de Identidade</label>
                        <input name="bilhete" type="text" id="bilhete" placeholder="110100123456A" maxlength="13">

                        <label for="codigoPerfil">Perfil</label>
                        <select name="codigoPerfil" id="codigoPerfil" required>
                            <option value=""> Selecione o perfil </option>

                            <?php foreach ($perfis as $perfil): ?>
                                <option value="<?= $perfil['codigoPerfil'] ?>">
                                    <?= htmlspecialchars($perfil['nome']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <label for="senha">Palavra-passe</label>
                        <input type="text" id="senha" value="<?= htmlspecialchars(ControllerUsuario::SENHA_PADRAO) ?>" readonly>
                    </div>
                </div>

                <div class="actions">
                    <button name="criar" type="submit" class="btn1"> Criar conta </button>

                    <a class="link" href="../pages/dashboard.php"> Voltar ao dashboard </a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>