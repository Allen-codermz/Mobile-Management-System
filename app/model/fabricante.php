<?php

class Fabricante
{
    private $codigoFabricante;
    private $nome;
    private $codigoPais;
    private $pais;

    public function __construct($codigoFabricante, $nome, $codigoPais, $pais)
    {
        $this->codigoFabricante = $codigoFabricante;
        $this->nome = $nome;
        $this->codigoPais = $codigoPais;
        $this->pais = $pais;
    }

    public function getCodigoFabricante()
    {
        return $this->codigoFabricante;
    }

    public function setCodigoFabricante($codigoFabricante)
    {
        $this->codigoFabricante = $codigoFabricante;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getCodigoPais()
    {
        return $this->codigoPais;
    }

    public function setCodigoPais($codigoPais)
    {
        $this->codigoPais = $codigoPais;
    }

    public function getPais()
    {
        return $this->pais;
    }

    public function setPais($pais)
    {
        $this->pais = $pais;
    }
}
