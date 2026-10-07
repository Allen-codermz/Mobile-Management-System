<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../auth/permissoes.php';

require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../model/usuario.php';
require_once __DIR__ . '/../../controller/ControllerUsuario.php';

$controllerUsuario = new ControllerUsuario($conexao);

$usuarios = $controllerUsuario->listar();

$nomeUsuario = $_SESSION['nome'];
$apelidoUsuario = $_SESSION['apelido'];
$codigoPerfil = $_SESSION['codigoPerfil'];


switch ($codigoPerfil) {
    case 1:
        $nomePerfil = "Operador";
        break;

    case 2:
        $nomePerfil = "SuperOperador";
        break;

    case 3:
        $nomePerfil = "Administrador";
        break;

    case 4:
        $nomePerfil = "Auditor";
        break;

    default:
        $nomePerfil = "Utilizador";
}

?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Page Title</title>
    <link rel='stylesheet' href='../css/painelADM.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <div class="layout">
        <aside class="sidebar">
            <h3>Gestão de Celulares</h3>

            <nav class="menu">
                <a href="../pages/dashboard.php" class="menu-item">
                    <i class="fa-solid fa-house"></i>
                    <p>Dashboard</p>
                </a>

                <a href="../pages/cadastroDeCelular.php" class="menu-item">
                    <i class="fa-solid fa-mobile-screen"></i>
                    <p>Celulares</p>
                </a>

                <a href="../pages/cadastroDeFabricante.php" class="menu-item">
                    <i class="fa-solid fa-building"></i>
                    <p>Fabricantes</p>
                </a>

                <a href="#" class="menu-item ">
                    <i class="fa-solid fa-tag"></i>
                    <p>Marcas</p>
                </a>

                <a href="../pages/cadastroDeModelo.php" class="menu-item">
                    <i class="fa-solid fa-box"></i>
                    <p>Modelos</p>
                </a>

                <a href="../pages/cadastroDaCor.php" class="menu-item">
                    <i class="fa-solid fa-palette"></i>
                    <p>Cores</p>
                </a>

                <?php if (podeGerirUsuarios()): ?>
                    <a href="../pages/cadastroUsuario.php" class="menu-item active">
                        <i class="fa-solid fa-user-gear"></i>
                        <p>Administração</p>
                    </a>
                <?php endif; ?>

                <?php if (podeVerLogs()): ?>
                    <a href="../pages/logsDoSistema.php" class="menu-item">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <p>Logs do Sistema</p>
                    </a>
                <?php endif; ?>

                <a href="logout.php" class="menu-item-logout">
                    <i class="fa-solid fa-sign-out-alt"></i>
                    <p>Log Out</p>
                </a>
            </nav>
        </aside>

            <main class="main">
            <header class="page-header">
                <div>
                    <h1>Administração</h1>
                    <p>
                        Gestão de usuarios
                    </p>
                </div>
                <div class="user-profile">
                    <div class="user-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <strong> <?= htmlspecialchars($nomeUsuario . ' ' . $apelidoUsuario) ?> </strong>
                        <span> <?= htmlspecialchars($nomePerfil) ?> </span>
                    </div>
                </div>
            </header>
            <section class="logs-container">
                <div class="logs-header">
                    <div>
                        <h2>
                            Usuarios
                        </h2>
                        <p>
                            <?= count($usuarios) ?> usuarios encontrados
                        </p>
                    </div>
                    <div class="botao">
                        <a href="../pages/cadastroUsuario.php" class="btn-guardar"> <i class="fa-solid fa-plus"></i> Adicionar fabricante </a>
                    </div>
                </div>

                <div class="tabela-container">
                    <table class="tabela-logs">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Utilizador</th>
                                <th>Email</th>
                                <th>Contacto</th>
                                <th>Genero</th>
                                <th>Estado civil</th>
                                <th>Bilhete De Identidade</th>
                                <th>Acões</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($usuarios) > 0): ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td>
                                            <span class="codigo">
                                                #<?= $usuario->getCodigousuario() ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="utilizador">
                                                <div class="avatar">
                                                    <i class="fa-solid fa-user"></i>
                                                </div>
                                                <div>
                                                    <strong>
                                                        <?= htmlspecialchars(
                                                            $usuario->getNome() . ' ' .
                                                                $usuario->getApelido()
                                                        ) ?>
                                                    </strong>
                                                    <span>
                                                        @<?= htmlspecialchars(
                                                                $usuario->getUsername()
                                                            ) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>

                                        <td> <?= htmlspecialchars(
                                                    $usuario->getEmail()
                                                ) ?>
                                        </td>

                                        <td> <?= htmlspecialchars(
                                                    $usuario->getContacto()
                                                ) ?>
                                        </td>

                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td>
                                            <div>
                                                <form method='get'>
                                                    <input type='hidden' name='id' value='{$marca->getcodigoMarca()}'>
                                                    <button type='submit' name='editar' class='btn-editar'> <i class='fa-solid fa-pen'></i> Editar </button>
                                                </form>
                                                <form method='get'>
                                                    <input type='hidden' name='id' value='{$marca->getcodigoMarca()}'>
                                                    <button type='submit' name='apagar' class='btn-apagar'> <i class='fa-solid fa-trash'></i> Apagar </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan='5' class='sem-registos'>
                                        <img src='../images/file-searching-animate.svg' alt='' width=400px>
                                    </td>
                                </tr>"
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>
</body>

</html>