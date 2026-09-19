<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once("../../model/fabricante.php");
include_once("../../Project/app/controller/ControllerFabricante.php");
$conexao = mysqli_connect("127.0.0.1", "root", "Ilovejava@123", "celular");
$fabricantes = listar($conexao);
$paises = array();
$continetes = listarContinentes($conexao);

//READ 
function listar($conexao)
{
    $fabricantes = array();
    $sql = "select  f.codigoFabricante, f.fabricante, f.codigoPais, p.pais
        from fabricante f
        inner join Pais p
        on f.codigoPais = p.codigoPais";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $id = $rs["codigoFabricante"];
            $nome = $rs["fabricante"];
            $codigoPais = $rs["codigoPais"];
            $pais = $rs["pais"];
            $fabricante = new fabricante($id, $nome, $codigoPais, $pais);
            array_push($fabricantes, $fabricante);
        }
    }
    return $fabricantes;
}

function listarContinentes($conexao)
{
    $continetes = array();
    $sql = "select codigoContinente, continente from Continente";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $continetes[] = $rs;
        }
    }
    return $continetes;
}

function listarPaises($conexao, $codigoContinente)
{
    $paises = array();
    $sql = "select codigoPais, pais from Pais where codigoContinente = {$codigoContinente}";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $paises[] = $rs;
        }
    }
    return $paises;
}

//REMOVE
function remover($id)
{
    global $conexao;
    $sql = "delete from fabricante where codigoFabricante = {$id}";
    $result = mysqli_query($conexao, $sql);
    return $result;
}
//UPDATE
function editar($id)
{
    global $conexao;
    $sql = "select f.codigoFabricante, f.fabricante, f.codigoPais, p.pais, p.codigoContinente
            from fabricante f
            inner join Pais p
            on f.codigoPais = p.codigoPais
            where f.codigoFabricante = {$id}";
    $result = mysqli_query($conexao, $sql);
    if ($result) {
        $rs = mysqli_fetch_assoc($result);
        $id = $rs["codigoFabricante"];
        $nome = $rs["fabricante"];
        $codigoPais = $rs["codigoPais"];
        $pais = $rs["pais"];


        return new fabricante($id, $nome, $codigoPais, $pais);
    }
}

//CREATE
if (isset($_POST['salvar'])) {
    if (isset($_POST["nome"]) && isset($_POST["codigoPais"])) {
        $nome = $_POST["nome"];
        $codigoPais = $_POST["codigoPais"];
        $sql = "insert into fabricante values(null,'{$nome}','{$codigoPais}')";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            echo "
            div class='mensagem-sucesso'>
                Fabricante adicionado com sucesso!
            </div>";
        } else {
            echo "fabricante não registrado";
        }
        $fabricantes = listar($conexao);
    }
}

//UPDATE FOR REAL
if (isset($_POST['actualizar'])) {
    if (isset($_POST["nome"]) && isset($_POST["codigoPais"])) {
        $id = $_POST['id'];
        $nome = $_POST["nome"];
        $codigoPais = $_POST["codigoPais"];
        $sql = "update fabricante set fabricante='{$nome}', codigoPais='{$codigoPais}' where codigoFabricante={$id}";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            echo "Fabricante actulizado!";
            unset($_GET['editar']);
            unset($_GET['id']);
        } else {
            echo "Fabricante não actualizado";
        }
        $fabricantes = listar($conexao);
    }
}

//DELETE FOR REAL
if (isset($_POST['apagar'])) {
    $id = $_POST['id'];
    if (remover($id)) {
        echo "Fabricante removido";
        $fabricantes = listar($conexao);
    } else {
        echo "Fabricante não foi removido";
    }
}

//
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $fabricante = editar($id);
}

if (isset($_GET['codigoContinente'])) {
    $codigoContinente = $_GET['codigoContinente'];
    $paises = listarPaises($conexao, $codigoContinente);
}
?>

<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <title>Page Title</title>
    <link rel='stylesheet' href='../css/cadatroDefabricante.css'>
</head>

<body>
    <header>
        <nav>

        </nav>
    </header>

    <main>
        <div class="area-formulario">

            <form method="get" class="form1">
                <label for="codigoContinente">Continente:</label>
                <select name="codigoContinente" id="codigoContinente" onchange="this.form.submit()">
                    <option value="">Selecione o Continente</option>
                    <?php foreach ($continetes as $continente) { ?>
                        <option value="<?= $continente['codigoContinente']; ?>"
                            <?= isset($_GET['codigoContinente']) && $_GET['codigoContinente'] == $continente['codigoContinente'] ? 'selected' : ''; ?>>
                            <?= $continente['continente']; ?>
                        </option>
                    <?php } ?>
                </select>
            </form>

            <form action="" method="post" class="form2">
                <label for="paisDeOrigem">Pais De Origem:</label>
                <select name="codigoPais" id="codigoPais" required>
                    <option value="">Selecione o país</option>
                    <?php foreach ($paises as $pais) { ?>
                        <option value="<?= $pais['codigoPais']; ?>"
                            <?= isset($fabricante) && $fabricante->getCodigoPais() == $pais['codigoPais'] ? 'selected' : ''; ?>>
                            <?= $pais['pais']; ?>
                        </option>
                    <?php } ?>
                </select>
                <div class="in">
                    <input type="hidden" name="id" value="<?= isset($fabricante) ? $fabricante->getCodigoFabricante() : ''; ?>">
                    <label for="fabricante">Fabricante:</label>
                    <input type="text" name="nome" value="<?= isset($fabricante) ? $fabricante->getNome() : ''; ?>" required>
                </div>
                <div class="botoes">
                    <?php if (isset($fabricante)) { ?>
                        <input type="submit" name="actualizar" value="actualizar">
                    <?php } else { ?>
                        <input type="submit" name="salvar" value="salvar">
                    <?php } ?>
                    <input type="reset" name="limpar" value="cancelar">
                </div>
            </form>
        </div>

        <div class="cards">

            <?php
            if (count($fabricantes) > 0) {
                foreach ($fabricantes as $fabricante) {
                    echo "
                    <div class= card >
                    <h2>{$fabricante->getCodigoFabricante()}</h2>
                    <h3>{$fabricante->getNome()}</h3>
                    <p>{$fabricante->getPais()}<p/>
                    <div class = accoes>
                    <form method='post'>
                    <input type='hidden' name='id' value='{$fabricante->getCodigoFabricante()}'>
                    <input type='submit' name='apagar' value='apagar' class='apagar'>
                    </form>
                    <form method='get'>
                    <input type='hidden' name='id' value='{$fabricante->getCodigoFabricante()}'>
                    <input type=submit name='editar' value='editar'>
                    </form>
                    </div>
                    </div>";
                }
            }
            ?>

        </div>


    </main>
</body>

</html>