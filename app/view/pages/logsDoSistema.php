<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../auth/permissoes.php';

require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../model/Logs.php';
require_once __DIR__ . '/../../controller/ControllerLogs.php';

$controllerLog = new ControllerLog($conexao);

$logs = $controllerLog->listar();

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
<html lang="pt">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Logs do Sistema</title>

    <link rel="stylesheet" href="../css/logsDoSistema.css">

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

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

                <a href="#" class="menu-item">
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
                    <a href="../pages/cadastroUsuario.php" class="menu-item">
                        <i class="fa-solid fa-user-gear"></i>
                        <p>Administração</p>
                    </a>
                <?php endif; ?>

                <?php if (podeVerLogs()): ?>
                    <a href="../pages/logsDoSistema.php" class="menu-item active">
                        <i class="fa-solid fa-sliders"></i>
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
                    <h1>Logs do Sistema</h1>
                    <p>
                        Registo das atividades realizadas no sistema
                    </p>
                </div>
                <div class="user-profile">
                    <div class="user-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <div>
                        <strong>
                            <?= htmlspecialchars($nomeUsuario . ' ' . $apelidoUsuario) ?>
                        </strong>
                        <span>
                            <?= htmlspecialchars($nomePerfil) ?>
                        </span>
                    </div>
                </div>
            </header>

            <section class="logs-container">
                <div class="logs-header">
                    <div>
                        <h2>
                            Atividades
                        </h2>
                        <p>
                            <?= count($logs) ?> registos encontrados
                        </p>
                    </div>
                    <div class="logs-icon">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                    </div>
                </div>

                <div class="tabela-container">
                    <table class="tabela-logs">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Utilizador</th>
                                <th>Ação</th>
                                <th>Descrição</th>
                                <th>Data e Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($logs) > 0): ?>
                                <?php foreach ($logs as $log): ?>
                                    <tr>
                                        <td>
                                            <span class="codigo">
                                                #<?= $log->getCodigoLog() ?>
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
                                                            $log->getNomeUsuario() . ' ' .
                                                                $log->getApelidoUsuario()
                                                        ) ?>
                                                    </strong>
                                                    <span>
                                                        @<?= htmlspecialchars(
                                                                $log->getUsernameUsuario()
                                                            ) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $classeAcao = strtolower(
                                                $log->getAcao()
                                            );
                                            ?>
                                            <span class="acao <?= $classeAcao ?>">
                                                <?php if ($log->getAcao() == "LOGIN"): ?>
                                                    <i class="fa-solid fa-right-to-bracket"></i>
                                                <?php elseif ($log->getAcao() == "LOGOUT"): ?>
                                                    <i class="fa-solid fa-right-from-bracket"></i>
                                                <?php elseif ($log->getAcao() == "CRIAR"): ?>
                                                    <i class="fa-solid fa-plus"></i>
                                                <?php elseif ($log->getAcao() == "EDITAR"): ?>
                                                    <i class="fa-solid fa-pen"></i>
                                                <?php elseif ($log->getAcao() == "APAGAR"): ?>
                                                    <i class="fa-solid fa-trash"></i>
                                                <?php endif; ?>
                                                <?= htmlspecialchars($log->getAcao()) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars(
                                                $log->getDescricao()
                                            ) ?>
                                        </td>
                                        <td>
                                            <div class="data-hora">
                                                <strong>
                                                    <?= date(
                                                        "d/m/Y",
                                                        strtotime($log->getDataHora())
                                                    ) ?>
                                                </strong>
                                                <span>
                                                    <?= date(
                                                        "H:i:s",
                                                        strtotime($log->getDataHora())
                                                    ) ?>
                                                </span>
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