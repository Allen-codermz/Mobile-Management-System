<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once __DIR__ . '/../../auth/log.php';
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../auth/permissoes.php';
include_once __DIR__ . '/../../model/celular.php';
include_once __DIR__ . '/../../controller/ControllerCelular.php';
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

$celularController = new ControllerCelular($conexao);
$q = trim($_GET['q'] ?? '');
$celulares = ($q !== '') ? $celularController->pesquisar($q) : $celularController->listar();
$fabricantes = $celularController->listarFabricantes();
$marcas = $celularController->listarMarcas();
$modelos = $celularController->listarModelos();
$cores = $celularController->listarCores();




if (isset($_POST['salvar'])) {
    $preco = $_POST['preco'];
    $anoDeFabrico = $_POST['anoDeFabrico'];
    $codigoMarca = $_POST['codigoMarca'];
    $codigoFabricante = $_POST['codigoFabricante'];
    $codigoCor = $_POST['descricao'];
    $codigoModelo = $_POST['codigoModelo'];
    $celular = new celular(null, $preco, $anoDeFabrico, $codigoMarca, $codigoFabricante, $codigoCor, $codigoModelo);
    $result = $celularController->criar($celular);
    if ($result) {
        header("Location: cadastroDeCelular.php");
        exit;
    } else {
        echo "
        <div class='mensagem-erro'>
            Celular não registado!
        </div>";
    }
    $celulares = $celularController->listar();
}

//DELETE FOR REAL
if (isset($_POST['apagar'])) {
    $id = $_POST['id'];
    if ($celularController->remover($id)) {
        header("Location: cadastroDeCelular.php");
        exit;
    } else {
        echo "Celular não foi removida";
    }
}


$celularSelecionado = null;

if (isset($_GET['apagar']) && isset($_GET['id'])) {

    $id = $_GET['id'];

    foreach ($celulares as $celular) {

        if ($celular->getNumeroDeSerie() == $id) {
            $celularSelecionado = $celular;
            break;
        }
    }
}

