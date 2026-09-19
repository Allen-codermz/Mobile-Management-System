<?php 
class modelo{
    private $codigoModelo;
    private $nome;
    private $codigoMarca;
    private $marca;

    public function __construct($codigoModelo,$nome,$codigoMarca,$marca)
    {
        $this->codigoMarca=$codigoMarca;
        $this->nome=$nome;
        $this->codigoMarca;
        $this->marca;

    }
    public function getCodigoModelo()
    {return $this->codigoModelo;}
    public function setCodigoModelo($codigoModelo)
    {$this->codigoModelo=$codigoModelo;}

    public function getnome()
    {return $this->nome;}
    public function setNome($nome)
    {$this->nome=$nome;}
    
    public function getCodigoMarca()
    {return $this->codigoMarca;}
    public function setCodigoMarca($codigoMarca)
    {$this->codigoMarca=$codigoMarca;}

    public function getMarca()
    {return $this->marca;}
    public function setMarca($marca)
    {$this->marca=$marca;}
}
?>