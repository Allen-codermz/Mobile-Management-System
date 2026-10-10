<?php
include_once __DIR__ . '/../model/celular.php';
include_once __DIR__ . '/../config/conexao.php';

class ControllerCelular
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


    function listarModelos()
    {
        $modelos = array();
        $sql = "SELECT codigoModelo, modelo FROM modelo";
        $result = mysqli_query($this->conexao, $sql);
        if ($result) {
            while ($rs = mysqli_fetch_assoc($result)) {
                $modelos[] = $rs;
            }
        }
        return $modelos;
    }


    function listarCores()
    {
        $cores = array();
        $sql = "SELECT codigoCor, descricao, Cor FROM cor";
        $result = mysqli_query($this->conexao, $sql);
        if ($result) {
            while ($rs = mysqli_fetch_assoc($result)) {
                $cores[] = $rs;
            }
        }
        return $cores;
    }

    function listar()
    {
        $celulares = array();

        $sql = "SELECT ce.numeroDeSerie, ce.preco, ce.anoDeFabrico, ma.codigoMarca, ma.marca, fa.codigoFabricante, fa.fabricante, co.codigoCor, co.Cor, co.descricao , mo.codigoModelo, mo.modelo
            FROM celulares ce
            LEFT JOIN marca ma
            ON ce.codigoMarca = ma.codigoMarca
            LEFT JOIN fabricante fa
            ON ce.codigoFabricante = fa.codigoFabricante
            LEFT JOIN cor co
            ON ce.codigoCor = co.codigoCor
            LEFT JOIN modelo mo
            ON ce.codigoModelo = mo.codigoModelo";
        $result = mysqli_query($this->conexao, $sql);
        if ($result) {
            while ($rs = mysqli_fetch_assoc($result)) {

                // Criar objeto com os códigos
                $celular = new celular($rs["numeroDeSerie"], $rs["preco"], $rs["anoDeFabrico"], $rs["codigoMarca"], $rs["codigoFabricante"], $rs["codigoCor"], $rs["codigoModelo"]);

                // Guardar os nomes
                $celular->setMarca($rs["marca"]);
                $celular->setFabricante($rs["fabricante"]);
                $celular->setCor($rs["Cor"]);
                $celular->setDescricaoCor($rs["descricao"]);
                $celular->setModelo($rs["modelo"]);
                array_push($celulares, $celular);
            }
        }
        return $celulares;
    }

    function criar($celular)
    {
        $sql = "SELECT * FROM celulares  WHERE codigoMarca = '{$celular->getCodigoMarca()}' AND codigoFabricante = '{$celular->getCodigoFabricante()}' AND codigoModelo = '{$celular->getCodigoModelo()}' 
        AND codigoCor = '{$celular->getCodigoCor()}' AND preco = '{$celular->getPreco()}' AND anoDeFabrico = '{$celular->getAnoDeFabrico()}'";
        $result = mysqli_query($this->conexao, $sql);
        if (mysqli_num_rows($result) > 0) {
            echo "";
        } else {
            $sql = "INSERT INTO celulares  VALUES( null, '{$celular->getPreco()}', '{$celular->getAnoDeFabrico()}', '{$celular->getCodigoMarca()}', '{$celular->getCodigoFabricante()}', '{$celular->getCodigoCor()}', '{$celular->getCodigoModelo()}' )";
            $result = mysqli_query($this->conexao, $sql);
            if ($result) {
                // header('location:index.php');
            } else {
                echo "";
            }
        }
        return $result;
    }

    // UPDATE
    function editar($celular)
    {
        $sql = "UPDATE celulares 
                SET preco = '{$celular->getPreco()}', anoDeFabrico = '{$celular->getAnoDeFabrico()}', codigoMarca = '{$celular->getCodigoMarca()}', 
                codigoFabricante = '{$celular->getCodigoFabricante()}', codigoCor = '{$celular->getCodigoCor()}', codigoModelo = '{$celular->getCodigoModelo()}'
                WHERE numeroDeSerie = {$celular->getNumeroDeSerie()}";
        $result = mysqli_query($this->conexao, $sql);
        return $result;
    }

    //DELETE
    function remover($id)
    {
        $sql = "delete from celulares where numeroDeSerie = {$id}";
        $result = mysqli_query($this->conexao, $sql);
        return $result;
    }

    function encontrarId($id)
    {
        $sql = "SELECT ce.numeroDeSerie, ce.preco, ce.anoDeFabrico, ce.codigoMarca, ce.codigoFabricante, ce.codigoCor, ce.codigoModelo
                FROM celulares ce
                WHERE ce.numeroDeSerie = {$id}";
        $result = mysqli_query($this->conexao, $sql);
        if ($result && mysqli_num_rows($result) > 0) {
            $rs = mysqli_fetch_assoc($result);
            return new celular($rs['numeroDeSerie'], $rs['preco'], $rs['anoDeFabrico'], $rs['codigoMarca'], $rs['codigoFabricante'], $rs['codigoCor'], $rs['codigoModelo']);
        }
        return null;
    }

    function pesquisar($termo)
    {
        $celulares = array();
        $t = mysqli_real_escape_string($this->conexao, $termo);

        $sql = "SELECT ce.numeroDeSerie, ce.preco, ce.anoDeFabrico, ma.codigoMarca, ma.marca, fa.codigoFabricante, fa.fabricante, co.codigoCor, co.Cor, co.descricao, mo.codigoModelo, mo.modelo
            FROM celulares ce
            LEFT JOIN marca ma ON ce.codigoMarca = ma.codigoMarca
            LEFT JOIN fabricante fa ON ce.codigoFabricante = fa.codigoFabricante
            LEFT JOIN cor co ON ce.codigoCor = co.codigoCor
            LEFT JOIN modelo mo ON ce.codigoModelo = mo.codigoModelo
            WHERE ce.numeroDeSerie LIKE '%$t%'
                OR ce.preco LIKE '%$t%'
                OR ce.anoDeFabrico LIKE '%$t%'
                OR ma.marca LIKE '%$t%'
                OR fa.fabricante LIKE '%$t%'
                OR mo.modelo LIKE '%$t%'
                OR co.Cor LIKE '%$t%'
                OR co.descricao LIKE '%$t%'";
        $result = mysqli_query($this->conexao, $sql);
        if ($result) {
            while ($rs = mysqli_fetch_assoc($result)) {
                $celular = new celular($rs["numeroDeSerie"], $rs["preco"], $rs["anoDeFabrico"], $rs["codigoMarca"], $rs["codigoFabricante"], $rs["codigoCor"], $rs["codigoModelo"]);
                $celular->setMarca($rs["marca"]);
                $celular->setFabricante($rs["fabricante"]);
                $celular->setCor($rs["Cor"]);
                $celular->setDescricaoCor($rs["descricao"]);
                $celular->setModelo($rs["modelo"]);
                array_push($celulares, $celular);
            }
        }
        return $celulares;
    }
}
