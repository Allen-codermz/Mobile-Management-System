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
            <section class="catalog-flow">
                <div class="section-heading">
                    <div>
                        <span class="label">ESTRUTURA</span>
                        <h2>
                            Do mundo ao dispositivo
                        </h2>
                    </div>
                    <p>
                        A organização dos dados do teu catálogo.
                    </p>
                </div>
                <div class="flow">
                    <div class="flow-item">
                        <div class="flow-icon">
                            <i class="fa-solid fa-industry"></i>
                        </div>
                        <div>
                            <span>Fabricantes</span>
                            <strong>18</strong>
                        </div>
                    </div>
                    <div class="arrow"> → </div>
                    <div class="flow-item">
                        <div class="flow-icon">
                            <i class="fa-solid fa-tag"></i>
                        </div>
                        <div>
                            <span>Marcas</span>
                            <strong>31</strong>
                        </div>
                    </div>
                    <div class="arrow"> → </div>
                    <div class="flow-item">
                        <div class="flow-icon">
                            <i class="fa-solid fa-cubes"></i>
                        </div>
                        <div>
                            <span>Modelos</span>
                            <strong>76</strong>
                        </div>
                    </div>
                    <div class="arrow"> → </div>
                    <div class="flow-item highlight">
                        <div class="flow-icon">
                            <i class="fa-solid fa-mobile-screen"></i>
                        </div>
                        <div>
                            <span>Celulares</span>
                            <strong>128</strong>
                        </div>
                    </div>
                </div>
            </section>
            <section class="bottom-grid">
                <div class="card colors-card">
                    <div class="card-title">
                        <div>
                            <span class="label">PALETA</span>
                            <h3>Cores registadas</h3>
                        </div>
                        <i class="fa-solid fa-palette"></i>
                    </div>
                    <div class="colors-list">
                        <div class="color-row">
                            <div class="color-name">
                                <span class="color-dot black"></span>
                                Preto
                            </div>
                            <strong>34</strong>
                        </div>
                        <div class="color-row">
                            <div class="color-name">
                                <span class="color-dot white"></span>
                                Branco
                            </div>
                            <strong>27</strong>
                        </div>
                        <div class="color-row">
                            <div class="color-name">
                                <span class="color-dot blue"></span>
                                Azul
                            </div>
                            <strong>19</strong>
                        </div>
                        <div class="color-row">
                            <div class="color-name">
                                <span class="color-dot red"></span>
                                Vermelho
                            </div>
                            <strong>8</strong>
                        </div>
                    </div>
                </div>
                <div class="card models-card">
                    <div class="card-title">
                        <div>
                            <span class="label">DESTAQUES</span>
                            <h3>Modelos mais registados</h3>
                        </div>
                        <i class="fa-solid fa-chart-simple"></i>
                    </div>
                    <div class="models-list">
                        <div class="model-row">
                            <div>
                                <span class="rank">01</span>
                                <span>Galaxy A15</span>
                            </div>
                            <strong>24</strong>
                        </div>
                        <div class="model-row">
                            <div>
                                <span class="rank">02</span>
                                <span>iPhone 13</span>
                            </div>
                            <strong>19</strong>
                        </div>
                        <div class="model-row">
                            <div>
                                <span class="rank">03</span>
                                <span>Redmi Note 13</span>
                            </div>
                            <strong>16</strong>
                        </div>
                        <div class="model-row">
                            <div>
                                <span class="rank">04</span>
                                <span>Galaxy S24</span>
                            </div>
                            <strong>11</strong>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>