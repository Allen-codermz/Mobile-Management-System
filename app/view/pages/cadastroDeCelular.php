<?php
ini_set('display_errors', 1);

include_once __DIR__ . '/../../model/celular.php';
include_once __DIR__ . '/../../controller/ControllerCelular.php';
include_once __DIR__ . '/../../config/conexao.php';


$celularController = new ControllerCelular($conexao);
$celulares = $celularController->listar();
$fabricantes = $celularController->listarFabricantes();
$marcas = $celularController->listarMarcas();
$modelos = $celularController->listarModelos();
$cores = $celularController->listarCores();



if (isset($_POST['salvar'])) {
    $preco = $_POST['preco'];
    $anoDeFabrico = $_POST['anoDeFabrico'];
    $codigoMarca = $_POST['codigoMarca'];
    $codigoFabricante = $_POST['codigoFabricante'];
    $codigoCor = $_POST['codigoCor'];
    $codigoModelo = $_POST['codigoModelo'];
    $celular = new celular(null, $preco, $anoDeFabrico, $codigoMarca, $codigoFabricante, $codigoCor, $codigoModelo);
    $result = $celularController->criar($celular);
    if ($result) {
        header("Location: cadastroDecelular.php");
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
        if ($celular->getCodigoMarca() == $id) {
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
    $nome = $_POST['nome'];
    $codigoFabricante = $_POST['codigoFabricante'];
    $marca = new marca($id, $nome, $codigoFabricante, null);
    $resultado = $celularController->editar($marca);
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
                    <h1>Celulares</h1>
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
                            <label for="codigoCor"> Cor </label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-palette"></i>
                                <select name="codigoCor" id="codigoCor" required>
                                    <option value=""> Selecione a cor </option>
                                    <?php foreach ($cores as $cor) { ?>
                                        <option
                                            value="<?= $cor['codigoCor']; ?>"
                                            <?= isset($celular) && $celular->getCodigoCor() == $cor['codigoCor'] ? 'selected' : ''; ?>>
                                            <?= $cor['Cor']; ?>
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
                                    echo "
                    <tr>
                        <td>
                            <span class='codigo'>
                                {$celular->getNumeroDeSerie()}
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
                                {$celular->getDescricao()}
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
                                <i class='fa-solid fa-building'></i>
                                {$celular->getFabricante()}
                            </span>
                        </td>
                        <td>
                            <div class='acoes'>
                                <form method='get'>
                                    <input type='hidden'  name='id'  value='{$celular->getNumeroDeSerie()}' >
                                    <button type='submit'  name='editar'  class='btn-editar' > <i class='fa-solid fa-pen'></i> Editar </button>
                                </form>
                                <form method='get'>
                                    <input type='hidden' name='id' value='{$celular->getNumeroDeSerie()}' >
                                    <button  type='submit' name='apagar' class='btn-apagar' > <i class='fa-solid fa-trash'></i> Apagar </button>
                                </form>
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
                                <input type="text" value="#<?= $celularSelecionado->getCodigoMarca() ?>" disabled>
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
                                <input type="text" value="<?= htmlspecialchars($celularSelecionado->getDescricao()) ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Preco</label>
                                <input type="text" value="<?= htmlspecialchars($celularSelecionado->getPreco()) ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Tempo de existencia</label>
                                <input type="text" value="<?= htmlspecialchars($celularSelecionado->getTempoExistencia()) ?>" disabled>
                            </div>
                        </div>
                        <div class="modal-aviso">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>Tem a certeza de que pretende eliminar este celular? </span>
                        </div>
                        <div class="modal-acoes">
                            <a href="CadastroDeMarca.php" class="btn-cancelarr"> Cancelar </a>
                            <form method="post">
                                <input type="hidden" name="id" value="<?= $celularSelecionado->getCodigoMarca() ?>">
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
                            <a href="cadastroDeCor.php" class="modal-fechar"> <i class="fa-solid fa-xmark"></i> </a>
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
                                    <?php foreach ($modelos as $modelo): ?>
                                        <option
                                            value="<?= $cor['codigoCor'] ?>"
                                            <?= $cor['codigoCor'] == $celularEditar->getCodigoCor() ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cor['modecorlo']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="campo-modal">
                                <label>Preco</label>
                                <input type="text" name="nome" value="<?= htmlspecialchars($celularEditar->getPreco()) ?>" required>
                            </div>
                            <div class="campo-modal">
                                <label>Ano de Fabrico</label>
                                <input type="text" name="nome" value="<?= htmlspecialchars($celularEditar->getAnoDeFabrico()) ?>" required>
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
</body>

</html>