<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


require_once __DIR__ . '/../../auth/log.php';
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../auth/permissoes.php';
include_once __DIR__ . '/../../model/cor.php';
include_once __DIR__ . '/../../controller/ControllerCor.php';
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

$corController = new ControllerCor($conexao);
$q = trim($_GET['q'] ?? '');
$cores = ($q !== '') ? $corController->pesquisar($q) : $corController->listar();

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
            registrarLog("CRIAR", "Criou a cor: " . $corHex);
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

if (isset($_GET['apagar'], $_GET['id'])) {
    $id = (int) $_GET['id'];

    if (podeApagar()) {
        $corSelecionada = $corController->encontrarId($id);
    }
}
//Modal para editar
$corEditar = null;
$corEscolhidaModal = null;

if (isset($_GET['editar'], $_GET['id'])) {
    $id = (int) $_GET['id'];
    $corEditar = $corController->encontrarId($id);

    if ($corEditar !== null) {
        // cor escolhida no select (se ainda não escolheu, usa a própria cor)
        $idRef = (int) ($_GET['corRef'] ?? $id);
        $corEscolhidaModal = $corController->encontrarId($idRef) ?? $corEditar;
    }
}

// todas as cores registadas, para o select do modal
$todasCores = $corEditar !== null ? $corController->listar() : [];
//guardar a edicao
if (isset($_POST['guardarEdicao'])) {
    $id = $_POST['id'];
    $corHex = $_POST['corHex'];
    $descricao = $_POST['descricao'];
    $cor = new cor($id, $corHex, $descricao);
    $resultado = $corController->editar($cor);
    if ($resultado) {
        registrarLog("EDITAR", "Editou  cor: " . $corHex);

        header("Location: cadastroDaCor.php");
        exit;
    } else {
        echo "Cor não foi actualizada";
    }
}

if (isset($_POST['apagar'])) {

    if (!podeApagar()) {
        http_response_code(403);
        exit('Não tens permissão para apagar cores.');
    }

    $id = (int) ($_POST['id'] ?? 0);
    $corApagar = $corController->encontrarId($id);

    if ($corApagar !== null) {
        $hexCorApagar = $corApagar->getCorHex();

        if ($corController->remover($id)) {
            registrarLog("APAGAR", "Apagou a cor: " . $hexCorApagar);
            header("Location: cadastroDaCor.php");
            exit;
        } else {
            echo "A cor não foi removida.";
        }
    } else {
        echo "Cor não encontrada.";
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

                <a href="#" class="menu-item active">
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
                        <i class="fa-solid fa-sliders"></i>
                        <span>Logs do Sistema</span>
                    </a>
                <?php endif; ?>

                <a href="logout.php" class="menu-item-logout">
                    <i class="fa-solid fa-sign-out-alt"></i>
                    <span>Log Out</span>
                </a>

                <!-- <a href="../pages/alterarSenha.php" class="menu-item">
                    <i class="fa-solid fa-key"></i>
                    <span>Alterar senha</span>
                </a> -->
            </nav>
        </aside>

        <main class="main">
            <header class="page-header">
                <div>
                    <h1>Cores</h1>
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

                        <a href="cadastroDaCor.php" class="btn-cancelar"> <i class="fa-solid fa-eraser"></i> Limpar </a>
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
                                <th>Cor(Hexadecimal)</th>
                                <th>descricao</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (count($cores) > 0) {
                                foreach ($cores as $cor) {
                                    $botaoEditar = "";
                                    $botaoApagar = "";
                                    if (podeEditar()) {
                                        $botaoEditar = "
                                    <form method='get'>
                                    <input type='hidden' name='id' value='{$cor->getCodigoCor()}'>
                                    <button type='submit' name='editar' class='btn-editar'> <i class='fa-solid fa-pen'></i> Editar </button>
                                    </form>";
                                    }
                                    if (podeApagar()) {
                                        $botaoApagar = "
                                    <form method='get'>
                                    <input type='hidden' name='id' value='{$cor->getCodigoCor()}'>
                                    <button type='submit' name='apagar' class='btn-apagar'> <i class='fa-solid fa-trash'></i> Apagar </button>
                                    </form>";
                                    }
                                    echo "
                    <tr>
                        <td>
                            <span class='codigo'>
                                # {$cor->getCodigoCor()}
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
                                $botaoEditar
                                $botaoApagar
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
                                <label for="selectCor">Cor</label>
                                <div class="select-cor">
                                    <span class="preview-cor"
                                        style="background-color: <?= htmlspecialchars($corEscolhidaModal->getCorHex()) ?>"></span>

                                    <select id="selectCor" name="corRef">
                                        <?php foreach ($todasCores as $c): ?>
                                            <option value="<?= $c->getCodigoCor() ?>"
                                                <?= $c->getCodigoCor() == $corEscolhidaModal->getCodigoCor() ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($c->getCorHex() . ' — ' . $c->getDescricao()) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <!-- formmethod="get" recarrega o modal com a cor escolhida, sem JS -->
                                    <button type="submit" name="editar" value="1" formmethod="get"
                                        formaction="cadastroDaCor.php" class="btn-aplicar">
                                        <i class="fa-solid fa-check"></i> Aplicar
                                    </button>
                                </div>
                            </div>

                            <div class="campo-modal">
                                <label for="editHex">Código HEX</label>
                                <input type="text" id="editHex" name="corHex"
                                    value="<?= htmlspecialchars($corEscolhidaModal->getCorHex()) ?>" readonly required>
                            </div>

                            <div class="campo-modal">
                                <label for="editDescricao">Descrição</label>
                                <input type="text" id="editDescricao" name="descricao"
                                    value="<?= htmlspecialchars($corEscolhidaModal->getDescricao()) ?>" readonly required>
                            </div>

                            <div class="modal-aviso">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                                <span>Tem a certeza de que pretende guardar as alterações desta cor?</span>
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