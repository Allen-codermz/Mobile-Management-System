<?php 
class ModelContinente{
    private $codigoContinente;
    private $nome;

    public function __construct($codigoContinente,$nome,){
        $this->codigoContinente=$codigoContinente;
        $this->nome=$nome;
    }
    
    public function getcodigoContinente()
    {return $this->codigoContinente;}
    public function setcodigoContinete($codigoContinente)
    {$this->codigoContinente=$codigoContinente;}

    public function getnome()
    {return $this->nome;}
    public function setNome($nome)
    {$this->nome=$nome;}
}
?>