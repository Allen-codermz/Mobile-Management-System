<?php

class usuario
{
    private $codigoUsuario;
    private $nome;
    private $apelido;
    private $email;
    private $contacto;
    private $sexo;

    public function __construct($codigoUsuario, $nome, $apelido, $email, $contacto, $sexo)
    {
        $this->codigoUsuario = $codigoUsuario;
        $this->nome = $nome;
        $this->apelido = $apelido;
        $this->email = $email;
        $this->contacto = $contacto;
        $this->sexo = $sexo;
    }

    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    public function setCodigoUsuario($codigoUsuario)
    {
        $this->codigoUsuario = $codigoUsuario;
        return $this;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
        return $this;
    }

    public function getApelido()
    {
        return $this->apelido;
    }

    public function setApelido($apelido)
    {
        $this->apelido = $apelido;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getContacto()
    {
        return $this->contacto;
    }

    public function setContacto($contacto)
    {
        $this->contacto = $contacto;
        return $this;
    }

    public function getSexo()
    {
        return $this->sexo;
    }

    public function setSexo($sexo)
    {
        $this->sexo = $sexo;
        return $this;
    }
}
