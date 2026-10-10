<?php

include_once __DIR__ . '/../model/pais.php';

class ControllerPais
{
    private $conexao;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    // READ: listar países com os respetivos continentes
    public function listar()
    {
        $paises = [];

        $sql = "SELECT
                    p.codigoPais,
                    p.pais,
                    p.codigoContinente,
                    c.continente
                FROM Pais p
                INNER JOIN Continente c
                    ON p.codigoContinente = c.codigoContinente
                ORDER BY p.codigoPais ASC";

        $resultado = mysqli_query($this->conexao, $sql);

        if ($resultado) {
            while ($rs = mysqli_fetch_assoc($resultado)) {
                $paises[] = new ModelPais(
                    $rs['codigoPais'],
                    $rs['pais'],
                    $rs['codigoContinente'],
                    $rs['continente']
                );
            }
        }

        return $paises;
    }

    // READ: listar continentes para o formulário
    public function listarContinentes()
    {
        $continentes = [];

        $sql = "SELECT codigoContinente, continente
                FROM Continente
                ORDER BY continente ASC";

        $resultado = mysqli_query($this->conexao, $sql);

        if ($resultado) {
            while ($rs = mysqli_fetch_assoc($resultado)) {
                $continentes[] = $rs;
            }
        }

        return $continentes;
    }

    // READ: encontrar um país pelo ID
    public function encontraId($id)
    {
        $id = (int) $id;

        $sql = "SELECT
                    p.codigoPais,
                    p.pais,
                    p.codigoContinente,
                    c.continente
                FROM Pais p
                INNER JOIN Continente c
                    ON p.codigoContinente = c.codigoContinente
                WHERE p.codigoPais = $id";

        $resultado = mysqli_query($this->conexao, $sql);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $rs = mysqli_fetch_assoc($resultado);

            return new ModelPais(
                $rs['codigoPais'],
                $rs['pais'],
                $rs['codigoContinente'],
                $rs['continente']
            );
        }

        return null;
    }

    // Verificar se o país já existe no continente
    private function existePais($nome, $codigoContinente, $idExcluir = 0)
    {
        $nomeSQL = mysqli_real_escape_string(
            $this->conexao,
            trim($nome)
        );

        $codigoContinente = (int) $codigoContinente;
        $idExcluir = (int) $idExcluir;

        $sql = "SELECT codigoPais
                FROM Pais
                WHERE pais = '$nomeSQL'
                AND codigoContinente = $codigoContinente
                AND codigoPais <> $idExcluir";

        $resultado = mysqli_query($this->conexao, $sql);

        if (!$resultado) {
            return true;
        }

        return mysqli_num_rows($resultado) > 0;
    }

    // CREATE
    public function criar($pais)
    {
        $nome = trim($pais->getnome());
        $codigoContinente = (int) $pais->getcodigoContinente();

        if ($nome === '' || $codigoContinente <= 0) {
            return false;
        }

        if ($this->existePais($nome, $codigoContinente)) {
            return false;
        }

        $nomeSQL = mysqli_real_escape_string(
            $this->conexao,
            $nome
        );

        $sql = "INSERT INTO Pais (pais, codigoContinente)
                VALUES ('$nomeSQL', $codigoContinente)";

        return mysqli_query($this->conexao, $sql);
    }

    // UPDATE
    public function editar($pais)
    {
        $id = (int) $pais->getcodigoPais();
        $nome = trim($pais->getnome());
        $codigoContinente = (int) $pais->getcodigoContinente();

        if ($id <= 0 || $nome === '' || $codigoContinente <= 0) {
            return false;
        }

        if ($this->existePais($nome, $codigoContinente, $id)) {
            return false;
        }

        $nomeSQL = mysqli_real_escape_string(
            $this->conexao,
            $nome
        );

        $sql = "UPDATE Pais
                SET pais = '$nomeSQL',
                    codigoContinente = $codigoContinente
                WHERE codigoPais = $id";

        return mysqli_query($this->conexao, $sql);
    }

    // DELETE
    public function remover($id)
    {
        $id = (int) $id;

        if ($id <= 0) {
            return false;
        }

        $sql = "DELETE FROM Pais
                WHERE codigoPais = $id";

        return mysqli_query($this->conexao, $sql);
    }
}