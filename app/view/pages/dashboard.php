<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Page Title</title>
    <link rel='stylesheet' href='../css/cadastroDeCor.css'>
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

                <a href="#" class="menu-item">
                    <i class="fa-solid fa-palette"></i>
                    <p>Cores</p>
                </a>

                <a href="../pages/login.php" class="menu-item-logout">
                    <i class="fa-solid fa-sign-out-alt"></i>
                    <p>Log Out</p>
                </a>
            </nav>
        </aside>

        <main class="main">
            <header class="page-header">
            </header>
        </main>
</body>

</body>

</html>