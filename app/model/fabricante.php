<?php

class Fabricante
{
    private $codigoFabricante;
    private $nome;
    private $codigoPais;
    private $pais;
    private $codigoContinente;

    public function __construct(
        $codigoFabricante,
        $nome,
        $codigoPais,
        $pais,
        $codigoContinente = null
    ) {
        $this->codigoFabricante = $codigoFabricante;
        $this->nome = $nome;
        $this->codigoPais = $codigoPais;
        $this->pais = $pais;
        $this->codigoContinente = $codigoContinente;
    }

    public function getCodigoFabricante()
    {
        return $this->codigoFabricante;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function getCodigoPais()
    {
        return $this->codigoPais;
    }

    public function getPais()
    {
        return $this->pais;
    }

    public function getCodigoContinente()
    {
        return $this->codigoContinente;
    }
}
