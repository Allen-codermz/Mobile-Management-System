<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../auth/log.php';
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../auth/permissoes.php';

require_once __DIR__ . '/../../model/pais.php';
require_once __DIR__ . '/../../controller/ControllerPais.php';
require_once __DIR__ . '/../../config/conexao.php';


$nomeUsuario = $_SESSION['nome'];
$apelidoUsuario = $_SESSION['apelido'];
$codigoPerfil = (int) $_SESSION['codigoPerfil'];

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


$paisController = new ControllerPais($conexao);

$mensagem = '';
$tipoMensagem = '';


// CREATE
if (isset($_POST['salvar'])) {

    if (podeAdicionar()) {

        $nome = trim($_POST['pais'] ?? '');
        $codigoContinente = (int) ($_POST['codigoContinente'] ?? 0);

        if ($nome !== '' && $codigoContinente > 0) {

            $pais = new ModelPais(
                null,
                $nome,
                $codigoContinente
            );

            if ($paisController->criar($pais)) {

                registrarLog(
                    "CRIAR",
                    "Criou o país: " . $nome
                );

                header("Location: cadastroDePais.php?sucesso=criado");
                exit;
            } else {
                $mensagem = "Não foi possível adicionar o país. Verifique se já existe nesse continente.";
                $tipoMensagem = "erro";
            }
        } else {
            $mensagem = "Preencha o nome do país e selecione um continente.";
            $tipoMensagem = "erro";
        }
    }
}


// UPDATE
if (isset($_POST['guardarEdicao'])) {

    if (podeEditar()) {

        $id = (int) ($_POST['id'] ?? 0);
        $nome = trim($_POST['pais'] ?? '');
        $codigoContinente = (int) ($_POST['codigoContinente'] ?? 0);

        if ($id > 0 && $nome !== '' && $codigoContinente > 0) {

            $pais = new ModelPais(
                $id,
                $nome,
                $codigoContinente
            );

            if ($paisController->editar($pais)) {

                registrarLog(
                    "EDITAR",
                    "Editou o país: " . $nome
                );

                header("Location: cadastroDePais.php?sucesso=editado");
                exit;
            } else {
                $mensagem = "Não foi possível editar. Verifique se o país já existe nesse continente.";
                $tipoMensagem = "erro";
            }
        } else {
            $mensagem = "Preencha todos os campos.";
            $tipoMensagem = "erro";
        }
    }
}


// DELETE
if (isset($_POST['apagar'])) {

    if (podeApagar()) {

        $id = (int) ($_POST['id'] ?? 0);
        $paisApagar = $paisController->encontraId($id);

        if ($paisApagar !== null) {

            if ($paisController->remover($id)) {

                registrarLog(
                    "APAGAR",
                    "Apagou o país: " . $paisApagar->getnome()
                );

                header("Location: cadastroDePais.php?sucesso=apagado");
                exit;
            } else {
                $mensagem = "Não foi possível apagar este país. Pode estar associado a um fabricante.";
                $tipoMensagem = "erro";
            }
        } else {
            $mensagem = "O país selecionado não foi encontrado.";
            $tipoMensagem = "erro";
        }
    }
}


// Mensagens após redirecionamento
if (isset($_GET['sucesso'])) {

    switch ($_GET['sucesso']) {
        case 'criado':
            $mensagem = "País adicionado com sucesso!";
            break;
        case 'editado':
            $mensagem = "País actualizado com sucesso!";
            break;
        case 'apagado':
            $mensagem = "País apagado com sucesso!";
            break;
    }

    $tipoMensagem = 'sucesso';
}


// Dados para os formulários
$continentes = $paisController->listarContinentes();
$paises = $paisController->listar();


// Pesquisa em PHP, sem SQL LIKE
$pesquisa = trim($_GET['q'] ?? '');
$resultados = [];

foreach ($paises as $itemPais) {

    if (
        $pesquisa === '' ||
        stripos($itemPais->getnome(), $pesquisa) !== false ||
        stripos($itemPais->getNomeContinente(), $pesquisa) !== false ||
        stripos((string) $itemPais->getcodigoPais(), $pesquisa) !== false
    ) {
        $resultados[] = $itemPais;
    }
}


// País selecionado para edição
$paisEditar = null;

if (isset($_GET['editar'], $_GET['id']) && podeEditar()) {
    $paisEditar = $paisController->encontraId(
        (int) $_GET['id']
    );
}


// País selecionado para eliminação
$paisSelecionado = null;

if (isset($_GET['apagar'], $_GET['id']) && podeApagar()) {
    $paisSelecionado = $paisController->encontraId(
        (int) $_GET['id']
    );
}

