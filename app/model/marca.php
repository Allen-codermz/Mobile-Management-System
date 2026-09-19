<?php
class marca
{
    private $codigoMarca;
    private $nome;
    private $codigoFabricante;
    private $fabricante;

    public function __construct($codigoMarca, $nome, $codigoFabricante, $fabricante)
    {
        $this->codigoMarca = $codigoMarca;
        $this->nome = $nome;
        $this->codigoFabricante = $codigoFabricante;
        $this->fabricante = $fabricante;
    }

    public function getCodigoMarca()
    {
        return $this->codigoMarca;
    }
    public function setCodigoMarca($codigoMarca)
    {
        $this->codigoMarca = $codigoMarca;
    }

    public function getnome()
    {
        return $this->nome;
    }
    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getCodigoFabricante()
    {
        return $this->codigoFabricante;
    }

    public function setCodigoFabricante($codigoFabricante)
    {
        $this->codigoFabricante = $codigoFabricante;
    }

    public function getFabricante()
    {
        return $this->fabricante;
    }
    public function setFabricante($fabricante)
    {
        $this->fabricante = $fabricante;
    }
}
