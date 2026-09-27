<?php

class celular
{
    private $numeroDeSerie;
    private $preco;
    private $anoDeFabrico;
    private $codigoMarca;
    private $codigoFabricante;
    private $codigoCor;
    private $codigoModelo;

    public function __construct($numeroDeSerie, $preco, $anoDeFabrico, $codigoMarca, $codigoFabricante, $codigoCor, $codigoModelo)
    {
        $this->numeroDeSerie = $numeroDeSerie;
        $this->preco = $preco;
        $this->anoDeFabrico = $anoDeFabrico;
        $this->codigoMarca = $codigoMarca;
        $this->codigoFabricante = $codigoFabricante;
        $this->codigoCor = $codigoCor;
        $this->codigoModelo = $codigoModelo;
    }

    public function getNumeroDeSerie()
    {
        return $this->numeroDeSerie;
    }

    public function setNumeroDeSerie($numeroDeSerie)
    {
        $this->numeroDeSerie = $numeroDeSerie;
    }

    public function getPreco()
    {
        return $this->preco;
    }

    public function setPreco($preco)
    {
        $this->preco = $preco;
    }

    public function getAnoDeFabrico()
    {
        return $this->anoDeFabrico;
    }

    public function setAnoDeFabrico($anoDeFabrico)
    {
        $this->anoDeFabrico = $anoDeFabrico;
    }

    public function getCodigoMarca()
    {
        return $this->codigoMarca;
    }

    public function setCodigoMarca($codigoMarca)
    {
        $this->codigoMarca = $codigoMarca;
    }

    public function getCodigoFabricante()
    {
        return $this->codigoFabricante;
    }

    public function setCodigoFabricante($codigoFabricante)
    {
        $this->codigoFabricante = $codigoFabricante;
    }

    public function getCodigoCor()
    {
        return $this->codigoCor;
    }

    public function setCodigoCor($codigoCor)
    {
        $this->codigoCor = $codigoCor;
    }

    public function getCodigoModelo()
    {
        return $this->codigoModelo;
    }

    public function setCodigoModelo($codigoModelo)
    {
        $this->codigoModelo = $codigoModelo;
    }
}