?>


<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Países | Gestão de Celulares</title>

    <link rel="stylesheet" href="../css/cadastroDeCor.css">

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
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

                <a href="../pages/cadastroDePais.php" class="menu-item active">
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
                    <h1>Países</h1>
                </div>

                <div class="user-profile">

                    <div class="user-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>

                    <div>
                        <strong>
                            <?= htmlspecialchars($nomeUsuario . ' ' . $apelidoUsuario) ?>
                        </strong>

                        <span>
                            <?= htmlspecialchars($nomePerfil) ?>
                        </span>
                    </div>

                </div>

            </header>


            <?php if ($mensagem !== ''): ?>
                <div class="mensagem <?= htmlspecialchars($tipoMensagem) ?>">
                    <?= htmlspecialchars($mensagem) ?>
                </div>
            <?php endif; ?>


            <!-- FORMULÁRIO DE CRIAÇÃO -->
            <section class="formulario-card">

                <form action="cadastroDePais.php" method="post" class="formulario">

                    <div class="campos-linha">


                        <div class="campo">

                            <label for="codigoContinente">Continente</label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-earth-africa"></i>

                                <select
                                    name="codigoContinente"
                                    id="codigoContinente"
                                    required>
                                    <option value="">Selecione o continente</option>

                                    <?php foreach ($continentes as $continente): ?>
                                        <option value="<?= (int) $continente['codigoContinente'] ?>">
                                            <?= htmlspecialchars($continente['continente']) ?>
                                        </option>
                                    <?php endforeach; ?>

                                </select>
                            </div>

                        </div>


                        <div class="campo">

                            <label for="pais">Nome do país</label>

                            <div class="input-wrapper">
                                <i class="fa-solid fa-location-dot"></i>

                                <input
                                    type="text"
                                    id="pais"
                                    name="pais"
                                    placeholder="Ex: Moçambique"
                                    maxlength="100"
                                    required>
                            </div>

                        </div>



                    </div>


                    <div class="botoes">

                        <a href="cadastroDePais.php" class="btn-cancelar">
                            <i class="fa-solid fa-eraser"></i>
                            Limpar
                        </a>

                        <?php if (podeAdicionar()): ?>
                            <button type="submit" name="salvar" class="btn-guardar">
                                <i class="fa-solid fa-plus"></i>
                                Adicionar país
                            </button>
                        <?php endif; ?>

                    </div>

                </form>

            </section>


            <!-- PESQUISA E LISTAGEM -->
            <section>

                <div class="tabela-container">

                    <form method="get" class="pesquisa-form">

                        <div class="pesquisa-campo">
                            <i class="fa-solid fa-magnifying-glass"></i>

                            <input
                                type="text"
                                name="q"
                                placeholder="Pesquisar país ou continente..."
                                autocomplete="off"
                                value="<?= htmlspecialchars($pesquisa) ?>">
                        </div>

                        <button type="submit" class="btn-pesquisar">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            Pesquisar
                        </button>

                        <?php if ($pesquisa !== ''): ?>
                            <a href="cadastroDePais.php" class="btn-limpar-pesquisa">
                                <i class="fa-solid fa-xmark"></i>
                                Limpar
                            </a>
                        <?php endif; ?>

                    </form>


                    <table class="tabela-fabricantes">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>País</th>
                                <th>Continente</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (count($resultados) > 0): ?>

                                <?php foreach ($resultados as $itemPais): ?>

                                    <tr>

                                        <td>
                                            <span class="codigo">
                                                #<?= (int) $itemPais->getcodigoPais() ?>
                                            </span>
                                        </td>

                                        <td class="nome-fabricante">
                                            <?= htmlspecialchars($itemPais->getnome()) ?>
                                        </td>

                                        <td>
                                            <span class="localizacao">
                                                <i class="fa-solid fa-earth-africa"></i>
                                                <?= htmlspecialchars($itemPais->getNomeContinente()) ?>
                                            </span>
                                        </td>

                                        <td>
                                            <div class="acoes">

                                                <?php if (podeEditar()): ?>
                                                    <form method="get">
                                                        <input
                                                            type="hidden"
                                                            name="id"
                                                            value="<?= (int) $itemPais->getcodigoPais() ?>">

                                                        <button
                                                            type="submit"
                                                            name="editar"
                                                            value="1"
                                                            class="btn-editar">
                                                            <i class="fa-solid fa-pen"></i>
                                                            Editar
                                                        </button>
                                                    </form>
                                                <?php endif; ?>


                                                <?php if (podeApagar()): ?>
                                                    <form method="get">
                                                        <input
                                                            type="hidden"
                                                            name="id"
                                                            value="<?= (int) $itemPais->getcodigoPais() ?>">

                                                        <button
                                                            type="submit"
                                                            name="apagar"
                                                            value="1"
                                                            class="btn-apagar">
                                                            <i class="fa-solid fa-trash"></i>
                                                            Apagar
                                                        </button>
                                                    </form>
                                                <?php endif; ?>

                                            </div>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="4" class="sem-registos">
                                        Nenhum país encontrado.
                                    </td>
                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </section>


            <!-- MODAL DE EDIÇÃO -->
            <?php if ($paisEditar !== null): ?>

                <div class="modal-overlay">

                    <div class="modal">

                        <div class="modal-header">
                            <div>
                                <h2>Editar país</h2>
                                <p>Altere o nome ou o continente associado.</p>
                            </div>

                            <a href="cadastroDePais.php" class="modal-fechar"
                                aria-label="Fechar">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        </div>


                        <form method="post" action="cadastroDePais.php">

                            <input
                                type="hidden"
                                name="id"
                                value="<?= (int) $paisEditar->getcodigoPais() ?>">

                            <div class="campo-modal">
                                <label for="paisEditar">Nome do país</label>

                                <input
                                    type="text"
                                    id="paisEditar"
                                    name="pais"
                                    value="<?= htmlspecialchars($paisEditar->getnome()) ?>"
                                    maxlength="100"
                                    required>
                            </div>


                            <div class="campo-modal">
                                <label for="continenteEditar">Continente</label>

                                <select
                                    id="continenteEditar"
                                    name="codigoContinente"
                                    required>
                                    <?php foreach ($continentes as $continente): ?>
                                        <option
                                            value="<?= (int) $continente['codigoContinente'] ?>"
                                            <?= (int) $continente['codigoContinente'] ===
                                                (int) $paisEditar->getcodigoContinente()
                                                ? 'selected'
                                                : '' ?>>
                                            <?= htmlspecialchars($continente['continente']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>


                            <div class="modal-acoes">

                                <a href="cadastroDePais.php" class="btn-cancelarr">
                                    Cancelar
                                </a>

                                <button
                                    type="submit"
                                    name="guardarEdicao"
                                    class="btn-confirmar-apagar">
                                    <i class="fa-solid fa-floppy-disk"></i>
                                    Guardar alterações
                                </button>

                            </div>

                        </form>

                    </div>

                </div>

            <?php endif; ?>


            <!-- MODAL DE ELIMINAÇÃO -->
            <?php if ($paisSelecionado !== null): ?>

                <div class="modal-overlay">

                    <div class="modal">

                        <div class="modal-header">
                            <div>
                                <h2>Apagar país</h2>
                                <p>Confirme os dados antes de continuar.</p>
                            </div>

                            <a href="cadastroDePais.php" class="modal-fechar"
                                aria-label="Fechar">
                                <i class="fa-solid fa-xmark"></i>
                            </a>
                        </div>


                        <div class="modal-conteudo">

                            <div class="campo-modal">
                                <label>ID</label>
                                <input
                                    type="text"
                                    value="#<?= (int) $paisSelecionado->getcodigoPais() ?>"
                                    disabled>
                            </div>

                            <div class="campo-modal">
                                <label>País</label>
                                <input
                                    type="text"
                                    value="<?= htmlspecialchars($paisSelecionado->getnome()) ?>"
                                    disabled>
                            </div>

                            <div class="campo-modal">
                                <label>Continente</label>
                                <input
                                    type="text"
                                    value="<?= htmlspecialchars($paisSelecionado->getNomeContinente()) ?>"
                                    disabled>
                            </div>

                        </div>


                        <div class="modal-aviso">
                            <i class="fa-solid fa-triangle-exclamation"></i>

                            <span>
                                Tem a certeza de que pretende eliminar este país?
                                A operação poderá falhar se existirem fabricantes associados.
                            </span>
                        </div>


                        <div class="modal-acoes">

                            <a href="cadastroDePais.php" class="btn-cancelarr">
                                Cancelar
                            </a>

                            <form method="post" action="cadastroDePais.php">

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= (int) $paisSelecionado->getcodigoPais() ?>">

                                <button
                                    type="submit"
                                    name="apagar"
                                    class="btn-confirmar-apagar">
                                    <i class="fa-solid fa-trash"></i>
                                    Confirmar eliminação
                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            <?php endif; ?>

        </main>

    </div>

</body>

</html>