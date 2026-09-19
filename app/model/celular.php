<?php
class ModelCelular
{
    private $codigoCelular;
    private $referencia;
    private $preco;
    private $codigoMarca;
    private $anodeFabrico;
    private  $codigoFabricante;

    public function _construct($codigoCelular,$referencia,$preco,$codigoMarca,$anodeFabrico, $codigoFabricante)
    {
        $this->codigoCelular=$codigoCelular;
        $this->referencia=$referencia;
        $this->preco=$preco;
        $this->codigoMarca=$codigoMarca;
        $this->anodeFabrico=$anodeFabrico;
        $this->codigoFabricante=$codigoFabricante;
    }
    public function getcodigoCelular()
    {return $this->codigoCelular;}
    public function setcodigoCelular($codigoCelular)
    {$this->codigoCelular=$codigoCelular;}

    public function getReferencia()
    {return $this->referencia;}
    public function setRefencia($referencia)
    {$this->referencia=$referencia;}

    public function getpreco()
    {return $this->preco;}
    public function setPreco($preco)
    {$this->preco=$preco;}

    public function getCodigoMarca()
    {return $this->codigoMarca;}
    public function setcodigoMarca($codigoMarca)
    {$this->codigoMarca=$codigoMarca;}

    public function getanodefabrico()
    {return $this->anodeFabrico;}
    public function setanodefabrico($anodeFabrico)
    {$this->anodeFabrico=$anodeFabrico;}

    public function getcodigoFabricante()
    {return $this->codigoFabricante;}
    public function setcodigoFabricante($codigoFabricante)
    {$this->codigoFabricante=$codigoFabricante;}

}
?>