$celularEditar = null;
if (isset($_GET['editar']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $celularEditar = $celularController->encontrarId($id);
}

if (isset($_POST['guardarEdicao'])) {

    $id = $_POST['id'];
    $preco = $_POST['preco'];
    $anoDeFabrico = $_POST['anoDeFabrico'];
    $codigoMarca = $_POST['codigoMarca'];
    $codigoFabricante = $_POST['codigoFabricante'];
    $codigoCor = $_POST['codigoCor'];
    $codigoModelo = $_POST['codigoModelo'];

    $celular = new celular(
        $id,
        $preco,
        $anoDeFabrico,
        $codigoMarca,
        $codigoFabricante,
        $codigoCor,
        $codigoModelo
    );

    $resultado = $celularController->editar($celular);

    if ($resultado) {
        header("Location: cadastroDeCelular.php");
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
    <link rel='stylesheet' href='../css/cadastroDeCelular.css'>
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

                <a href="#" class="menu-item active">
                    <i class="fa-solid fa-mobile-screen"></i>
                    <span>Celulares</span>
                </a>

                <a href="../pages/cadastroDeContinente.php" class="menu-item">
                    <i class="fa-solid fa-earth-africa"></i>
                    <span>Continentes</span>
                </a>

                <a href="../pages/cadastroDePais.php" class="menu-item">
                    <i class="fa-solid fa-globe"></i>
                    <span>Países</span>
                </a>

                <a href="../pages/cadastroDeFabricante.php" class="menu-item">
                    <i class="fa-solid fa-building"></i>
                    <span>Fabricantes</span>
                </a>

                <a href="../pages/CadastroDeMarca.php" class="menu-item">
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
                    <h1>Celulares</h1>
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
                            <label for="codigoFabricante"> Fabricante </label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-building"></i>
                                <select name="codigoFabricante" id="codigoFabricante" required>
                                    <option value=""> Selecione o fabricante </option>
                                    <?php foreach ($fabricantes as $fabricante) { ?>
                                        <option
                                            value="<?= $fabricante['codigoFabricante']; ?>"
                                            <?= isset($celular) &&
                                                $celular->getCodigoFabricante() == $fabricante['codigoFabricante']
                                                ? 'selected'
                                                : ''; ?>>
                                            <?= $fabricante['fabricante']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="campo">
                            <label for="codigoMarca"> Marca </label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-tag"></i>
                                <select name="codigoMarca" id="codigoMarca" required>
                                    <option value=""> Selecione a marca </option>
                                    <?php foreach ($marcas as $marca) { ?>
                                        <option
                                            value="<?= $marca['codigoMarca']; ?>"
                                            <?= isset($celular) && $celular->getCodigoMarca() == $marca['codigoMarca'] ? 'selected' : ''; ?>>
                                            <?= $marca['marca']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="campos-linha">
                        <div class="campo">
                            <label for="codigoModelo"> Modelo </label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-box"></i>
                                <select name="codigoModelo" id="codigoModelo" required>
                                    <option value=""> Selecione o modelo </option>
                                    <?php foreach ($modelos as $modelo) { ?>
                                        <option
                                            value="<?= $modelo['codigoModelo']; ?>"
                                            <?= isset($celular) && $celular->getCodigoModelo() == $modelo['codigoModelo'] ? 'selected' : ''; ?>>
                                            <?= $modelo['modelo']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="campo">
                            <label for="descricao"> Cor </label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-palette"></i>
                                <select name="descricao" id="descricao" required>
                                    <option value=""> Selecione a cor </option>
                                    <?php foreach ($cores as $cor) { ?>
                                        <option
                                            value="<?= $cor['codigoCor']; ?>"
                                            <?= isset($celular) && $celular->getDescricaoCor() == $cor['codigoCor'] ? 'selected' : ''; ?>>
                                            <?= $cor['descricao']; ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="campos-linha">
                        <div class="campo">
                            <label for="preco"> Preço </label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-money-bill"></i>
                                <input type="number" name="preco" id="preco" step="0.01" min="0" placeholder="Ex: 25000.00" value="<?= isset($celular)             ? $celular->getPreco()             : ''; ?>" required>
                            </div>
                        </div>
                        <div class="campo">
                            <label for="anoDeFabrico"> Ano de fabrico </label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-calendar"></i>
                                <input type="number" name="anoDeFabrico" id="anoDeFabrico" min="1900" max="<?= date('Y'); ?>" placeholder="Ex: 2025" value="<?= isset($celular)             ? $celular->getAnoDeFabrico()             : ''; ?>" required>
                            </div>
                        </div>
                    </div>
                    <div class="botoes">
                        <button type="reset" class="btn-cancelar"> <i class="fa-solid fa-eraser"></i> Limpar </button>
                        <button type="submit" name="salvar" class="btn-guardar"> <i class="fa-solid fa-plus"></i> Adicionar celular </button>
                    </div>
                </form>
            </section>


            <section>
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
                <div class="tabela-container">
                    <table class="tabela-fabricantes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fabricante</th>
                                <th>Marca</th>
                                <th>Modelo</th>
                                <th>Cor</th>
                                <th>Preco</th>
                                <th>Tempo de existência</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($celulares) > 0) {
                                foreach ($celulares as $celular) {
                                    $botaoEditar = "";
                                    $botaoApagar = "";
                                    if (podeEditar()) {
                                        $botaoEditar = "
                                    <form method='get'>
                                    <input type='hidden' name='id' value='{$celular->getNumeroDeSerie()}'>
                                    <button type='submit' name='editar' class='btn-editar'> <i class='fa-solid fa-pen'></i> Editar </button>
                                    </form>";
                                    }
                                    if (podeApagar()) {
                                        $botaoApagar = "
                                    <form method='get'>
                                    <input type='hidden' name='id' value='{$celular->getNumeroDeSerie()}'>
                                    <button type='submit' name='apagar' class='btn-apagar'> <i class='fa-solid fa-trash'></i> Apagar </button>
                                    </form>";
                                    }

                                    $anoAtual = date("Y");
                                    $tempoExistencia = $anoAtual - $celular->getAnoDeFabrico();
                                    $textoExistencia = ($tempoExistencia == 1) ? "1 ano" : $tempoExistencia . " anos";
                                    echo "
                    <tr>
                        <td>
                            <span class='codigo'>
                                #{$celular->getNumeroDeSerie()}
                            </span>
                        </td>
                        <td>
                            <span class='fabricante'>
                                <i class='fa-solid fa-building'></i>
                                {$celular->getFabricante()}
                            </span>
                        </td>
                        <td>
                            <span class='marca'>
                                <i class='fa-solid fa-tag'></i>
                                {$celular->getMarca()}
                            </span>
                        </td>
                        <td>
                            <span class='modelo'>
                                <i class='fa-solid fa-box'></i>
                                {$celular->getModelo()}
                            </span>
                        </td>
                        <td>
                            <span class='cor'>
                                <i class='fa-solid fa-palette'></i>
                                {$celular->getCor()}
                            </span>
                        </td>
                        <td>
                            <span class='preco'>
                                <i class='fa-solid fa-money-bill'></i>
                                {$celular->getPreco()}
                            </span>
                        </td>
                        
                        <td>
                            <span class='existencia'>
                                <i class='fa-solid fa-calendar'></i>
                                {$textoExistencia}
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
                    <td colspan='9' class='sem-registos'>
                        <img src='../images/file-searching-animate.svg' alt='' width=400px>
                    </td>
                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <?php if ($celularSelecionado !== null): ?>
                <?php
                $anoAtual = date("Y");
                $tempoExistencia = $anoAtual - $celularSelecionado->getAnoDeFabrico();
                $textoExistencia = ($tempoExistencia == 1) ? "1 ano" : $tempoExistencia . " anos"; ?>
                <div class="modal-overlay">
                    <div class="modal">
                        <div class="modal-header">
                            <div>
                                <h2>Apagar celular</h2>
                                <p>Confirme os dados antes de continuar.</p>
                            </div>
                            <a href="cadastroDeCelular.php" class="modal-fechar"> <i class="fa-solid fa-xmark"></i> </a>
                        </div>
                        <div class="modal-conteudo">
                            <div class="campo-modal">
                                <label>ID</label>
                                <input type="text" value="#<?= $celularSelecionado->getNumeroDeSerie() ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Fabricante</label>
                                <input type="text" value="<?= htmlspecialchars($celularSelecionado->getFabricante()) ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Marca</label>
                                <input type="text" value="<?= htmlspecialchars($celularSelecionado->getMarca()) ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Modelo</label>
                                <input type="text" value="<?= htmlspecialchars($celularSelecionado->getModelo()) ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Cor</label>
                                <input type="text" value="<?= htmlspecialchars($celularSelecionado->getCor()) ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Preco</label>
                                <input type="text" value="<?= htmlspecialchars($celularSelecionado->getPreco()) ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Tempo de existência</label>
                                <input type="text" value="<?= htmlspecialchars($textoExistencia) ?>" disabled>
                            </div>
                        </div>
                        <div class="modal-aviso">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>Tem a certeza de que pretende eliminar este celular? </span>
                        </div>
                        <div class="modal-acoes">
                            <a href="cadastroDeCelular.php" class="btn-cancelarr"> Cancelar </a>
                            <form method="post">
                                <input type="hidden" name="id" value="<?= $celularSelecionado->getNumeroDeSerie() ?>">
                                <button type="submit" name="apagar" class="btn-confirmar-apagar"> <i class="fa-solid fa-trash"></i> Confirmar eliminação </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>


            <?php if ($celularEditar !== null): ?>
                <div class="modal-overlay">
                    <div class="modal">
                        <div class="modal-header">
                            <div>
                                <h2>Editar celular</h2>
                                <p>Confirme os dados antes de continuar.</p>
                            </div>
                            <a href="cadastroDeCelular.php" class="modal-fechar"> <i class="fa-solid fa-xmark"></i> </a>
                        </div>
                        <form method="post">
                            <input type="hidden" name="id" value="<?= $celularEditar->getNumeroDeSerie() ?>">
                            <div class="campo-modal">
                                <label>ID</label>
                                <input type="text" value="<?= $celularEditar->getNumeroDeSerie() ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Fabricante</label>
                                <select name="codigoFabricante" required>
                                    <?php foreach ($fabricantes as $fabricante): ?>
                                        <option
                                            value="<?= $fabricante['codigoFabricante'] ?>"
                                            <?= $fabricante['codigoFabricante'] == $celularEditar->getCodigoFabricante() ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($fabricante['fabricante']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="campo-modal">
                                <label>Marca</label>
                                <select name="codigoMarca" required>
                                    <?php foreach ($marcas as $marca): ?>
                                        <option
                                            value="<?= $marca['codigoMarca'] ?>"
                                            <?= $marca['codigoMarca'] == $celularEditar->getCodigoMarca() ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($marca['marca']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="campo-modal">
                                <label>Modelo</label>
                                <select name="codigoModelo" required>
                                    <?php foreach ($modelos as $modelo): ?>
                                        <option
                                            value="<?= $modelo['codigoModelo'] ?>"
                                            <?= $modelo['codigoModelo'] == $celularEditar->getCodigoModelo() ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($modelo['modelo']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="campo-modal">
                                <label>Cor</label>
                                <select name="codigoCor" required>
                                    <?php foreach ($cores as $cor): ?>
                                        <option
                                            value="<?= $cor['codigoCor'] ?>"
                                            <?= $cor['descricao'] == $celularEditar->getCodigoCor() ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cor['Cor']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="campo-modal">
                                <label>Preco</label>
                                <input type="text" name="preco" value="<?= htmlspecialchars($celularEditar->getPreco()) ?>" required>
                            </div>
                            <div class="campo-modal">
                                <label>Ano de Fabrico</label>
                                <input type="text" name="anoDeFabrico" value="<?= htmlspecialchars($celularEditar->getAnoDeFabrico()) ?>" required>
                            </div>
                            <div class="modal-aviso">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>
                                    Tem a certeza de que pretende guardar
                                    as alterações desta celular?
                                </span>
                            </div>
                            <div class="modal-acoes">
                                <a href="cadastroDeCelular.php" class="btn-cancelarr"> Cancelar </a>
                                <button type="submit" name="guardarEdicao" class="btn-confirmar-apagar"> Guardar alterações </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </main>
</body>

</html>