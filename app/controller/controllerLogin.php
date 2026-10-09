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
        $sql = "SELECT  u.codigoUsuario,  u.nome,  u.apelido,  u.username,  u.email,  u.contacto,  u.senha,  u.codigoPerfil,  u.codigoEstadoCivil,  u.genero,  u.bilhete_de_identidade,  u.primeiro_acesso ,  p.nome AS nomePerfil,  ec.estado_civil AS nomeEstadoCivil
                FROM usuario u
                INNER JOIN perfil p
                ON u.codigoPerfil = p.codigoPerfil
                INNER JOIN estado_civil ec
                ON u.codigoEstadoCivil = ec.codigoEstadoCivil
                WHERE u.username = '$username'
                LIMIT 1";
        $resultado = mysqli_query($this->conexao, $sql);
        if (!$resultado) {
            return null;
        }
        if (mysqli_num_rows($resultado) == 0) {
            return null;
        }
        $dados = mysqli_fetch_assoc($resultado);
        // Verificar a palavra-passe
        if (!password_verify($senha, $dados['senha'])) {
            return null;
        }
        return new usuario($dados['codigoUsuario'], $dados['nome'], $dados['apelido'], $dados['username'], $dados['email'], $dados['codigoEstadoCivil'], $dados['nomePerfil'], $dados['nomeEstadoCivil'], $dados['genero'], $dados['bilhete_de_identidade'], $dados['contacto'], $dados['senha'], $dados['codigoPerfil'], $dados['primeiro_acesso']);
    }
}
