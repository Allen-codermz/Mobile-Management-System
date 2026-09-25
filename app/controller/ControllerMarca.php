<?php
include_once __DIR__ . '/../model/marca.php';
include_once __DIR__ . '/../config/conexao.php';

class ControllerMarca
{

    private $conexao;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    function listarFabricantes()
    {
        $fabricantes = array();
        $sql = "SELECT codigoFabricante, fabricante FROM fabricante";
        $result = mysqli_query($this->conexao, $sql);
        if ($result) {
            while ($rs = mysqli_fetch_assoc($result)) {
                $fabricantes[] = $rs;
            }
        }
        return $fabricantes;
    }

    function listar()
    {
        $marcas = array();
        $sql = "select m.codigoMarca, m.marca, m.codigoFabricante, f.fabricante
        from marca m
        inner join fabricante f
        on m.codigofabricante = f.codigoFabricante";
        $result = mysqli_query($this->conexao, $sql);
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
        return $marcas;
    }


    function criar($marca)
    {
        $sql = "select * from marca where marca = '{$marca->getNome()}' and codigoFabricante = '{$marca->getCodigofabricante()}'";
        $result = mysqli_query($this->conexao, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "";
        } else {
            $sql = " insert into marca values ( null , '{$marca->getNome()}' , '{$marca->getCodigofabricante()}')";
            $result = mysqli_query($this->conexao, $sql);

            if ($result) {
                header("Location: CadastroDeMarca.php");
                exit;
            } else {
                echo "";
            }
        }
        return $result;
    }

    function actualizar($marca)
    {
        $sql = "Update marca set marca = '{$marca->getNome()}',codigoMarca = {$marca->getCodigofabricante()}, marca = '{$marca->getFabricante()}')
            where codigoMarca ={$marca->getCodigoMarca()}";

        $result = mysqli_query($this->conexao, $sql);
        if ($result) {
            header("Location: CadastroDeMarca.php");
        } else {
            echo "";
        }
        return $result;
    }

    function remover($id)
    {
        $sql = "delete from marca where codigoMarca = {$id}";
        $result = mysqli_query($this->conexao, $sql);

        return $result;
    }


    function encontrarId($id)
    {
        $sql = "select  m.codigoMarca, m.marca, m.codigoFabricante, f.fabricante
            from marca m
            inner join fabricante f
        on m.codigofabricante = f.codigoFabricante
        where m.codigoMarca = $id";
        $result = mysqli_query($this->conexao, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $rs = mysqli_fetch_assoc($result);
            return new marca($rs['codigoMarca'], $rs['marca'], $rs['codigoFabricante'], null);
        }
        return null;
    }
}
