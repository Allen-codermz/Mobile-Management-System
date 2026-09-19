<?php
include_once("../../model/marca.php");
include_once("../config/conexao.php");

class ControllerMarca
{

    private $conexao;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    function listar()
    {
        global $conexao;
        $marcas = array();
        $sql = "select m.codigoMarca, m.marca, m.codigoFabricante, f.fabricante
        from marca m
        inner join fabricante f
        on m.codigofabricante = f.codigoFabricante";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            while ($rs = mysqli_fetch_assoc($result)) {
                $id = $rs["codigoMarca"];
                $nome = $rs["marca"];
                $codigoFabricante = $rs["codigoFabricante"];
                $fabricante = $rs["fabricante"];

                $marca = new marca($id, $nome, $codigoFabricante, $fabricante);
                array_push($marcas, $marca);
            }
        }
    }
    function criar($marca)
    {
        global $conexao;
        $sql = "select * from marca where marca = {$marca->getNome()} or codigoFabricante='{$marca->getCodigofabricante()}' or fabricante='{$marca->getFabricante()}'";
        $result = mysqli_query($conexao, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "";
        } else {
            $sql = "insert into modelo values(null,'{$marca->getNome()}','{$marca->getCodigofabricante()}','{$marca->getFabricante()}')";
            $result = mysqli_query($conexao, $sql);
            if ($result) {
                // header('location:index.php');
            } else {
                echo "";
            }
        }
    }

    function editar($marca)
    {
        global $conexao;
        $sql = "Update marca set marca = '{$marca->getNome()}',codigoMarca = {$marca->getCodigofabricante()}, marca = '{$marca->getFabricante()}')
            where codigoMarca ={$marca->getCodidoMarca()}";

        $result = mysqli_query($conexao, $sql);
        if ($result) {
            //    header('location:index.php');
        } else {
            echo "";
        }
    }

    function remover($id)
    {
        global $conexao;
        $sql = "delete from marca where codigoMarca = {$id}";
        $result = mysqli_query($conexao, $sql);
        // header('location:index.php');
    }
}
