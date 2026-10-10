<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../auth/log.php';
require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../auth/permissoes.php';

require_once __DIR__ . '/../../model/continente.php';
require_once __DIR__ . '/../../controller/ControllerContinente.php';
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


$continenteController = new ControllerContinente($conexao);

$mensagem = '';
$tipoMensagem = '';


// CREATE
if (isset($_POST['salvar'])) {

    if (podeAdicionar()) {

        $nome = trim($_POST['continente'] ?? '');

        if ($nome !== '') {

            $continente = new ModelContinente(null, $nome);

            if ($continenteController->criar($continente)) {

                registrarLog(
                    "CRIAR",
                    "Criou o continente: " . $nome
                );

                header("Location: cadastroDeContinente.php?sucesso=criado");
                exit;
            } else {
                $mensagem = "Não foi possível adicionar o continente. Verifique se já existe.";
                $tipoMensagem = "erro";
            }
        } else {
            $mensagem = "O nome do continente é obrigatório.";
            $tipoMensagem = "erro";
        }
    }
}


// UPDATE
if (isset($_POST['guardarEdicao'])) {

    if (podeEditar()) {

        $id = (int) ($_POST['id'] ?? 0);
        $nome = trim($_POST['continente'] ?? '');

        if ($id > 0 && $nome !== '') {

            $continente = new ModelContinente($id, $nome);

            if ($continenteController->editar($continente)) {

                registrarLog(
                    "EDITAR",
                    "Editou o continente: " . $nome
                );

                header("Location: cadastroDeContinente.php?sucesso=editado");
                exit;
            } else {
                $mensagem = "Não foi possível editar. O nome pode já estar registado.";
                $tipoMensagem = "erro";
            }
        } else {
            $mensagem = "Preencha o nome do continente.";
            $tipoMensagem = "erro";
        }
    }
}


// DELETE
if (isset($_POST['apagar'])) {

    if (podeApagar()) {

        $id = (int) ($_POST['id'] ?? 0);
        $continente = $continenteController->encontraId($id);

        if ($continente !== null) {

            if ($continenteController->remover($id)) {

                registrarLog(
                    "APAGAR",
                    "Apagou o continente: " . $continente->getnome()
                );

                header("Location: cadastroDeContinente.php?sucesso=apagado");
                exit;
            } else {
                $mensagem = "Não foi possível apagar. Verifique se existem países associados.";
                $tipoMensagem = "erro";
            }
        }
    }
}


// Mensagens após redirecionamento
if (isset($_GET['sucesso'])) {

    switch ($_GET['sucesso']) {
        case 'criado':
            $mensagem = "Continente adicionado com sucesso!";
            break;

        case 'editado':
            $mensagem = "Continente actualizado com sucesso!";
            break;

        case 'apagado':
            $mensagem = "Continente apagado com sucesso!";
            break;
    }

    $tipoMensagem = "sucesso";
}


// Continente selecionado para edição
$continenteEditar = null;

if (isset($_GET['editar']) && isset($_GET['id']) && podeEditar()) {
    $continenteEditar = $continenteController->encontraId(
        (int) $_GET['id']
    );
}


// Continente selecionado para eliminação
$continenteApagar = null;

if (isset($_GET['apagar']) && isset($_GET['id']) && podeApagar()) {
    $continenteApagar = $continenteController->encontraId(
        (int) $_GET['id']
    );
}


// Pesquisa feita em PHP
$continentes = $continenteController->listar();

$pesquisa = trim($_GET['q'] ?? '');

$resultados = [];

foreach ($continentes as $itemContinente) {

    if (
        $pesquisa === '' ||
        stripos($itemContinente->getnome(), $pesquisa) !== false ||
        stripos((string) $itemContinente->getcodigoContinente(), $pesquisa) !== false
    ) {
        $resultados[] = $itemContinente;
    }
}

?>


