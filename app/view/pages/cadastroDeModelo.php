<?php
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
            header("Location: cadastroDeFabricante.php");
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

//UPDATE FOR REAL

if (isset($_POST['actualizar'])) {
    if (isset($_POST["nome"]) && isset($_POST["codigoMarca"])) {
        $id = $_POST['id'];
        $nome = $_POST['nome'];
        $codigoMarca = $_POST['codigoMarca'];
        $modelo = new modelo($id, $nome, $codigoMarca, null);
        $result = $modeloController->editar($modelo);
        if ($result) {
            header("Location: cadastroDeFabricante.php");
            exit;
        } else {
            echo "<div class='mensagem-erro'>
                Marca não actualizda!
            </div>";
        }
        $modelos = $modeloController->listar();
    }
}


//DELETE FOR REAL
if (isset($_POST['apagar'])) {
    $id = $_POST['id'];
    if ($modeloController->remover($id)) {
        echo "Modelo removido";
        $modelos = $modeloController->listar();
    } else {
        echo "Modelo não foi removida";
    }
}

//
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $modelo = $modeloController->encontrarId($id);
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
                <a href="#" class="menu-item">
                    <i class="fa-solid fa-house"></i>
                    <p>Dashboard</p>
                </a>

                <a href="#" class="menu-item">
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

                <a href="../pages/cadastroDaCor.php" class="menu-item active">
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
                <div class="cards">
                    <?php
                    if (count($modelos) > 0) {
                        foreach ($modelos as $modelo) {
                            echo "
                        <div class='card'>
                        <div class='card-top'>
                        <span class='codigo'>{$modelo->getCodigoModelo()}</span>
                        </div>
                        <div class='card-info'>
                        <h3>{$modelo->getNome()}</h3>
                        <p class='marca'> <i class='fa-solid fa-tag'></i> {$modelo->getMarca()} </p>
                        </div>
                        <div class='acoes'>
                        <form method='get'>
                        <input type='hidden' name='id' value='{$modelo->getCodigoModelo()}'>
                        <button type='submit' name='editar' class='btn-editar'> <i class='fa-solid fa-pen'></i> Editar </button>
                        </form>
                        <form method='post'>
                        <input type='hidden' name='id' value='{$modelo->getCodigoModelo()}'>
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