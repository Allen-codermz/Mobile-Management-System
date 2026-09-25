<?php
include_once __DIR__ . '/../model/modelo.php';
include_once __DIR__ . '/../config/conexao.php';


class ControllerModelo
{
    private $conexao;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    function listarMarcas()
    {
        $marcas = array();
        $sql = "SELECT codigoMarca, marca FROM marca";
        $result = mysqli_query($this->conexao, $sql);
        if ($result) {
            while ($rs = mysqli_fetch_assoc($result)) {
                $marcas[] = $rs;
            }
        }
        return $marcas;
    }


    //READ
    function listar()
    {
        $modelos = array();
        $sql = "select mo.codigoModelo, mo.modelo, mo.codigoMarca, m.marca
        from modelo mo
        inner join marca m
        on mo.codigoMarca = m.codigoMarca";
        $result = mysqli_query($this->conexao, $sql);
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
        $sql = "select * from modelo where modelo= '{$modelo->getNome()}' and codigoMarca='{$modelo->getcodigoMarca()}'";
        $result = mysqli_query($this->conexao, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "";
        } else {
            $sql = " insert into modelo values (null,'{$modelo->getNome()}','{$modelo->getcodigoMarca()}')";
            $result = mysqli_query($this->conexao, $sql);
            if ($result) {
                // header('location:index.php');
            } else {
                echo "";
            }
        }
        return $result;
    }

    //UPDATE
    function editar($modelo)
    {
        $sql = "update modelo set modelo = '{$modelo->getNome()}',codigoMarca = '{$modelo->getcodigoMarca()}'
            where codigoModelo = {$modelo->getCodigoModelo()}";

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
        $sql = "delete from modelo where codigoModelo = {$id}";
        $result = mysqli_query($this->conexao, $sql);
        // header('location:index.php');
        return $result;
    }

    function encontrarId($id)
    {
        $sql = "select  mo.codigoModelo, mo.modelo, mo.codigoMarca, m.marca
            from modelo mo
            inner join marca m
        on mo.codigoMarca = m.codigoMarca
        where mo.codigoModelo = $id";
        $result = mysqli_query($this->conexao, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $rs = mysqli_fetch_assoc($result);
            return new modelo($rs['codigoModelo'], $rs['modelo'], $rs['codigoMarca'], null);
        }
        return null;
    }
}
