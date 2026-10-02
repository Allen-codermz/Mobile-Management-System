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
        $fabricante = new fabricante(null, $nome, $codigoPais, null, null, null);
        $result = $fabricanteController->criar($fabricante);
        if ($result) {
            header("Location: cadastroDeFabricante.php");
            exit;
        } else {
            echo "fabricante não registrado";
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

$fabricanteSelecionado = null;
if (isset($_GET['apagar']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    foreach ($fabricantes as $fabricante) {
        if ($fabricante->getCodigoFabricante() == $id) {
            $fabricanteSelecionado = $fabricante;
            break;
        }
    }
}

$fabricanteEditar = null;
if (isset($_GET['editar']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $fabricanteEditar = $fabricanteController->encontraId($id);
}

if (isset($_POST['guardarEdicao'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $codigoPais = $_POST['codigoPais'];
    $fabricante = new Fabricante($id, $nome, $codigoPais, null, null, null);
    $resultado = $fabricanteController->editar($fabricante);
    if ($resultado) {
        header("Location: cadastroDeFabricante.php");
        exit;
    }
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
                <a href="../pages/dashboard.php" class="menu-item">
                    <i class="fa-solid fa-house"></i>
                    <p>Dashboard</p>
                </a>

                <a href="../pages/cadastroDeCelular.php" class="menu-item">
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

                <a href="../pages/login.php" class="menu-item-logout">
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
                <div class="tabela-container">
                    <table class="tabela-fabricantes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fabricante</th>
                                <th>País</th>
                                <th>Continente</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($fabricantes) > 0) {
                                foreach ($fabricantes as $fabricante) {
                                    echo "
                    <tr>
                        <td>
                            <span class='codigo'>
                                #{$fabricante->getCodigoFabricante()}
                            </span>
                        </td>
                        <td class='nome-fabricante'>
                            {$fabricante->getNome()}
                        </td>
                        <td>
                            <span class='localizacao'>
                                <i class='fa-solid fa-location-dot'></i>
                                {$fabricante->getPais()}
                            </span>
                        </td>
                        <td>
                            <span class='localizacao'>
                                <i class='fa-solid fa-earth-africa'></i>
                                {$fabricante->getContinente()}
                            </span>
                        </td>
                        <td>
                            <div class='acoes'>
                                <form method='get'>
                                    <input type='hidden'  name='id'  value='{$fabricante->getCodigoFabricante()}' >
                                    <button type='submit'  name='editar'  class='btn-editar' > <i class='fa-solid fa-pen'></i> Editar </button>
                                </form>
                                <form method='get'>
                                    <input type='hidden' name='id' value='{$fabricante->getCodigoFabricante()}' >
                                    <button  type='submit' name='apagar' class='btn-apagar' > <i class='fa-solid fa-trash'></i> Apagar </button>
                                </form>
                            </div>
                        </td>
                    </tr>";
                                }
                            } else {
                                echo "
                <tr>
                    <td colspan='5' class='sem-registos'>
                        <img src='../images/file-searching-animate.svg' alt='' width=400px>
                    </td>
                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <?php if ($fabricanteSelecionado !== null): ?>
                <div class="modal-overlay">
                    <div class="modal">
                        <div class="modal-header">
                            <div>
                                <h2>Apagar fabricante</h2>
                                <p>Confirme os dados antes de continuar.</p>
                            </div>
                            <a href="cadastroDeFabricante.php" class="modal-fechar"> <i class="fa-solid fa-xmark"></i> </a>
                        </div>
                        <div class="modal-conteudo">
                            <div class="campo-modal">
                                <label>ID</label>
                                <input type="text" value="#<?= $fabricanteSelecionado->getCodigoFabricante() ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Fabricante</label>
                                <input type="text" value="<?= htmlspecialchars($fabricanteSelecionado->getNome()) ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>País</label>
                                <input type="text" value="<?= htmlspecialchars($fabricanteSelecionado->getPais()) ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Continente</label>
                                <input type="text" value="<?= htmlspecialchars($fabricanteSelecionado->getContinente()) ?>" disabled>
                            </div>
                        </div>
                        <div class="modal-aviso">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>
                                Tem a certeza de que pretende eliminar
                                este fabricante?
                            </span>
                        </div>
                        <div class="modal-acoes">
                            <a href="cadastroDeFabricante.php" class="btn-cancelarr"> Cancelar </a>
                            <form method="post">
                                <input type="hidden" name="id" value="<?= $fabricanteSelecionado->getCodigoFabricante() ?>">
                                <button type="submit" name="apagar" class="btn-confirmar-apagar"> <i class="fa-solid fa-trash"></i> Confirmar eliminação </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>


            <?php if ($fabricanteEditar !== null): ?>
                <div class="modal-overlay">
                    <div class="modal">
                        <div class="modal-header">
                            <div>
                                <h2>Editar fabricante</h2>
                                <p>Confirme os dados antes de continuar.</p>
                            </div>
                            <a href="cadastroDeFabricante.php" class="modal-fechar"> <i class="fa-solid fa-xmark"></i> </a>
                        </div>
                        <form method="post">
                            <input type="hidden" name="id" value="<?= $fabricanteEditar->getCodigoFabricante() ?>">
                            <div class="campo-modal">
                                <label>ID</label>
                                <input type="text" value="<?= $fabricanteEditar->getCodigoFabricante() ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Fabricante</label>
                                <input type="text" name="nome" value="<?= htmlspecialchars($fabricanteEditar->getNome()) ?>" required>
                            </div>
                            <div class="campo-modal">
                                <label>País</label>
                                <select name="codigoPais" required>
                                    <?php foreach ($paises as $pais): ?>
                                        <option
                                            value="<?= $pais['codigoPais'] ?>"
                                            <?= $pais['codigoPais'] == $fabricanteEditar->getCodigoPais() ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($pais['pais']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="campo-modal">
                                <label>Continente</label>
                                <input type="text" value="<?= htmlspecialchars($fabricanteEditar->getContinente()) ?>" disabled>
                            </div>
                            <div class="modal-aviso">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>
                                    Tem a certeza de que pretende editar
                                    este fabricante?
                                </span>
                            </div>
                            <div class="modal-acoes">
                                <a href="cadastroDeFabricante.php" class="btn-cancelarr"> Cancelar </a>
                                <button type="submit" name="guardarEdicao" class="btn-confirmar-apagar"> Guardar alterações </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </main>
</body>

</html>