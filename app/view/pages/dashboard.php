<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../auth/permissoes.php';
require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../controller/ControllerDashboard.php';

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


$dashboardController = new ControllerDashboard($conexao);

$totalCelulares = $dashboardController->contarCelulares();
$totalFabricantes = $dashboardController->contarFabricantes();
$totalMarcas = $dashboardController->contarMarcas();
$totalModelos = $dashboardController->contarModelos();
$totalCores = $dashboardController->contarCores();
$totalUsuarios = $dashboardController->contarUsuarios();

$fabricantes = $dashboardController->celularesPorFabricante();


?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Page Title</title>
    <link rel='stylesheet' href='../css/dashboard.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <div class="layout">
        <aside class="sidebar">
            <h3>Gestão de Celulares</h3>

            <nav class="menu">
                <a href="../pages/dashboard.php" class="menu-item active">
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

                <a href="../pages/CadastroDeMarca.php" class="menu-item">
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
            <header class="dashboard-header">

                <div class="welcome">
                    <span class="welcome-label">VAMOS GERIR CELULARES DE MANEIRA EFICIENTE??</span>
                    <h1> Olá, <span class="nome-typing"> <?= htmlspecialchars($nomeUsuario) ?></span> </h1>
                    <p class="subtitle"> Bem-vindo ao Sistema de Gestão de Celulares. Aqui está a visão geral do catálogo. </p>
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

            <section class="overview">
                <div class="main-stat card">
                    <div class="stat-header">
                        <span>CATÁLOGO</span>
                        <div class="icon-box">
                            <i class="fa-solid fa-mobile-screen"></i>
                        </div>
                    </div>
                    <div class="big-number">
                        <?= $totalCelulares ?>
                    </div>
                    <p> <?= $totalCelulares == 1 ? 'celular registado' : 'celulares registados' ?> </p>
                    <div class="stat-details">
                        <div>
                            <strong><?= $totalFabricantes ?></strong>
                            <span>Fabricantes</span>
                        </div>
                        <div>
                            <strong><?= $totalMarcas ?></strong>
                            <span>Marcas</span>
                        </div>
                        <div>
                            <strong><?= $totalModelos ?></strong>
                            <span>Modelos</span>
                        </div>
                    </div>
                </div>
                <div class="manufacturers card">
                    <div class="card-title">
                        <div>
                            <span class="label">FABRICANTES</span>
                            <h3>Presença no catálogo</h3>
                        </div>
                        <i class="fa-solid fa-building"></i>
                    </div>
                    <div class="manufacturer-list">
                        <?php if (count($fabricantes) > 0): ?>
                            <?php foreach ($fabricantes as $fabricante): ?>
                                <?php
                                $total = $fabricante['total'];
                                if ($totalCelulares > 0) {
                                    $percentagem = ($total / $totalCelulares) * 100;
                                } else {
                                    $percentagem = 0;
                                }
                                ?>
                                <div class="manufacturer">
                                    <span>
                                        <?= htmlspecialchars($fabricante['fabricante']) ?>
                                    </span>
                                    <div class="bar">
                                        <div style="width: <?= $percentagem ?>%;"></div>
                                    </div>
                                    <strong>
                                        <?= $total ?>
                                    </strong>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <p class="sem-dados">
                                Nenhum fabricante registado.
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </section>

        </main>
    </div>
</body>

</html>