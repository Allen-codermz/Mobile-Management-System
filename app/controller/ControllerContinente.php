<?php

include_once __DIR__ . '/../model/continente.php';

class ControllerContinente
{
    private $conexao;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    // READ: listar todos os continentes
    public function listar()
    {
        $continentes = [];

        $sql = "SELECT codigoContinente, continente
                FROM Continente
                ORDER BY continente ASC";

        $resultado = mysqli_query($this->conexao, $sql);

        if ($resultado) {
            while ($rs = mysqli_fetch_assoc($resultado)) {
                $continentes[] = new ModelContinente(
                    $rs['codigoContinente'],
                    $rs['continente']
                );
            }
        }

        return $continentes;
    }

    // READ: procurar um continente pelo ID
    public function encontraId($id)
    {
        $id = (int) $id;

        $sql = "SELECT codigoContinente, continente
                FROM Continente
                WHERE codigoContinente = $id";

        $resultado = mysqli_query($this->conexao, $sql);

        if ($resultado && mysqli_num_rows($resultado) > 0) {
            $rs = mysqli_fetch_assoc($resultado);

            return new ModelContinente(
                $rs['codigoContinente'],
                $rs['continente']
            );
        }

        return null;
    }

    // CREATE: adicionar continente
    public function criar($continente)
    {
        $nome = trim($continente->getnome());
        $nomeSQL = mysqli_real_escape_string(
            $this->conexao,
            $nome
        );

        if ($nome === '') {
            return false;
        }

        // Verificar se já existe
        $sql = "SELECT codigoContinente
                FROM Continente
                WHERE continente = '$nomeSQL'";

        $resultado = mysqli_query($this->conexao, $sql);

        if (!$resultado || mysqli_num_rows($resultado) > 0) {
            return false;
        }

        $sql = "INSERT INTO Continente (continente)
                VALUES ('$nomeSQL')";

        return mysqli_query($this->conexao, $sql);
    }

    // UPDATE: editar continente
    public function editar($continente)
    {
        $id = (int) $continente->getcodigoContinente();
        $nome = trim($continente->getnome());

        if ($nome === '') {
            return false;
        }

        $nomeSQL = mysqli_real_escape_string(
            $this->conexao,
            $nome
        );

        // Evitar nomes repetidos noutros registos
        $sql = "SELECT codigoContinente
                FROM Continente
                WHERE continente = '$nomeSQL'
                AND codigoContinente <> $id";

        $resultado = mysqli_query($this->conexao, $sql);

        if (!$resultado || mysqli_num_rows($resultado) > 0) {
            return false;
        }

        $sql = "UPDATE Continente
                SET continente = '$nomeSQL'
                WHERE codigoContinente = $id";

        return mysqli_query($this->conexao, $sql);
    }

    // DELETE: apagar continente
    public function remover($id)
    {
        $id = (int) $id;

        $sql = "DELETE FROM Continente
                WHERE codigoContinente = $id";

        return mysqli_query($this->conexao, $sql);
    }
}