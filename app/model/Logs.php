<?php

class Logs
{
    private $codigoLog;
    private $codigoUsuario;
    private $acao;
    private $descricao;
    private $dataHora;

    private $nomeUsuario;
    private $apelidoUsuario;
    private $usernameUsuario;

    public function __construct(
        $codigoLog,
        $codigoUsuario,
        $acao,
        $descricao,
        $dataHora = null
    ) {
        $this->codigoLog = $codigoLog;
        $this->codigoUsuario = $codigoUsuario;
        $this->acao = $acao;
        $this->descricao = $descricao;
        $this->dataHora = $dataHora;
    }

    public function getCodigoLog()
    {
        return $this->codigoLog;
    }

    public function getCodigoUsuario()
    {
        return $this->codigoUsuario;
    }

    public function getAcao()
    {
        return $this->acao;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getDataHora()
    {
        return $this->dataHora;
    }

    public function setNomeUsuario($nomeUsuario)
    {
        $this->nomeUsuario = $nomeUsuario;
    }

    public function getNomeUsuario()
    {
        return $this->nomeUsuario;
    }

    public function setApelidoUsuario($apelidoUsuario)
    {
        $this->apelidoUsuario = $apelidoUsuario;
    }

    public function getApelidoUsuario()
    {
        return $this->apelidoUsuario;
    }

    public function setUsernameUsuario($usernameUsuario)
    {
        $this->usernameUsuario = $usernameUsuario;
    }

    public function getUsernameUsuario()
    {
        return $this->usernameUsuario;
    }
}