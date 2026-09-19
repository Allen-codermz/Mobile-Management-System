<?php
class Cor
{
    private $codigoCor;
    private $corHex;
    private $descricao;

    public function __construct($codigoCor, $corHex, $descricao)
    {
        $this->codigoCor = $codigoCor;
        $this->corHex = $corHex;
        $this->descricao = $descricao;
    }

    public function getCodigoCor()
    {
        return $this->codigoCor;
    }
    public function setCodigoCor($codigoCor)
    {
        $this->codigoCor = $codigoCor;
    }

    public function getCorHex()
    {
        return $this->corHex;
    }
    public function setCorHex($corHex)
    {
        $this->corHex = $corHex;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }
    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }
}
