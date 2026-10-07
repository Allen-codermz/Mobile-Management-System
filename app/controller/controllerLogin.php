<?php

include_once __DIR__ . '/../model/usuario.php';

class controllerLogin
{
    private $conexao;
    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    public function login($username, $senha)
    {
        $username = mysqli_real_escape_string($this->conexao, $username);
        $sql = "SELECT  codigoUsuario, nome, apelido, username, email, contacto, senha, codigoPerfil
                FROM usuario
                WHERE username = '$username'
                LIMIT 1";
        $resultado = mysqli_query($this->conexao, $sql);
        if (!$resultado) {
            return null;
        }
        if (mysqli_num_rows($resultado) == 0) {
            return null;
        }
        $dados = mysqli_fetch_assoc($resultado);

        if (!password_verify($senha, $dados['senha'])) {
            return null;
        }
        return new usuario($dados['codigoUsuario'], $dados['nome'], $dados['apelido'], $dados['username'], $dados['email'], $dados['contacto'], $dados['senha'], $dados['codigoPerfil']);
    }
}
