<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../auth/auth.php';
require_once __DIR__ . '/../../auth/permissoes.php';

require_once __DIR__ . '/../../config/conexao.php';
require_once __DIR__ . '/../../model/usuario.php';
require_once __DIR__ . '/../../controller/ControllerUsuario.php';

$controllerUsuario = new ControllerUsuario($conexao);


exigirGestaoUsuarios();

$mensagem = '';
$tipoMensagem = '';

if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['resetarSenha'])
) {
    $codigoUsuario = filter_input(INPUT_POST, 'codigoUsuario', FILTER_VALIDATE_INT);

    if (!$codigoUsuario || $codigoUsuario <= 0) {
        $mensagem = "Utilizador inválido.";
        $tipoMensagem = "erro";
    } else {
        $resultado = $controllerUsuario->resetarSenha(
            $codigoUsuario
        );

        if ($resultado) {
            $mensagem = "Senha reposta para a senha padrão com sucesso.";
            $tipoMensagem = "sucesso";
        } else {
            $mensagem = "Não foi possível repor a senha.";
            $tipoMensagem = "erro";
        }
    }
}

$usuarios = $controllerUsuario->listar();

$usuarioSelecionado = null;
$usuarioEditar = null;

// Carregar utilizador para o modal Apagar
if (isset($_GET['apagar'], $_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($id && $id > 0) {
        $usuarioSelecionado = $controllerUsuario->encontrarId($id);
    }
}

// Carregar utilizador para o modal Editar
if (isset($_GET['editar'], $_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($id && $id > 0) {
        $usuarioEditar = $controllerUsuario->encontrarId($id);
    }
}

// Confirmar eliminação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmarApagar'])) {
    $id = filter_input(INPUT_POST, 'codigoUsuario', FILTER_VALIDATE_INT);

    if ($id && $id > 0) {
        if ($controllerUsuario->remover($id)) {
            header('Location: painelADM.php?apagado=1');
            exit;
        }

        $erro = 'Não foi possível eliminar o utilizador.';
    }
}

// Guardar edição
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['guardarEdicao'])) {
    $id = filter_input(INPUT_POST, 'codigoUsuario', FILTER_VALIDATE_INT);

    $nome = trim($_POST['nome'] ?? '');
    $apelido = trim($_POST['apelido'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contacto = trim($_POST['contacto'] ?? '');
    $bilhete = trim($_POST['bilhete'] ?? '');
    $genero = $_POST['genero'] ?? '';
    $estadoCivil = filter_input(INPUT_POST, 'estadoCivil', FILTER_VALIDATE_INT);
    $codigoPerfil = filter_input(INPUT_POST, 'codigoPerfil', FILTER_VALIDATE_INT);

    if (
        $id && $id > 0 &&
        $nome !== '' && $apelido !== '' &&
        $username !== '' && $email !== '' &&
        $estadoCivil && $codigoPerfil
    ) {
        // Não alterar a senha através deste formulário.
        $usuarioAtual = $controllerUsuario->encontrarId($id);

        if ($usuarioAtual) {
            $usuario = new usuario(
                $id,
                $nome,
                $apelido,
                $username,
                $email,
                $estadoCivil,
                $usuarioAtual->getNomePerfil(),
                $usuarioAtual->getNomeEstadoCivil(),
                $genero,
                $bilhete,
                $contacto,
                '',
                $codigoPerfil,
                $usuarioAtual->getPrimeiroAcesso()
            );

            if ($controllerUsuario->editar($usuario)) {
                header('Location: painelADM.php?editado=1');
                exit;
            }

            $erro = 'Não foi possível guardar as alterações.';
        } else {
            $erro = 'O utilizador selecionado não foi encontrado.';
        }
    } else {
        $erro = 'Preenche corretamente os campos obrigatórios.';
    }
}


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

?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Page Title</title>
    <link rel='stylesheet' href='../css/painelADM.css'>
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

                <a href="../pages/cadastroDeModelo.php" class="menu-item">
                    <i class="fa-solid fa-box"></i>
                    <p>Modelos</p>
                </a>

                <a href="../pages/cadastroDaCor.php" class="menu-item">
                    <i class="fa-solid fa-palette"></i>
                    <p>Cores</p>
                </a>

                <?php if (podeGerirUsuarios()): ?>
                    <a href="../pages/cadastroUsuario.php" class="menu-item active">
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
                    <h1>Administração</h1>
                    <p>
                        Gestão de usuarios
                    </p>
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
            <section class="logs-container">
                <?php if ($mensagem !== ''): ?>
                    <p class="mensagem <?= htmlspecialchars($tipoMensagem) ?>"> <?= htmlspecialchars($mensagem) ?> </p>
                <?php endif; ?>

                <div class="logs-header">
                    <div>
                        <h2> Usuarios </h2>
                        <p>
                            <?= count($usuarios) ?> usuarios encontrados
                        </p>
                    </div>
                    <div class="botao">
                        <a href="../pages/cadastroUsuario.php" class="btn-guardar"> <i class="fa-solid fa-plus"></i> Adicionar Usuario </a>
                    </div>
                </div>

                <div class="tabela-container">
                    <table class="tabela-logs">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Utilizador</th>
                                <th>Perfil</th>
                                <th>Email</th>
                                <th>Contacto</th>
                                <th>Genero</th>
                                <th>Estado civil</th>
                                <th>Bilhete De Identidade</th>
                                <th>Acões</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($usuarios) > 0): ?>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr>
                                        <td>
                                            <span class="codigo">
                                                #<?= $usuario->getCodigoUsuario() ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="utilizador">
                                                <div class="avatar">
                                                    <i class="fa-solid fa-user"></i>
                                                </div>
                                                <div>
                                                    <strong>
                                                        <?= htmlspecialchars(
                                                            $usuario->getNome() . ' ' .
                                                                $usuario->getApelido()
                                                        ) ?>
                                                    </strong>
                                                    <span>
                                                        @<?= htmlspecialchars(
                                                                $usuario->getUsername()
                                                            ) ?>
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars(
                                                $usuario->getNomePerfil()
                                            ) ?>
                                        </td>

                                        <td> <?= htmlspecialchars(
                                                    $usuario->getEmail()
                                                ) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($usuario->getContacto()) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($usuario->getGenero()) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($usuario->getNomeEstadoCivil()) ?>
                                        </td>
                                        <td>
                                            <?= htmlspecialchars($usuario->getBilhete()) ?>
                                        </td>
                                        <td>
                                            <div class="acoes">
                                                <form method="GET" action="painelADM.php">
                                                    <input type="hidden" name="id" value="<?= (int) $usuario->getCodigoUsuario() ?>">
                                                    <button type="submit" name="editar" value="1" class="btn-editar"> <i class="fa-solid fa-pen"></i> Editar </button>
                                                </form>

                                                <form method="GET" action="painelADM.php">
                                                    <input type="hidden" name="id" value="<?= (int) $usuario->getCodigoUsuario() ?>">
                                                    <button type="submit" name="apagar" value="1" class="btn-apagar"> <i class="fa-solid fa-trash"></i> Apagar</button>
                                                </form>

                                                <form method="POST" action="painelADM.php">
                                                    <input type="hidden" name="codigoUsuario" value="<?= (int) $usuario->getCodigoUsuario() ?>">
                                                    <button type="submit" name="resetarSenha" value="1" class="btn-editar"> <i class="fa-solid fa-arrows-rotate"></i> Reset </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan='5' class='sem-registos'>
                                        <img src='../images/file-searching-animate.svg' alt='' width=400px>
                                    </td>
                                </tr>"
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <?php if ($usuarioSelecionado !== null): ?>
                <div class="modal-overlay">
                    <div class="modal">
                        <div class="modal-header">
                            <div>
                                <h2>Apagar utilizador</h2>
                                <p>Confirme os dados antes de continuar.</p>
                            </div>
                            <a href="painelADM.php" class="modal-fechar"> <i class="fa-solid fa-xmark"></i> </a>
                        </div>

                        <div class="campo-modal">
                            <label>ID</label>
                            <input type="text" value="#<?= (int) $usuarioSelecionado->getCodigoUsuario() ?>" disabled>
                        </div>

                        <div class="campo-modal">
                            <label>Nome completo</label>
                            <input type="text" value="<?= htmlspecialchars($usuarioSelecionado->getNome() . ' ' . $usuarioSelecionado->getApelido(), ENT_QUOTES, 'UTF-8') ?>" disabled>
                        </div>

                        <div class="campo-modal">
                            <label>Username</label>
                            <input type="text" value="<?= htmlspecialchars($usuarioSelecionado->getUsername(), ENT_QUOTES, 'UTF-8') ?>" disabled>
                        </div>

                        <div class="modal-aviso">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span> Tem a certeza de que pretende eliminar este utilizador? Esta operação pode ser irreversível. </span>
                        </div>

                        <div class="modal-acoes">
                            <a href="painelADM.php" class="btn-cancelarr"> Cancelar </a>

                            <form method="post">
                                <input type="hidden" name="codigoUsuario" value="<?= (int) $usuarioSelecionado->getCodigoUsuario() ?>">
                                <button type="submit" name="confirmarApagar" value="1" class="btn-confirmar-apagar"> <i class="fa-solid fa-trash"></i> Confirmar eliminação </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($usuarioEditar !== null): ?>
                <div class="modal-overlay">
                    <div class="modal">
                        <div class="modal-header">
                            <div>
                                <h2>Editar utilizador</h2>
                                <p>Actualize os dados do utilizador.</p>
                            </div>
                            <a href="painelADM.php" class="modal-fechar"> <i class="fa-solid fa-xmark"></i> </a>
                        </div>

                        <form method="post">
                            <input type="hidden" name="codigoUsuario" value="<?= (int) $usuarioEditar->getCodigoUsuario() ?>">

                            <div class="campo-modal">
                                <label>ID</label>
                                <input type="text" value="#<?= (int) $usuarioEditar->getCodigoUsuario() ?>" disabled>
                            </div>

                            <div class="campo-modal">
                                <label>Nome</label>
                                <input type="text" name="nome" value="<?= htmlspecialchars($usuarioEditar->getNome(), ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>

                            <div class="campo-modal">
                                <label>Apelido</label>
                                <input type="text" name="apelido" value="<?= htmlspecialchars($usuarioEditar->getApelido(), ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>

                            <div class="campo-modal">
                                <label>Username</label>
                                <input type="text" name="username" value="<?= htmlspecialchars($usuarioEditar->getUsername(), ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>

                            <div class="campo-modal">
                                <label>Email</label>
                                <input type="email" name="email" value="<?= htmlspecialchars($usuarioEditar->getEmail(), ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>

                            <div class="campo-modal">
                                <label>Contacto</label>
                                <input type="text" name="contacto" value="<?= htmlspecialchars($usuarioEditar->getContacto() ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>

                            <div class="campo-modal">
                                <label>Bilhete de Identidade</label>
                                <input type="text" name="bilhete" value="<?= htmlspecialchars($usuarioEditar->getBilhete() ?? '', ENT_QUOTES, 'UTF-8') ?>">
                            </div>

                            <div class="modal-aviso">
                                <i class="fa-solid fa-circle-info"></i>
                                <span>
                                    Confirme os dados antes de guardar as alterações.
                                </span>
                            </div>

                            <div class="modal-acoes">
                                <a href="painelADM.php" class="btn-cancelarr"> Cancelar </a>
                                <button type="submit" name="guardarEdicao" value="1" class="btn-confirmar-apagar"> <i class="fa-solid fa-floppy-disk"></i> Guardar alterações </button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>
</body>

</html>