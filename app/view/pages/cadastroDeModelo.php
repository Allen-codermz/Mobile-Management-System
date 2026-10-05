<?php

require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../auth/permissoes.php';


error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ . '/../../model/modelo.php';
include_once __DIR__ . '/../../controller/ControllerModelo.php';
include_once __DIR__ . '/../../config/conexao.php';


$modeloController = new ControllerModelo($conexao);
$modelos = $modeloController->listar();
$marcas = $modeloController->listarMarcas();


if (isset($_POST['salvar'])) {
    if (isset($_POST["nome"]) && isset($_POST["codigoMarca"])) {
        $nome = $_POST["nome"];
        $codigoMarca = $_POST["codigoMarca"];
        $modelo = new modelo(null, $nome, $codigoMarca, null);
        $result = $modeloController->criar($modelo);
        if ($result) {
            header("Location: cadastroDeModelo.php");
            exit;
        } else {
            echo "
            <div class='mensagem-erro'>
                Marca não registada!
            </div>";
        }
        $modelos = $modeloController->listar();
    }
}

//DELETE FOR REAL
if (isset($_POST['apagar'])) {
    $id = $_POST['id'];
    if ($modeloController->remover($id)) {
        header("Location: cadastroDeModelo.php");
        exit;
    } else {
        echo "Modelo não foi removida";
    }
}

//
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $modelo = $modeloController->encontrarId($id);
}

