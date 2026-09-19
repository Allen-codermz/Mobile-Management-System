<?php
include_once("../model/fabricante.php");
include_once("../config/conexao.php");

class ControllerFabricante
{
    private $conexao;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }



    //READ
    function listar()
    {
        global $conexao;
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

    //CREATE
    function criar($fabricante)
    {
        global $conexao;
        $sql = "select * from fabricante where fabricante={$fabricante->getNome()} or codigoFabricante='{$fabricante->getcodigoPais()}' or pais='{$fabricante->getPais()}'";
        $result = mysqli_query($conexao, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "";
        } else {
            $sql = "insert into fabricante values(null,'{$fabricante->getNome()}','{$fabricante->getcodigopais()}','{$fabricante->getPais()}')";
            $result = mysqli_query($conexao, $sql);
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
        global $conexao;
        $sql = "update fabricante set nome = '{$fabricante->getNome()}',codigoPais = {$fabricante->getcodigoPais()}, pais = '{$fabricante->getPais()}')
            where codigoFabricante ={$fabricante->getCodigoFabricante()}";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            //    header('location:index.php');
        } else {
            echo "";
        }
    }

    //DELETE
    function remover($id)
    {
        global $conexao;
        $sql = "delete from fabricante where codigoFabricante = {$id}";
        $result = mysqli_query($conexao, $sql);
        // header('location:index.php');
    }
}
