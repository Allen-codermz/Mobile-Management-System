<?php

class ControllerLog
{
    private $conexao;
    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    public function criar($log)
    {
        $codigoUsuario = $log->getCodigoUsuario();
        $acao = $log->getAcao();
        $descricao = $log->getDescricao();
        $sql = "INSERT INTO logs (codigoUsuario, acao, descricao)VALUES ('$codigoUsuario', '$acao', '$descricao')";
        return mysqli_query($this->conexao, $sql);
    }

    public function listar()
    {
        $sql = "SELECT  logs.codigoLog, logs.codigoUsuario, logs.acao, logs.descricao, logs.dataHora, usuario.nome, usuario.apelido, usuario.username
                FROM logs
                LEFT JOIN usuario 
                ON logs.codigoUsuario = usuario.codigoUsuario
                ORDER BY logs.dataHora DESC";
        $resultado = mysqli_query($this->conexao, $sql);
        $logs = array();
        while ($linha = mysqli_fetch_assoc($resultado)) {
            $log = new Logs($linha['codigoLog'], $linha['codigoUsuario'], $linha['acao'], $linha['descricao'], $linha['dataHora']);
            // Guardamos os dados do utilizador no próprio objeto
            $log->setNomeUsuario($linha['nome']);
            $log->setApelidoUsuario($linha['apelido']);
            $log->setUsernameUsuario($linha['username']);
            $logs[] = $log;
        }
        return $logs;
    }
}
