<?php
include_once("/../model/modelo.php");
include_once("../config/conexao.php");

class ControllerModelo
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
        $modelos = array();
        $sql = "select mo.codigoModelo, mo.modelo, mo.codigoMarca, m.marca
        from modelo mo
        inner join marca m
        on mo.codigoMarca = m.codigoMarca";
        $result = mysqli_query($conexao, $sql);
        if ($result) {
            while ($rs = mysqli_fetch_assoc($result)) {
                $id = $rs["codigoModelo"];
                $nome = $rs["modelo"];
                $codigoMarca = $rs["codigoMarca"];
                $marca = $rs["marca"];

                $modelo = new modelo($id, $nome, $codigoMarca, $marca);
                array_push($modelos, $modelo);
            }
        }
        return $modelos;
    }

    //CREATE
    function criar($modelo)
    {
        global $conexao;
        $sql = "select * from modelo where modelo={$modelo->getNome()} or codigoMarca='{$modelo->getcodigoMarca()}' or marca='{$modelo->getMarca()}'";
        $result = mysqli_query($conexao, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "";
        } else {
            $sql = "insert into modelo values(null,'{$modelo->getNome()}','{$modelo->getcodigoMarca()}','{$modelo->getMarca()}')";
            $result = mysqli_query($conexao, $sql);
            if ($result) {
                // header('location:index.php');
            } else {
                echo "";
            }
        }
    }

    //UPDATE
    function editar($modelo)
    {
        global $conexao;
        $sql = "update modelo set modelo = '{$modelo->getNome()}',codigoMarca = {$modelo->getcodigoMarca()}, marca = '{$modelo->getMarca()}')
            where codigoModelo ={$modelo->getCodidoModelo()}";

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
        $sql = "delete from modelo where codigoModelo = {$id}";
        $result = mysqli_query($conexao, $sql);
        // header('location:index.php');
    }


}
