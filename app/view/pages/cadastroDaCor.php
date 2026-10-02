<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once __DIR__ . '/../../model/cor.php';
include_once __DIR__ . '/../../controller/ControllerCor.php';
include_once __DIR__ . '/../../config/conexao.php';


$corController = new ControllerCor($conexao);
$cores = $corController->listar();

$corEscolhida = "#C9A98F";
$descricaoCor = "";
$corConfirmada = false;

if (isset($_POST['confirmarCor'])) {
    if (!empty($_POST['corHex'])) {
        $corEscolhida = $_POST['corHex']; // faz o picker e o campo HEX manterem a cor
        $descricaoCor = $corController->obterDescricao($corEscolhida);
        if ($descricaoCor === false) {
            $mensagemErro = "Não foi possível obter a descrição da cor.";
            $descricaoCor = "";
        } else {
            $corConfirmada = true;
        }
    }
}


//accao: criar uma cor
if (isset($_POST['salvar'])) {
    if (isset($_POST["corHex"]) && !empty($_POST["corHex"])) {
        $corHex = $_POST["corHex"];
        $cor = new cor(null, $corHex, "");
        $result = $corController->criar($cor);
        if ($result) {
            header("Location: cadastroDaCor.php");
            exit;
        } else {
            echo "
            <div class='mensagem-erro'>
                Cor não registada!
            </div>";
        }
        $cores = $corController->listar();
    }
}

