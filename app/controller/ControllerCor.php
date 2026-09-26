<?php
include_once __DIR__ . '/../model/modelo.php';
include_once __DIR__ . '/../config/conexao.php';

class ControllerCor
{

    private $conexao;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }


    function listar()
    {
        $cores = array();
        $sql = "select * from cor";
        $result = mysqli_query($this->conexao, $sql);

        if ($result) {
            while ($rs = mysqli_fetch_assoc($result)) {
                $id = $rs["codigoCor"];
                $nome = $rs["Cor"];
                $descricao = $rs["descricao"];

                $cor = new cor($id, $nome, $descricao);
                array_push($cores, $cor);
            }
        }
        return $cores;
    }
    function criar($cor)
    {
        global $conexao;
        $sql = "select * from cor where Cor = {$cor->getCorHex()} or descricao='{$cor->getDescricao()}'";
        $result = mysqli_query($conexao, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "";
        } else {
            $sql = "insert into cor values(null,'{$cor->getCorHex()}','{$cor->getDescricao()}'";
            $result = mysqli_query($conexao, $sql);
            if ($result) {
                // header('location:index.php');
            } else {
                echo "";
            }
        }
        return $result;
    }

    function editar($cor)
    {
        global $conexao;
        $sql = "Update cor set Cor = '{$cor->getCorHex()}', descricao = {$cor->getDescricao()}')
            where codigoCor ={$cor->getCodidoCor()}";

        $result = mysqli_query($conexao, $sql);
        if ($result) {
            //    header('location:index.php');
        } else {
            echo "";
        }
        return $result;
    }

    function remover($id)
    {
        global $conexao;
        $sql = "delete from Cor where codigoCor = {$id}";
        $result = mysqli_query($conexao, $sql);
        // header('location:index.php');

        return $result;
    }

    public function encontrarId($id)
    {
        $sql = "select codigoCor, Cor, descricao
                from cor
                where codigoCor = {$id}";
        $result = mysqli_query($this->conexao, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $rs = mysqli_fetch_assoc($result);
            return new Cor($rs["codigoCor"], $rs["Cor"], $rs["descricao"] );
        }
        return null;
    }
}
