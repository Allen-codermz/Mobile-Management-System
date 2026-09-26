<?php 
ini_set('display_errors', 1);

include_once __DIR__ . '/../../model/marca.php';
include_once __DIR__ . '/../../controller/ControllerMarca.php';
include_once __DIR__ . '/../../config/conexao.php';


$marcaController = new ControllerMarca($conexao);
$marcas = $marcaController->listar();
$fabricantes = $marcaController->listarFabricantes();


if (isset($_POST['salvar'])) {
    if (isset($_POST["nome"]) && isset($_POST["codigoFabricante"])) {
        $nome = $_POST["nome"];
        $codigoFabricante = $_POST["codigoFabricante"];
        $marca = new marca(null, $nome, $codigoFabricante, null);
        $result = $marcaController->criar($marca);
        if ($result) {
            echo "
            <div class='mensagem-sucesso'>
                Marca registada com sucesso!
            </div>";
        } else {
            echo "
            <div class='mensagem-erro'>
                Marca não registada!
            </div>";
        }
        $marcas = $marcaController->listar();
    }
}

//UPDATE FOR REAL

if (isset($_POST['actualizar'])) {
if (isset($_POST["nome"]) && isset($_POST["codigoFabricante"])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $codigoFabricante = $_POST['codigoFabricante'];
    $marca = new marca( $id, $nome, $codigoFabricante, null );
    $result = $marcaController->actualizar($marca);
    if ($result) {
        echo "<div class:'mensagem-sucesso'>
        Marca actualizada!";
    } else {
        echo "<div class='mensagem-erro'>
                Marca não actualizda!
            </div>";
    }
    $marcas = $marcaController->listar();
    }}


//DELETE FOR REAL
if (isset($_POST['apagar'])) {
    $id = $_POST['id'];
    if ($marcaController->remover($id)) {
        echo "Marca removida";
        $marcas = $marcaController->listar();
    } else {
        echo "Marca não foi removida";
    }
}

//
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $marca = $marcaController->encontrarId($id);
}


?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Page Title</title>
    <link rel='stylesheet' href='../css/cadastroDeMarca.css'>
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

                <a href="#" class="menu-item active">
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

                <a href="#" class="menu-item-logout">
                    <i class="fa-solid fa-sign-out-alt"></i>
                    <p>Log Out</p>
                </a>
            </nav>
        </aside>

        <main class="main">
            <header class="page-header">
                <div>
                    <h1>Celulares</h1>
                </div>
            </header>

            <section class="formulario-card">
                <form action="" method="post" class="formulario">
                    <input type="hidden" name="id" value="<?= isset($marca) ? $marca->getCodigoMarca() : ''; ?>">
                    <div class="campos-linha">
                        <div class="campo">
                            <label for="codigoFabricante">Fabricante</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-building"></i>
                                <select name="codigoFabricante" id="codigoFabricante" required>
                                    <option value="">Selecione o fabricante</option>
                                    <?php foreach ($fabricantes as $fabricante) { ?>
                                        <option
                                            value="<?= $fabricante['codigoFabricante']; ?>"
                                            <?= isset($marca) &&
                                                $marca->getCodigoFabricante() == $fabricante['codigoFabricante']
                                                ? 'selected'
                                                : ''; ?>>
                                            <?= $fabricante['fabricante']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="campo">
                            <label for="marca">Marca</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-tag"></i>
                                <input type="text" id="marca" name="nome" placeholder="Ex: Samsung" value="<?= isset($marca) ? $marca->getNome() : ''; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="botoes">
                        <button type="reset" class="btn-cancelar"> <i class="fa-solid fa-eraser"></i> Limpar </button>
                        <?php if (isset($marca)) { ?>
                            <button type="submit" name="actualizar" class="btn-guardar"> <i class="fa-solid fa-rotate"></i> Actualizar </button>
                        <?php } else { ?>
                            <button type="submit" name="salvar" class="btn-guardar"> <i class="fa-solid fa-plus"></i> Adicionar marca </button>
                        <?php } ?>
                    </div>
                </form>
            </section>


            <section>
                <div class="cards">
                    <?php
                    if (count($marcas) > 0) {
                        foreach ($marcas as $marca) {
                            echo "
                        <div class='card'>
                        <div class='card-top'>
                        <span class='codigo'>{$marca->getCodigoMarca()}</span>
                        </div>
                        <div class='card-info'>
                        <h3>{$marca->getNome()}</h3>
                        <p class='fabricante'> <i class='fa-solid fa-building'></i> {$marca->getFabricante()} </p>
                        </div>
                        <div class='acoes'>
                        <form method='get'>
                        <input type='hidden' name='id' value='{$marca->getCodigoMarca()}'>
                        <button type='submit' name='editar' class='btn-editar'> <i class='fa-solid fa-pen'></i> Editar </button>
                        </form>
                        <form method='post'>
                        <input type='hidden' name='id' value='{$marca->getCodigoMarca()}'>
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