//accao: apagar uma cor
$corSelecionada = null;
if (isset($_GET['apagar']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    foreach ($cores as $cor) {
        if ($cor->getCodigoCor() == $id) {
            $corSelecionada = $cor;
            break;
        }
    }
}

//Modal para editar
$corEditar = null;
if (isset($_GET['editar']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $corEditar = $corController->encontrarId($id);
}

//guardar a edicao
if (isset($_POST['guardarEdicao'])) {
    $id = $_POST['id'];
    $corHex = $_POST['corHex'];
    $descricao = $_POST['descricao'];
    $cor = new cor($id, $corHex, $descricao);
    $resultado = $corController->editar($cor);
    if ($resultado) {
        header("Location: cadastroDaCor.php");
        exit;
    } else {
        echo "Cor não foi actualizada";
    }
}

//Modal para eliminar
if (isset($_POST['apagar'])) {
    $id = $_POST['id'];
    if ($corController->remover($id)) {
        header("Location: cadastroDaCor.php");
        exit;
    } else {
        echo "Cor não foi removida";
    }
}
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Page Title</title>
    <link rel='stylesheet' href='../css/cadastroDeCor.css'>
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

                <a href="../pages/CadastroDeMarca.php" class="menu-item">
                    <i class="fa-solid fa-tag"></i>
                    <p>Marcas</p>
                </a>

                <a href="../pages/cadastroDeModelo.php" class="menu-item">
                    <i class="fa-solid fa-box"></i>
                    <p>Modelos</p>
                </a>

                <a href="#" class="menu-item active">
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
                    <h1>Cores</h1>
                </div>
            </header>

            <section class="formulario-card">
                <form action="" method="post" class="formulario">
                    <div class="campos-linha">
                        <div class="campo">
                            <label for="corHex">
                                Selecionar cor
                            </label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-palette"></i>
                                <input type="color" name="corHex" id="corHex" value="<?= htmlspecialchars($corEscolhida) ?>" required>
                            </div>
                        </div>
                        <div class="campo">
                            <label for="codigoHex"> Código HEX </label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-hashtag"></i>
                                <input type="text" id="codigoHex" value="<?= htmlspecialchars($corEscolhida) ?>" readonly>
                            </div>
                        </div>
                        <div class="campo">
                            <label for="descricao"> Descrição da cor </label>
                            <div class="input-wrapper">
                                <i class="fa-solid fa-align-left"></i>
                                <input type="text" id="descricao" name="descricao" value="<?= htmlspecialchars($descricaoCor) ?>" placeholder="Descrição da cor" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="botoes">
                        <button type="reset" class="btn-cancelar"> <i class="fa-solid fa-eraser"></i> Limpar </button>
                        <?php if (!$corConfirmada): ?>
                            <button type="submit" name="confirmarCor" class="btn-guardar"> <i class="fa-solid fa-check"></i> Confirmar cor </button>
                        <?php else: ?>
                            <button type="submit" name="salvar" class="btn-guardar"> <i class="fa-solid fa-plus"></i> Adicionar cor </button>
                        <?php endif; ?>
                    </div>
                </form>
            </section>


            <section>
                <div class="tabela-container">
                    <table class="tabela-fabricantes">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cor(Hexadecimal)</th>
                                <th>descricao</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($cores) > 0) {
                                foreach ($cores as $cor) {
                                    echo "
                    <tr>
                        <td>
                            <span class='codigo'>
                                {$cor->getCodigoCor()}
                            </span>
                        </td>
                        <td class='nome-cor'>
                            {$cor->getCorHex()}
                        </td>
                        <td>
                            <span class='descricao'>
                                <i class='fa-solid fa-align-left'></i>
                                {$cor->getDescricao()}
                            </span>
                        </td>
                        <td>
                            <div class='acoes'>
                                <form method='get'>
                                    <input type='hidden'  name='id'  value='{$cor->getCodigoCor()}' >
                                    <button type='submit'  name='editar'  class='btn-editar' > <i class='fa-solid fa-pen'></i> Editar </button>
                                </form>
                                <form method='get'>
                                    <input type='hidden' name='id' value='{$cor->getCodigoCor()}' >
                                    <button  type='submit' name='apagar' class='btn-apagar' > <i class='fa-solid fa-trash'></i> Apagar </button>
                                </form>
                            </div>
                        </td>
                    </tr>";
                                }
                            } else {
                                echo "
                <tr>
                    <td colspan='4' class='sem-registos'>
                        <img src='../images/file-searching-animate.svg' alt='' width=400px>
                    </td>
                </tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <?php if ($corSelecionada !== null): ?>
                <div class="modal-overlay">
                    <div class="modal">
                        <div class="modal-header">
                            <div>
                                <h2>Apagar cor</h2>
                                <p>Confirme os dados antes de continuar.</p>
                            </div>
                            <a href="cadastroDaCor.php" class="modal-fechar"> <i class="fa-solid fa-xmark"></i> </a>
                        </div>
                        <div class="modal-conteudo">
                            <div class="campo-modal">
                                <label>ID</label>
                                <input type="text" value="#<?= $corSelecionada->getCodigoCor() ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Cor(Hexadecimal)</label>
                                <input type="text" value="<?= htmlspecialchars($corSelecionada->getCorHex()) ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Descricao</label>
                                <input type="text" value="<?= htmlspecialchars($corSelecionada->getDescricao()) ?>" disabled>
                            </div>
                        </div>
                        <div class="modal-aviso">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>Tem a certeza de que pretende eliminar esta cor? </span>
                        </div>
                        <div class="modal-acoes">
                            <a href="cadastroDaCor.php" class="btn-cancelarr"> Cancelar </a>
                            <form method="post">
                                <input type="hidden" name="id" value="<?= $corSelecionada->getCodigoCor() ?>">
                                <button type="submit" name="apagar" class="btn-confirmar-apagar"> <i class="fa-solid fa-trash"></i> Confirmar eliminação </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>


            <?php if ($corEditar !== null): ?>
                <div class="modal-overlay">
                    <div class="modal">
                        <div class="modal-header">
                            <div>
                                <h2>Editar cor</h2>
                                <p>Confirme os dados antes de continuar.</p>
                            </div>
                            <a href="cadastroDaCor.php" class="modal-fechar"> <i class="fa-solid fa-xmark"></i> </a>
                        </div>
                        <form method="post">
                            <input type="hidden" name="id" value="<?= $corEditar->getCodigoCor() ?>">
                            <div class="campo-modal">
                                <label>ID</label>
                                <input type="text" value="<?= $corEditar->getCodigoCor() ?>" disabled>
                            </div>
                            <div class="campo-modal">
                                <label>Cor(Hexadecimal)</label>
                                <input type="text" name="corHex" value="<?= htmlspecialchars($corEditar->getCorHex()) ?>" required>
                            </div>
                            <div class="campo-modal">
                                <label>Descrição</label>
                                <input type="text" name="descricao" value="<?= htmlspecialchars($corEditar->getDescricao()) ?>" required>
                            </div>
                            <div class="modal-aviso">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>
                                    Tem a certeza de que pretende guardar
                                    as alterações desta cor?
                                </span>
                            </div>
                            <div class="modal-acoes">
                                <a href="cadastroDaCor.php" class="btn-cancelarr"> Cancelar </a>
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