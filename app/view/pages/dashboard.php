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

                <a href="../pages/login.php" class="menu-item-logout">
                    <i class="fa-solid fa-sign-out-alt"></i>
                    <p>Log Out</p>
                </a>
            </nav>
        </aside>

        <main class="main">
            <header class="dashboard-header">
                <div>
                    <h1>GESTÃO DE CELULARES</h1>
                    <p class="subtitle">Explore fabricantes, marcas, modelos e dispositivos registados no sistema.</p>
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
                        128
                    </div>
                    <p>celulares registados</p>
                    <div class="stat-details">
                        <div>
                            <strong>18</strong>
                            <span>Fabricantes</span>
                        </div>
                        <div>
                            <strong>31</strong>
                            <span>Marcas</span>
                        </div>
                        <div>
                            <strong>76</strong>
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
                        <div class="manufacturer">
                            <span>Samsung</span>
                            <div class="bar">
                                <div style="width: 85%;"></div>
                            </div>
                            <strong>42</strong>
                        </div>
                        <div class="manufacturer">
                            <span>Apple</span>
                            <div class="bar">
                                <div style="width: 65%;"></div>
                            </div>
                            <strong>31</strong>
                        </div>
                        <div class="manufacturer">
                            <span>Xiaomi</span>
                            <div class="bar">
                                <div style="width: 50%;"></div>
                            </div>
                            <strong>24</strong>
                        </div>
                        <div class="manufacturer">
                            <span>Huawei</span>
                            <div class="bar">
                                <div style="width: 30%;"></div>
                            </div>
                            <strong>14</strong>
                        </div>
                    </div>
                </div>
            </section>
            
        </main>
    </div>
</body>

</html>