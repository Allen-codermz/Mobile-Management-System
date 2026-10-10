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
$q = trim($_GET['q'] ?? '');
$marcas = ($q !== '') ? $marcaController->pesquisar($q) : $marcaController->listar();
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


if (isset($_POST['apagar'])) {
    if (!podeApagar()) {
        http_response_code(403);
        exit('Não tens permissão para apagar marcas.');
    }

    $id = (int) ($_POST['id'] ?? 0);

    $marcaApagar = $marcaController->encontrarId($id);

    if ($marcaApagar !== null) {
        $nomeMarca = $marcaApagar->getNome();

        if ($marcaController->remover($id)) {
            registrarLog(
                "APAGAR",
                "Apagou a marca: " . $nomeMarca
            );

            header("Location: CadastroDeMarca.php");
            exit;
        } else {
            echo "A marca não foi removida.";
        }
    } else {
        echo "Marca não encontrada.";
    }
}

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

if (isset($_GET['apagar'], $_GET['id'])) {
    $id = (int) $_GET['id'];
    $marcaSelecionada = $marcaController->encontrarId($id);
}


$marcaEditar = null;
if (isset($_GET['editar']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $marcaEditar = $marcaController->encontrarId($id);
}
if (isset($_POST['guardarEdicao'])) {
    if (!podeEditar()) {
        http_response_code(403);
        exit('Não tens permissão para editar marcas.');
    }

    $id = (int) $_POST['id'];
    $nome = trim($_POST['nome']);
    $codigoFabricante = (int) $_POST['codigoFabricante'];

    $marca = new marca(
        $id,
        $nome,
        $codigoFabricante,
        null
    );

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
                    <span>Dashboard</span>
                </a>

                <a href="../pages/cadastroDeCelular.php" class="menu-item">
                    <i class="fa-solid fa-mobile-screen"></i>
                    <span>Celulares</span>
                </a>

                <a href="../pages/cadastroDeContinente.php" class="menu-item">
                    <i class="fa-solid fa-earth-africa"></i>
                    <span>Continentes</span>
                </a>

                <a href="../pages/cadastroDePais.php" class="menu-item ">
                    <i class="fa-solid fa-globe"></i>
                    <span>Países</span>
                </a>

                <a href="../pages/cadastroDeFabricante.php" class="menu-item">
                    <i class="fa-solid fa-building"></i>
                    <span>Fabricantes</span>
                </a>

                <a href="#" class="menu-item active">
                    <i class="fa-solid fa-tag"></i>
                    <span>Marcas</span>
                </a>

                <a href="../pages/cadastroDeModelo.php" class="menu-item">
                    <i class="fa-solid fa-box"></i>
                    <span>Modelos</span>
                </a>

                <a href="../pages/cadastroDaCor.php" class="menu-item">
                    <i class="fa-solid fa-palette"></i>
                    <span>Cores</span>
                </a>

                <?php if (podeGerirUsuarios()): ?>
                    <a href="../pages/painelADM.php" class="menu-item">
                        <i class="fa-solid fa-user-gear"></i>
                        <span>Administração</span>
                    </a>
                <?php endif; ?>

                <?php if (podeVerLogs()): ?>
                    <a href="../pages/logsDoSistema.php" class="menu-item">
                        <i class="fa-solid fa-clock-rotate-left"></i>
                        <span>Logs do Sistema</span>
                    </a>
                <?php endif; ?>

                <a href="logout.php" class="menu-item-logout">
                    <i class="fa-solid fa-sign-out-alt"></i>
                    <span>Log Out</span>
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
                                    <?php foreach ($fabricantes as $fabricante): ?>
                                        <option value="<?= $fabricante['codigoFabricante'] ?>">
                                            <?= htmlspecialchars($fabricante['fabricante']) ?>
                                        </option>
                                    <?php endforeach; ?>
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
                    <form method="get" class="pesquisa-form">
                        <div class="pesquisa-campo">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            <input type="text" name="q" placeholder="Pesquisar..." autocomplete="off"
                                value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                        </div>

                        <button type="submit" class="btn-pesquisar">
                            <i class="fa-solid fa-magnifying-glass"></i> Pesquisar
                        </button>

                        <?php if (!empty($_GET['q'])): ?>
                            <a href="?" class="btn-limpar-pesquisa">
                                <i class="fa-solid fa-xmark"></i> Limpar
                            </a>
                        <?php endif; ?>
                    </form>
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
                                    <input type='hidden' name='id' value='{$marca->getCodigoMarca()}'>
                                    <button type='submit' name='editar' class='btn-editar'> <i class='fa-solid fa-pen'></i> Editar </button>
                                    </form>";
                                    }
                                    if (podeApagar()) {
                                        $botaoApagar = "
                                    <form method='get'>
                                    <input type='hidden' name='id' value='{$marca->getCodigoMarca()}'>
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