$modeloSelecionado = null;
if (isset($_GET['apagar']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    foreach ($modelos as $modelo) {
        if ($modelo->getCodigoModelo() == $id) {
            $modeloSelecionado = $modelo;
            break;
        }
    }
}

$modeloEditar = null;
if (isset($_GET['editar']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $modeloEditar = $modeloController->encontrarId($id);
}

if (isset($_POST['guardarEdicao'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $codigoModelo = $_POST['codigoModelo'];
    $modelo = new modelo($id, $nome, $codigoModelo, null);
    $resultado = $modeloController->editar($modelo);
    if ($resultado) {
        header("Location: cadastroDeModelo.php");
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
    <link rel='stylesheet' href='../css/cadastroDeModelo.css'>
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

                <a href="../pages/CadastroDeMarca.php" class="menu-item ">
                    <i class="fa-solid fa-tag"></i>
                    <p>Marcas</p>
                </a>

                <a href="../pages/cadastroDeModelo.php" class="menu-item active">
                    <i class="fa-solid fa-box"></i>
                    <p>Modelos</p>
                </a>

                <a href="../pages/cadastroDaCor.php" class="menu-item">
                    <i class="fa-solid fa-palette"></i>
                    <p>Cores</p>
                </a>

                <a href="../pages/cadastroUsuario.php" class="menu-item">
                    <i class="fa-solid fa-users"></i>
                    <p>Administração</p>
                </a>

                <a href="logout.php" class="menu-item-logout">
                    <i class="fa-solid fa-sign-out-alt"></i>
                    <p>Log Out</p>
                </a>
            </nav>
        </aside>

        <main class="main">
            <header class="page-header">
                <div>
                    <h1>Modelos</h1>
                </div>
            </header>

            <section class="formulario-card">
                <form action="" method="post" class="formulario">
                    <input type="hidden" name="id" value="<?= isset($modelo) ? $modelo->getCodigoModelo() : ''; ?>">
                    <div class="campos-linha">
                        <div class="campo">
                            <label for="codigoMarca">Marca</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-tag"></i>
                                <select name="codigoMarca" id="codigoMarca" required>
                                    <option value="">Selecione a marca</option>
                                    <?php foreach ($marcas as $marca) { ?>
                                        <option
                                            value="<?= $marca['codigoMarca']; ?>"
                                            <?= isset($modelo) &&
                                                $modelo->getCodigoMarca() == $marca['codigoMarca']
                                                ? 'selected'
                                                : ''; ?>>
                                            <?= $marca['marca']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="campo">
                            <label for="modelo">Modelo</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-box"></i>
                                <input type="text" id="modelo" name="nome" placeholder="Ex: S25 Ultra" value="<?= isset($modelo) ? $modelo->getNome() : ''; ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="botoes">
                        <button type="reset" class="btn-cancelar"> <i class="fa-solid fa-eraser"></i> Limpar </button>
                        <?php if (isset($modelo)) { ?>
                            <button type="submit" name="actualizar" class="btn-guardar"> <i class="fa-solid fa-rotate"></i> Actualizar </button>
                        <?php } else { ?>
                            <button type="submit" name="salvar" class="btn-guardar"> <i class="fa-solid fa-plus"></i> Adicionar modelo </button>
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
                                <th>Modelo</th>
                                <th>Marca</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($modelos) > 0) {
                                foreach ($modelos as $modelo) {
                                    $botaoEditar = "";
                                    $botaoApagar = "";
                                    if (podeEditar()) {
                                        $botaoEditar = "
                                    <form method='get'>
                                    <input type='hidden' name='id' value='{$modelo->getCodigoModelo()}'>
                                    <button type='submit' name='editar' class='btn-editar'> <i class='fa-solid fa-pen'></i> Editar </button>
                                    </form>";
                                    }
                                    if (podeApagar()) {
                                        $botaoApagar = "
                                    <form method='get'>
                                    <input type='hidden' name='id' value='{$modelo->getCodigoModelo()}'>
                                    <button type='submit' name='apagar' class='btn-apagar'> <i class='fa-solid fa-trash'></i> Apagar </button>
                                    </form>";
                                    }
                                    echo "
                    <tr>
                        <td>
                            <span class='codigo'>
                                #{$modelo->getCodigoModelo()}
                            </span>
                        </td>
                        <td class='nome-marca'>
                            {$modelo->getNome()}
                        </td>
                        <td>
                            <span class='fabricante'>
                                <i class='fa-solid fa-tag'></i>
                                {$modelo->getMarca()}
                            </span>
                        </td>
                        <td>
                            <div class='acoes'>
                                $botaoEditar
                                $botaoApagar
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

            <?php if ($modeloSelecionado !== null): ?>
                <div class="modal-overlay">
                    <div class="modal">
                        <div class="modal-header">
                            <div>
                                <h2>Apagar marca</h2>
                                <p>Confirme os dados antes de continuar.</p>
                            </div>
                            <a href="cadastroDeModelo.php" class="modal-fechar"> <i class="fa-solid fa-xmark"></i> </a>
                        </div>
                        <div class="modal-conteudo">
                            <div class="campo-modal">
                                <label>ID</label>
                                <input type="text" value="#<?= $modeloSelecionado->getCodigoMarca() ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Marca</label>
                                <input type="text" value="<?= htmlspecialchars($modeloSelecionado->getNome()) ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Fabricante</label>
                                <input type="text" value="<?= htmlspecialchars($modeloSelecionado->getMarca()) ?>" disabled>
                            </div>
                        </div>
                        <div class="modal-aviso">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>Tem a certeza de que pretende eliminar esta marca? </span>
                        </div>
                        <div class="modal-acoes">
                            <a href="cadastroDeModelo.php" class="btn-cancelarr"> Cancelar </a>
                            <form method="post">
                                <input type="hidden" name="id" value="<?= $modeloSelecionado->getCodigoMarca() ?>">
                                <button type="submit" name="apagar" class="btn-confirmar-apagar"> <i class="fa-solid fa-trash"></i> Confirmar eliminação </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>


            <?php if ($modeloEditar !== null): ?>
                <div class="modal-overlay">
                    <div class="modal">
                        <div class="modal-header">
                            <div>
                                <h2>Editar marca</h2>
                                <p>Confirme os dados antes de continuar.</p>
                            </div>
                            <a href="cadastroDeModelo.php" class="modal-fechar"> <i class="fa-solid fa-xmark"></i> </a>
                        </div>
                        <form method="post">
                            <input type="hidden" name="id" value="<?= $modeloEditar->getCodigoModelo() ?>">
                            <div class="campo-modal">
                                <label>ID</label>
                                <input type="text" value="<?= $modeloEditar->getCodigoModelo() ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Modelo</label>
                                <input type="text" name="nome" value="<?= htmlspecialchars($modeloEditar->getNome()) ?>" required>
                            </div>
                            <div class="campo-modal">
                                <label>Marca</label>
                                <select name="codigoModelo" required>
                                    <?php foreach ($marcas as $marca): ?>
                                        <option
                                            value="<?= $marca['codigoMarca'] ?>"
                                            <?= $marca['codigoMarca'] == $modeloEditar->getCodigoMarca() ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($marca['marca']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="modal-aviso">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>
                                    Tem a certeza de que pretende editar
                                    este fabricante?
                                </span>
                            </div>
                            <div class="modal-acoes">
                                <a href="cadastroDeModelo.php" class="btn-cancelarr"> Cancelar </a>
                                <button type="submit" name="guardarEdicao" class="btn-confirmar-apagar"> Guardar alterações </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </main>
</body>

</html>