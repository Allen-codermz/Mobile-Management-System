<?php

class ControllerDashboard
{
    private $conexao;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    public function contarCelulares()
    {
        $sql = "SELECT COUNT(*) AS total FROM celulares";
        $resultado = mysqli_query($this->conexao, $sql);
        if (!$resultado) {
            return 0;
        }
        $dados = mysqli_fetch_assoc($resultado);
        return $dados['total'];
    }

    public function contarFabricantes()
    {
        $sql = "SELECT COUNT(*) AS total FROM fabricante";
        $resultado = mysqli_query($this->conexao, $sql);
        if (!$resultado) {
            return 0;
        }
        $dados = mysqli_fetch_assoc($resultado);
        return $dados['total'];
    }

    public function contarMarcas()
    {
        $sql = "SELECT COUNT(*) AS total FROM marca";
        $resultado = mysqli_query($this->conexao, $sql);
        if (!$resultado) {
            return 0;
        }
        $dados = mysqli_fetch_assoc($resultado);
        return $dados['total'];
    }

    public function contarModelos()
    {
        $sql = "SELECT COUNT(*) AS total FROM modelo";
        $resultado = mysqli_query($this->conexao, $sql);
        if (!$resultado) {
            return 0;
        }
        $dados = mysqli_fetch_assoc($resultado);
        return $dados['total'];
    }

    public function contarCores()
    {
        $sql = "SELECT COUNT(*) AS total FROM cor";
        $resultado = mysqli_query($this->conexao, $sql);
        if (!$resultado) {
            return 0;
        }
        $dados = mysqli_fetch_assoc($resultado);
        return $dados['total'];
    }

    public function contarUsuarios()
    {
        $sql = "SELECT COUNT(*) AS total FROM usuario";
        $resultado = mysqli_query($this->conexao, $sql);
        if (!$resultado) {
            return 0;
        }
        $dados = mysqli_fetch_assoc($resultado);
        return $dados['total'];
    }

    public function celularesPorFabricante()
    {
        $sql = "
            SELECT  f.fabricante,
            COUNT(c.numeroDeSerie) AS total
            FROM fabricante f
            LEFT JOIN celulares c
            ON c.codigoFabricante = f.codigoFabricante
            GROUP BY f.codigoFabricante, f.fabricante
            ORDER BY total DESC
        ";

        $resultado = mysqli_query($this->conexao, $sql);
        if (!$resultado) {
            return [];
        }
        $fabricantes = [];
        while ($dados = mysqli_fetch_assoc($resultado)) {
            $fabricantes[] = $dados;
        }
        return $fabricantes;
    }
}