<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Continentes | Gestão de Celulares</title>

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

                <a href="../pages/cadastroDeContinente.php" class="menu-item active">
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
                    <h1>Continentes</h1>
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
                <div class="mensagem <?= $tipoMensagem ?>">
                    <?= htmlspecialchars($mensagem) ?>
                </div>
            <?php endif; ?>


            <!-- FORMULÁRIO -->
            <section class="formulario-card">

                <form method="post" class="formulario">

                    <?php if ($continenteEditar !== null): ?>

                        <input
                            type="hidden"
                            name="id"
                            value="<?= $continenteEditar->getcodigoContinente() ?>">

                    <?php endif; ?>


                    <div class="campo">

                        <label for="continente">
                            Nome do continente
                        </label>

                        <div class="input-wrapper">

                            <i class="fa-solid fa-earth-africa"></i>

                            <input
                                type="text"
                                id="continente"
                                name="continente"
                                placeholder="Ex: África"
                                maxlength="100"
                                value="<?= $continenteEditar !== null
                                            ? htmlspecialchars($continenteEditar->getnome())
                                            : '' ?>"
                                required>

                        </div>

                    </div>


                    <div class="botoes">

                        <a href="cadastroDeContinente.php" class="btn-cancelar">
                            <i class="fa-solid fa-eraser"></i>
                            Limpar
                        </a>

                        <?php if ($continenteEditar !== null): ?>

                            <button
                                type="submit"
                                name="guardarEdicao"
                                class="btn-guardar">
                                <i class="fa-solid fa-rotate"></i>
                                Actualizar
                            </button>

                        <?php elseif (podeAdicionar()): ?>

                            <button
                                type="submit"
                                name="salvar"
                                class="btn-guardar">
                                <i class="fa-solid fa-plus"></i>
                                Adicionar continente
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
                                placeholder="Pesquisar continente..."
                                autocomplete="off"
                                value="<?= htmlspecialchars($pesquisa) ?>">
                        </div>

                        <button type="submit" class="btn-pesquisar">
                            <i class="fa-solid fa-magnifying-glass"></i>
                            Pesquisar
                        </button>

                        <?php if ($pesquisa !== ''): ?>
                            <a href="cadastroDeContinente.php"
                                class="btn-limpar-pesquisa">
                                <i class="fa-solid fa-xmark"></i>
                                Limpar
                            </a>
                            
                        <?php endif; ?>

                    </form>


                    <table class="tabela-fabricantes">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Continente</th>
                                <th>Ações</th>
                            </tr>
                        </thead>

                        <tbody>

                            <?php if (count($resultados) > 0): ?>

                                <?php foreach ($resultados as $itemContinente): ?>

                                    <tr>

                                        <td>
                                            <span class="codigo">
                                                #<?= $itemContinente->getcodigoContinente() ?>
                                            </span>
                                        </td>

                                        <td class="nome-fabricante">
                                            <?= htmlspecialchars($itemContinente->getnome()) ?>
                                        </td>

                                        <td>

                                            <div class="acoes">

                                                <?php if (podeEditar()): ?>

                                                    <form method="get">
                                                        <input
                                                            type="hidden"
                                                            name="id"
                                                            value="<?= $itemContinente->getcodigoContinente() ?>">

                                                        <button
                                                            type="submit"
                                                            name="editar"
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
                                                            value="<?= $itemContinente->getcodigoContinente() ?>">

                                                        <button
                                                            type="submit"
                                                            name="apagar"
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
                                    <td colspan="3" class="sem-registos">
                                        Nenhum continente encontrado.
                                    </td>
                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </section>


            <!-- CONFIRMAÇÃO DE ELIMINAÇÃO -->
            <?php if ($continenteApagar !== null): ?>

                <div class="modal-overlay">

                    <div class="modal">

                        <div class="modal-header">

                            <div>
                                <h2>Apagar continente</h2>
                                <p>Confirme os dados antes de continuar.</p>
                            </div>

                            <a
                                href="cadastroDeContinente.php"
                                class="modal-fechar">
                                <i class="fa-solid fa-xmark"></i>
                            </a>

                        </div>


                        <div class="modal-conteudo">

                            <div class="campo-modal">
                                <label>ID</label>

                                <input
                                    type="text"
                                    value="#<?= $continenteApagar->getcodigoContinente() ?>"
                                    disabled>
                            </div>

                            <div class="campo-modal">
                                <label>Continente</label>

                                <input
                                    type="text"
                                    value="<?= htmlspecialchars($continenteApagar->getnome()) ?>"
                                    disabled>
                            </div>

                        </div>


                        <div class="modal-aviso">
                            <i class="fa-solid fa-triangle-exclamation"></i>

                            <span>
                                Tem a certeza de que pretende eliminar este continente?
                                A operação poderá falhar se existirem países associados.
                            </span>
                        </div>


                        <div class="modal-acoes">

                            <a
                                href="cadastroDeContinente.php"
                                class="btn-cancelarr">
                                Cancelar
                            </a>

                            <form method="post">

                                <input
                                    type="hidden"
                                    name="id"
                                    value="<?= $continenteApagar->getcodigoContinente() ?>">

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

            
<?php if ($continenteEditar !== null): ?>

    <div class="modal-overlay">

        <div class="modal">

            <div class="modal-header">

                <div>
                    <h2>Editar continente</h2>
                    <p>Altere o nome do continente abaixo.</p>
                </div>

                <a
                    href="cadastroDeContinente.php"
                    class="modal-fechar"
                    aria-label="Fechar"
                >
                    <i class="fa-solid fa-xmark"></i>
                </a>

            </div>

            <form method="post" class="formulario-modal">

             <div class="campo-modal">

             <label for="continenteEditar">
                        ID
                    </label>
                <input
                    type="text"
                    name="id"
                    value="<?= $continenteEditar->getcodigoContinente() ?> "
                    disabled
                >
             </div>

                <div class="campo-modal">

                    <label for="continenteEditar">
                        Nome do continente
                    </label>

                    <input
                        type="text"
                        id="continenteEditar"
                        name="continente"
                        value="<?= htmlspecialchars($continenteEditar->getnome()) ?>"
                        maxlength="100"
                        required
                    >

                </div>

                <div class="modal-acoes">

                    <a
                        href="cadastroDeContinente.php"
                        class="btn-cancelarr"
                    >
                        Cancelar
                    </a>

                    <button
                        type="submit"
                        name="guardarEdicao"
                        class="btn-guardar"
                    >
                        <i class="fa-solid fa-floppy-disk"></i>
                        Guardar alterações
                    </button>

                </div>

            </form>

        </div>

    </div>

<?php endif; ?>



        </main>

    </div>

</body>

</html>