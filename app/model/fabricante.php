<?php

class Fabricante
{
    private $codigoFabricante;
    private $nome;
    private $codigoPais;
    private $pais;
    private $codigoContinente;
    private $continente;

    public function __construct(
        $codigoFabricante,
        $nome,
        $codigoPais,
        $pais,
        $codigoContinente,
        $continente
    ) {
        $this->codigoFabricante = $codigoFabricante;
        $this->nome = $nome;
        $this->codigoPais = $codigoPais;
        $this->pais = $pais;
        $this->codigoContinente = $codigoContinente;
        $this->continente = $continente;
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

    public function getContinente()
    {
        return $this->continente;
    }
}
