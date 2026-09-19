<?php 
class ModelPais{
    private $codigoPais;
    private $nome;
    private $codigoContinente;

    public function __construct($codigoPais,$nome,$codigoContinente)
    {
        $this->codigoPais=$codigoPais;
        $this->nome=$nome;
        $this->codigoContinente=$codigoContinente;

    }
    public function getcodigoPais()
    {return $this->codigoPais;}
    public function setCodigoCor($codigoPais)
    {$this->codigoPais=$codigoPais;}

    public function getnome()
    {return $this->nome;}
    public function setNome($nome)
    {$this->nome=$nome;}

    public function getcodigoContinente()
    {return $this->codigoContinente;}
    public function setcodigoContinete($codigoContinente)
    {$this->codigoContinente=$codigoContinente;}
}
?>