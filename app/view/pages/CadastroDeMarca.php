<?php

require_once __DIR__ . '/../../auth/log.php';
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../auth/permissoes.php';
include_once __DIR__ . '/../../model/marca.php';
include_once __DIR__ . '/../../controller/ControllerMarca.php';
include_once __DIR__ . '/../../config/conexao.php';


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
            registrarLog("CRIAR", "Criou a marca: " . $nome);
            header("Location: CadastroDeMarca.php");
            exit;
        } else {
            echo "
            <div class='mensagem-erro'>
                Marca não registada!
            </div>";
        }
        $marcas = $marcaController->listar();
    }
}

//DELETE FOR REAL
if (isset($_POST['apagar'])) {
    $id = $_POST['id'];
    if ($marcaController->remover($id)) {
        registrarLog("APAGAR", "Apagou a marca: " . $nome->getNome());
        header("Location: CadastroDeMarca.php");
        exit;
    } else {
        echo "Marca não foi removida";
    }
}


$marcaSelecionada = null;
if (isset($_GET['apagar']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    foreach ($marcas as $marca) {
        if ($marca->getCodigoMarca() == $id) {
            $marcaSelecionada = $marca;
            break;
        }
    }
}

$marcaEditar = null;
if (isset($_GET['editar']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $marcaEditar = $marcaController->encontrarId($id);
}

if (isset($_POST['guardarEdicao'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $codigoFabricante = $_POST['codigoFabricante'];
    $marca = new marca($id, $nome, $codigoFabricante, null);
    $resultado = $marcaController->editar($marca);
    if ($resultado) {
        registrarLog("EDITAR", "Editou a marca: " . $nome);
        header("Location: CadastroDeMarca.php");
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

                <a href="../pages/cadastroDeCelular.php" class="menu-item">
                    <i class="fa-solid fa-mobile-screen"></i>
                    <p>Celulares</p>
                </a>

                <a href="../pages/cadastroDeFabricante.php" class="menu-item">
                    <i class="fa-solid fa-building"></i>
                    <p>Fabricantes</p>
                </a>

                <a href="#" class="menu-item active">
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
                    <a href="../pages/painelADM.php" class="menu-item">
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
                    <h1>Marcas</h1>
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

            <section class="formulario-card">
                <form action="" method="post" class="formulario">
                    <div class="campos-linha">
                        <div class="campo">
                            <label for="codigoFabricante">Fabricante</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-building"></i>
                                <select name="codigoFabricante" id="codigoFabricante" required>
                                    <option value="">Selecione o fabricante</option>
                                    <?php foreach ($fabricantes as $fabricante) { ?>
                                        <option value="<?= $fabricante['codigoFabricante']; ?>">
                                            <?= htmlspecialchars($fabricante['fabricante']); ?>
                                        <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="campo">
                            <label for="marca">Marca</label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-tag"></i>
                                <input type="text" id="marca" name="nome" placeholder="Ex: Samsung" required>
                            </div>
                        </div>
                    </div>

                    <div class="botoes">
                        <button type="reset" class="btn-cancelar"> <i class="fa-solid fa-eraser"></i> Limpar </button>
                        <button type="submit" name="salvar" class="btn-guardar"> <i class="fa-solid fa-plus"></i> Adicionar marca </button>
                    </div>
                </form>
            </section>


            <section>
                <div class="tabela-container">
                    <table class="tabela-fabricantes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Marca</th>
                                <th>Fabricante</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($marcas) > 0) {
                                foreach ($marcas as $marca) {
                                    $botaoEditar = "";
                                    $botaoApagar = "";
                                    if (podeEditar()) {
                                        $botaoEditar = "
                                    <form method='get'>
                                    <input type='hidden' name='id' value='{$marca->getcodigoMarca()}'>
                                    <button type='submit' name='editar' class='btn-editar'> <i class='fa-solid fa-pen'></i> Editar </button>
                                    </form>";
                                    }
                                    if (podeApagar()) {
                                        $botaoApagar = "
                                    <form method='get'>
                                    <input type='hidden' name='id' value='{$marca->getcodigoMarca()}'>
                                    <button type='submit' name='apagar' class='btn-apagar'> <i class='fa-solid fa-trash'></i> Apagar </button>
                                    </form>";
                                    }
                                    echo "
                    <tr>
                        <td>
                            <span class='codigo'>
                                #{$marca->getCodigoMarca()}
                            </span>
                        </td>
                        <td class='nome-marca'>
                            {$marca->getNome()}
                        </td>
                        <td>
                            <span class='fabricante'>
                                <i class='fa-solid fa-building'></i>
                                {$marca->getFabricante()}
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

            <?php if ($marcaSelecionada !== null): ?>
                <div class="modal-overlay">
                    <div class="modal">
                        <div class="modal-header">
                            <div>
                                <h2>Apagar marca</h2>
                                <p>Confirme os dados antes de continuar.</p>
                            </div>
                            <a href="CadastroDeMarca.php" class="modal-fechar"> <i class="fa-solid fa-xmark"></i> </a>
                        </div>
                        <div class="modal-conteudo">
                            <div class="campo-modal">
                                <label>ID</label>
                                <input type="text" value="#<?= $marcaSelecionada->getCodigoMarca() ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Marca</label>
                                <input type="text" value="<?= htmlspecialchars($marcaSelecionada->getNome()) ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Fabricante</label>
                                <input type="text" value="<?= htmlspecialchars($marcaSelecionada->getFabricante()) ?>" disabled>
                            </div>
                        </div>
                        <div class="modal-aviso">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>Tem a certeza de que pretende eliminar esta marca? </span>
                        </div>
                        <div class="modal-acoes">
                            <a href="CadastroDeMarca.php" class="btn-cancelarr"> Cancelar </a>
                            <form method="post">
                                <input type="hidden" name="id" value="<?= $marcaSelecionada->getCodigoMarca() ?>">
                                <button type="submit" name="apagar" class="btn-confirmar-apagar"> <i class="fa-solid fa-trash"></i> Confirmar eliminação </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>


            <?php if ($marcaEditar !== null): ?>
                <div class="modal-overlay">
                    <div class="modal">
                        <div class="modal-header">
                            <div>
                                <h2>Editar marca</h2>
                                <p>Confirme os dados antes de continuar.</p>
                            </div>
                            <a href="CadastroDeMarca.php" class="modal-fechar"> <i class="fa-solid fa-xmark"></i> </a>
                        </div>
                        <form method="post">
                            <input type="hidden" name="id" value="<?= $marcaEditar->getCodigoMarca() ?>">
                            <div class="campo-modal">
                                <label>ID</label>
                                <input type="text" value="<?= $marcaEditar->getCodigoMarca() ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Marca</label>
                                <input type="text" name="nome" value="<?= htmlspecialchars($marcaEditar->getNome()) ?>" required>
                            </div>
                            <div class="campo-modal">
                                <label>Fabricante</label>
                                <select name="codigoFabricante" required>
                                    <?php foreach ($fabricantes as $fabricante): ?>
                                        <option
                                            value="<?= $fabricante['codigoFabricante'] ?>"
                                            <?= $fabricante['codigoFabricante'] == $marcaEditar->getCodigoFabricante() ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($fabricante['fabricante']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="modal-aviso">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>
                                    Tem a certeza de que pretende guardar
                                    as alterações desta marca?
                                </span>
                            </div>
                            <div class="modal-acoes">
                                <a href="CadastroDeMarca.php" class="btn-cancelarr"> Cancelar </a>
                                <button type="submit" name="guardarEdicao" class="btn-confirmar-apagar"> Guardar alterações </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>