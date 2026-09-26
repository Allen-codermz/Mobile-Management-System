<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ . '/../../model/fabricante.php';
include_once __DIR__ . '/../../controller/ControllerFabricante.php';
include_once __DIR__ . '/../../config/conexao.php';


$fabricanteController = new ControllerFabricante($conexao);
$fabricantes = $fabricanteController->listar();
$paises = array();
$continetes = $fabricanteController->listarContinentes();

//CREATE
if (isset($_POST['salvar'])) {
    if (isset($_POST["nome"]) && isset($_POST["codigoPais"])) {
        $nome = $_POST["nome"];
        $codigoPais = $_POST["codigoPais"];
        $sql = "insert into fabricante values(null,'{$nome}','{$codigoPais}')";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            header("Location: cadastroDeFabricante.php");
            exit;
        } else {
            echo "fabricante não registrado";
        }
        $fabricantes = $fabricanteController->listar();
    }
}

//UPDATE FOR REAL
if (isset($_POST['actualizar'])) {
    if (isset($_POST["nome"]) && isset($_POST["codigoPais"])) {
        $id = $_POST['id'];
        $nome = $_POST["nome"];
        $codigoPais = $_POST["codigoPais"];
        $sql = "update fabricante set fabricante='{$nome}', codigoPais='{$codigoPais}' where codigoFabricante={$id}";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            header("Location: cadastroDeFabricante.php");
            exit;
        } else {
            echo "Fabricante não actualizado";
        }
        $fabricantes = $fabricanteController->listar();
    }
}

//DELETE FOR REAL
if (isset($_POST['apagar'])) {
    $id = $_POST['id'];
    if ($fabricanteController->remover($id)) {
        header("Location: cadastroDeFabricante.php");
        exit;
    } else {
        echo "Fabricante não foi removido";
    }
}

//
if (isset($_GET['id'])) {

    $id = $_GET['id'];

    $fabricante = $fabricanteController->encontraId($id);

    if ($fabricante) {
        $paises = $fabricanteController->listarPaises(
            $conexao,
            $fabricante->getCodigoContinente()
        );
    }
}

if (isset($_GET['codigoContinente'])) {
    $codigoContinente = $_GET['codigoContinente'];
    $paises = $fabricanteController->listarPaises($conexao, $codigoContinente);
}
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Page Title</title>
    <link rel='stylesheet' href='../css/cadatroDefabricante.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>
    <div class="layout">
        <aside class="sidebar">

            <h3>Gestão de Celulares</h3>

            <nav class="menu">
                <a href="#" class="menu-item">
                    <i class="fa-solid fa-house"></i>
                    <p>Dashboard</p>
                </a>

                <a href="#" class="menu-item">
                    <i class="fa-solid fa-mobile-screen"></i>
                    <p>Celulares</p>
                </a>

                <a href="#" class="menu-item active">
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

                <!-- <a href="#" class="menu-item">
                    <i class="fa-solid fa-palette"></i>
                    <smal></smal>
                </a> -->

                <a href="#" class="menu-item-logout">
                    <i class="fa-solid fa-sign-out-alt"></i>
                    <p>Log Out</p>
                </a>
            </nav>
        </aside>

        <main class="main">
            <header class="page-header">
                <div>
                    <h1>Fabricantes</h1>
                </div>
            </header>

            <section class="formulario-card">
                <form method="get" class="formulario">
                    <div class="campo">
                        <label for="codigoContinente"> Continente</label>
                        <div class="input-wrapper">
                            <i class="fa-solid fa-earth-africa"></i>
                            <select name="codigoContinente" id="codigoContinente" onchange="this.form.submit()">
                                <option value=""> Selecione o continente</option>
                                <?php foreach ($continetes as $continente) { ?>
                                    <option value="<?= $continente['codigoContinente']; ?>" <?= isset($_GET['codigoContinente']) && $_GET['codigoContinente'] == $continente['codigoContinente'] ? 'selected' : ''; ?>> <?= $continente['continente']; ?> </option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </form>

                <form action="" method="post" class="formulario">
                    <input type="hidden" name="id" value="<?= isset($fabricante) ? $fabricante->getCodigoFabricante() : ''; ?>">
                    <div class="campos-linha">
                        <div class="campo">
                            <label for="codigoPais">País de origem</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-location-dot"></i>
                                <select name="codigoPais" id="codigoPais" required>
                                    <option value="">Selecione o país</option>
                                    <?php foreach ($paises as $pais) { ?>
                                        <option
                                            value="<?= $pais['codigoPais']; ?>"
                                            <?= isset($fabricante) &&
                                                $fabricante->getCodigoPais() == $pais['codigoPais']
                                                ? 'selected'
                                                : ''; ?>>
                                            <?= $pais['pais']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="campo">
                            <label for="fabricante">Fabricante</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-building"></i>
                                <input type="text" id="fabricante" name="nome" placeholder="Ex: Samsung Eletronics" value="<?= isset($fabricante) ? $fabricante->getNome() : ''; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="botoes">
                        <button type="reset" class="btn-cancelar"> <i class="fa-solid fa-eraser"></i> Limpar </button>
                        <?php if (isset($fabricante)) { ?>
                            <button type="submit" name="actualizar" class="btn-guardar"> <i class="fa-solid fa-rotate"></i> Actualizar </button>
                        <?php } else { ?>
                            <button type="submit" name="salvar" class="btn-guardar"> <i class="fa-solid fa-plus"></i> Adicionar fabricante </button>
                        <?php } ?>
                    </div>
                </form>
            </section>


            <section>
                <div class="cards">
                    <?php
                    if (count($fabricantes) > 0) {
                        foreach ($fabricantes as $fabricante) {
                            echo "
                        <div class='card'>
                        <div class='card-top'>
                        <span class='codigo'>{$fabricante->getCodigoFabricante()}</span>
                        </div>
                        <div class='card-info'>
                        <h3>{$fabricante->getNome()}</h3>
                        <p class='pais'> <i class='fa-solid fa-location-dot'></i> {$fabricante->getPais()} </p>
                        </div>
                        <div class='acoes'>
                        <form method='get'>
                        <input type='hidden' name='id' value='{$fabricante->getCodigoFabricante()}'>
                        <button type='submit' name='editar' class='btn-editar'> <i class='fa-solid fa-pen'></i> Editar </button>
                        </form>
                        <form method='post'>
                        <input type='hidden' name='id' value='{$fabricante->getCodigoFabricante()}'>
                        <button type='submit' name='apagar' class='btn-apagar'> <i class='fa-solid fa-trash'></i> Apagar </button>
                        </form>
                        </div>
                        </div>";
                        }
                    }
                    ?>
                </div>
            </section>
        </main>
</body>

</html>