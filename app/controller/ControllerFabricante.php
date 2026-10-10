<?php
include_once __DIR__ . '/../model/fabricante.php';
include_once __DIR__ . '/../config/conexao.php';

class ControllerFabricante
{
    private $conexao;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }


    public function listarContinentes()
    {
        $continentes = array();
        $sql = "SELECT codigoContinente, continente FROM Continente";
        $result = mysqli_query($this->conexao, $sql);
        if ($result) {
            while ($rs = mysqli_fetch_assoc($result)) {
                $continentes[] = $rs;
            }
        }
        return $continentes;
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


    //READ
    function listar()
    {
        $fabricantes = array();
        $sql = "SELECT f.codigoFabricante, f.fabricante, p.codigoPais, p.pais, c.codigoContinente, c.continente
            FROM fabricante f
            INNER JOIN Pais p 
            ON f.codigoPais = p.codigoPais
            INNER JOIN Continente c
            ON p.codigoContinente = c.codigoContinente";
        $result = mysqli_query($this->conexao, $sql);
        if ($result) {
            while ($rs = mysqli_fetch_assoc($result)) {
                $id = $rs["codigoFabricante"];
                $nome = $rs["fabricante"];
                $codigoPais = $rs["codigoPais"];
                $pais = $rs["pais"];
                $codigoContinente = $rs["codigoContinente"];
                $continente = $rs["continente"];

                $fabricante = new fabricante($id, $nome, $codigoPais, $pais, $codigoContinente, $continente);
                array_push($fabricantes, $fabricante);
            }
        }
        return $fabricantes;
    }

    //CREATE
    function criar($fabricante)
    {
        $sql = "select * from fabricante where fabricante = '{$fabricante->getNome()}' and codigoPais='{$fabricante->getcodigoPais()}' ";
        $result = mysqli_query($this->conexao, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "";
        } else {
            $sql = "insert into fabricante values(null,'{$fabricante->getNome()}','{$fabricante->getcodigopais()}')";
            $result = mysqli_query($this->conexao, $sql);
            if ($result) {
                // header('location:index.php');
            } else {
                echo "";
            }
        }
    }

    //UPDATE
    function  editar($fabricante)
    {
        $sql = "update fabricante set fabricante = '{$fabricante->getNome()}',codigoPais = '{$fabricante->getcodigoPais()}'
            where codigoFabricante ={$fabricante->getCodigoFabricante()}";
        $result = mysqli_query($this->conexao, $sql);
        if ($result) {
            //    header('location:index.php');
        } else {
            echo "";
        }
        return $result;
    }

    //DELETE
    function remover($id)
    {
        $sql = "delete from fabricante where codigoFabricante = {$id}";
        $result = mysqli_query($this->conexao, $sql);
        // header('location:index.php');

        return $result;
    }

    function encontraId($id)
    {
        $sql = "select f.codigoFabricante, f.fabricante, f.codigoPais, p.pais, p.codigoContinente, c.continente
            from fabricante f
            inner join Pais p
            on f.codigoPais = p.codigoPais
            inner join Continente c
            on p.codigoContinente = c.codigoContinente
            where f.codigoFabricante = {$id} ";
        $result = mysqli_query($this->conexao, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $rs = mysqli_fetch_assoc($result);
            return new fabricante($rs['codigoFabricante'], $rs['fabricante'], $rs['codigoPais'], $rs['pais'], $rs['codigoContinente'],$rs['continente']);
        }
        return null;
    }

    function pesquisar($termo)
{
    $fabricantes = array();
    $t = mysqli_real_escape_string($this->conexao, $termo);

    $sql = "SELECT f.codigoFabricante, f.fabricante, p.codigoPais, p.pais, c.codigoContinente, c.continente
            FROM fabricante f
            INNER JOIN Pais p ON f.codigoPais = p.codigoPais
            INNER JOIN Continente c ON p.codigoContinente = c.codigoContinente
            WHERE f.codigoFabricante LIKE '%$t%'
                OR f.fabricante LIKE '%$t%'
                OR p.pais LIKE '%$t%'
                OR c.continente LIKE '%$t%'";
    $result = mysqli_query($this->conexao, $sql);
    if ($result) {
        while ($rs = mysqli_fetch_assoc($result)) {
            $fabricantes[] = new fabricante($rs["codigoFabricante"], $rs["fabricante"], $rs["codigoPais"], $rs["pais"], $rs["codigoContinente"], $rs["continente"]);
        }
    }
    return $fabricantes;
}
}
