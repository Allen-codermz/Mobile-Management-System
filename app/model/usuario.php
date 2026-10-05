<?php

class usuario
{
    private $codigoUsuario;
    private $nome;
    private $apelido;
    private $username;
    private $email;
    private $contacto;
    private $senha;
    private $codigoPerfil;

    public function __construct($codigoUsuario, $nome, $apelido, $username, $email, $contacto, $senha, $codigoPerfil)
    {
        $this->codigoUsuario = $codigoUsuario;
        $this->nome = $nome;
        $this->apelido = $apelido;
        $this->username = $username;
        $this->email = $email;
        $this->contacto = $contacto;
        $this->senha = $senha;
        $this->codigoPerfil = $codigoPerfil;
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

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
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

    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
        return $this;
    }

    public function getCodigoPerfil()
    {
        return $this->codigoPerfil;
    }

    public function setCodigoPerfil($codigoPerfil)
    {
        $this->codigoPerfil = $codigoPerfil;
        return $this;
    }
}