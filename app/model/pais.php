<?php

class ModelPais
{
    private $codigoPais;
    private $nome;
    private $codigoContinente;
    private $nomeContinente;

    public function __construct(
        $codigoPais,
        $nome,
        $codigoContinente,
        $nomeContinente = null
    ) {
        $this->codigoPais = $codigoPais;
        $this->nome = $nome;
        $this->codigoContinente = $codigoContinente;
        $this->nomeContinente = $nomeContinente;
    }

    public function getcodigoPais()
    {
        return $this->codigoPais;
    }

    public function setCodigoPais($codigoPais)
    {
        $this->codigoPais = $codigoPais;
    }

    public function getnome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getcodigoContinente()
    {
        return $this->codigoContinente;
    }

    public function setCodigoContinente($codigoContinente)
    {
        $this->codigoContinente = $codigoContinente;
    }

    public function getNomeContinente()
    {
        return $this->nomeContinente;
    }

    public function setNomeContinente($nomeContinente)
    {
        $this->nomeContinente = $nomeContinente;
    }
}
