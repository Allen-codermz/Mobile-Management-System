<?php

class usuario
{
    private $codigoUsuario;
    private $nome;
    private $apelido;
    private $username;
    private $email;
    private $estadoCivil;
    private $nomePerfil;
    private $nomeEstadoCivil;
    private $genero;
    private $bilhete;
    private $contacto;
    private $senha;
    private $codigoPerfil;
    private $primeiroAcesso;

    public function __construct($codigoUsuario, $nome, $apelido, $username, $email, $estadoCivil, $nomePerfil, $nomeEstadoCivil, $genero, $bilhete, $contacto, $senha, $codigoPerfil, $primeiroAcesso)
    {
        $this->codigoUsuario = $codigoUsuario;
        $this->nome = $nome;
        $this->apelido = $apelido;
        $this->username = $username;
        $this->email = $email;
        $this->estadoCivil = $estadoCivil;
        $this->nomePerfil = $nomePerfil;
        $this->nomeEstadoCivil = $nomeEstadoCivil;
        $this->genero = $genero;
        $this->bilhete = $bilhete;
        $this->contacto = $contacto;
        $this->senha = $senha;
        $this->codigoPerfil = $codigoPerfil;
        $this->primeiroAcesso = $primeiroAcesso;
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

    public function getEstadoCivil()
    {
        return $this->estadoCivil;
    }

    public function setEstadoCivil($estadoCivil)
    {
        $this->estadoCivil = $estadoCivil;
        return $this;
    }

    public function getNomePerfil()
    {
        return $this->nomePerfil;
    }

    public function setNomePerfil($nomePerfil)
    {
        $this->nomePerfil = $nomePerfil;
        return $this;
    }

    public function getNomeEstadoCivil()
    {
        return $this->nomeEstadoCivil;
    }

    public function setNomeEstadoCivil($nomeEstadoCivil)
    {
        $this->nomeEstadoCivil = $nomeEstadoCivil;
        return $this;
    }

    public function getGenero()
    {
        return $this->genero;
    }

    public function setGenero($genero)
    {
        $this->genero = $genero;
        return $this;
    }

    public function getBilhete()
    {
        return $this->bilhete;
    }

    public function setBilhete($bilhete)
    {
        $this->bilhete = $bilhete;
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

    public function getPrimeiroAcesso()
    {
        return $this->primeiroAcesso;
    }

    public function setPrimeiroAcesso($primeiroAcesso)
    {
        $this->primeiroAcesso = $primeiroAcesso;
        return $this;
    }
}