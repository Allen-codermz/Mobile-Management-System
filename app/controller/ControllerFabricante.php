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
        $sql = "select  f.codigoFabricante, f.fabricante, f.codigoPais, p.pais
        from fabricante f
        inner join Pais p
        on f.codigoPais = p.codigoPais";
        $result = mysqli_query($this->conexao, $sql);
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

    //CREATE
    function criar($fabricante)
    {
        $sql = "select * from fabricante where fabricante = '{$fabricante->getNome()}' and codigoFabricante='{$fabricante->getcodigoPais()}' ";
        $result = mysqli_query($this->conexao, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "";
        } else {
            $sql = "insert into fabricante values(null,'{$fabricante->getNome()}','{$fabricante->getcodigopais()}','{$fabricante->getPais()}')";
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
        $sql = "update fabricante set fabricante = '{$fabricante->getNome()}',codigoPais = {$fabricante->getcodigoPais()}'
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



    // function encotraId($id)
    // {
    // $sql = "select  f.codigoFabricante, m.fabricante, m.codigoP, f.fabricante
    //     from marca m
    //     inner join fabricante f
    // on m.codigofabricante = f.codigoFabricante
    // where m.codigoMarca = $id";
    // $result = mysqli_query($this->conexao, $sql);
    // if ($result && mysqli_num_rows($result) > 0) {
    //     $rs = mysqli_fetch_assoc($result);
    //     return new marca($rs['codigoMarca'], $rs['marca'], $rs['codigoFabricante'], null);
    // }
    // return null;

    function encontraId($id)
    {
        $sql = "select f.codigoFabricante, f.fabricante, f.codigoPais, p.pais, p.codigoContinente, c.continente
            from fabricante f
            inner join Pais p
            on f.codigoPais = p.codigoPais
            inner join Continente c
            on p.codigoContinente = c.codigoContinente
            where f.codigoFabricante = $id";
        $result = mysqli_query($this->conexao, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $rs = mysqli_fetch_assoc($result);
            return new fabricante($rs['codigoFabricante'], $rs['fabricante'], $rs['codigoPais'], $rs['pais'], $rs['codigoContinente']);
        }
        return null;
    }
}
