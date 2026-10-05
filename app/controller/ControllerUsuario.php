<?php

include_once __DIR__ . '/../model/usuario.php';

class ControllerUsuario
{
    private $conexao;

    public function __construct($conexao)
    {
        $this->conexao = $conexao;
    }

    public function criar(usuario $usuario)
    {
        $nome = $usuario->getNome();
        $apelido = $usuario->getApelido();
        $username = $usuario->getUsername();
        $email = $usuario->getEmail();
        $contacto = $usuario->getContacto();
        $senha = $usuario->getSenha();
        $codigoPerfil = $usuario->getCodigoPerfil();

        // Criptografar a senha antes de guardar
        $senha = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario
                (nome, apelido, username, email, contacto, senha, codigoPerfil)
                VALUES
                ('$nome', '$apelido', '$username', '$email', '$contacto', '$senha', '$codigoPerfil')";
        return mysqli_query($this->conexao, $sql);
    }

    public function listar()
    {
        $sql = "SELECT  u.codigoUsuario, u.nome, u.apelido, u.username, u.email, u.contacto, u.senha, u.codigoPerfil, p.nome AS perfil
                FROM usuario u
                INNER JOIN perfil p
                ON u.codigoPerfil = p.codigoPerfil
                ORDER BY u.codigoUsuario DESC";
        $resultado = mysqli_query($this->conexao, $sql);
        $usuarios = [];
        if ($resultado) {
            while ($dados = mysqli_fetch_assoc($resultado)) {
                $usuarios[] = new usuario( $dados['codigoUsuario'], $dados['nome'], $dados['apelido'], $dados['username'], $dados['email'], $dados['contacto'], $dados['senha'], $dados['codigoPerfil'] );
            }
        }
        return $usuarios;
    }

    public function encontrarId($codigoUsuario)
    {
        $sql = "SELECT codigoUsuario, nome, apelido, username, email, contacto, senha, codigoPerfil
                FROM usuario
                WHERE codigoUsuario = '$codigoUsuario'
                LIMIT 1";
        $resultado = mysqli_query($this->conexao, $sql);
        if (!$resultado) {
            return null;
        }
        if (mysqli_num_rows($resultado) == 0) {
            return null;
        }
        $dados = mysqli_fetch_assoc($resultado);
        return new usuario( $dados['codigoUsuario'], $dados['nome'], $dados['apelido'], $dados['username'], $dados['email'], $dados['contacto'], $dados['senha'], $dados['codigoPerfil'] );
    }

    public function editar(usuario $usuario)
    {
        $codigoUsuario = $usuario->getCodigoUsuario();
        $nome = $usuario->getNome();
        $apelido = $usuario->getApelido();
        $username = $usuario->getUsername();
        $email = $usuario->getEmail();
        $contacto = $usuario->getContacto();
        $senha = $usuario->getSenha();
        $codigoPerfil = $usuario->getCodigoPerfil();
        // Se foi informada uma nova senha
        if (!empty($senha)) {
            $senha = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "UPDATE usuario
                    SET nome = '$nome', apelido = '$apelido', username = '$username', email = '$email', contacto = '$contacto', senha = '$senha', codigoPerfil = '$codigoPerfil'
                    WHERE codigoUsuario = '$codigoUsuario'";
        } else {
            // Se a senha estiver vazia,
            // mantém a senha que já existe
            $sql = "UPDATE usuario
                    SET nome = '$nome', apelido = '$apelido', username = '$username', email = '$email', contacto = '$contacto', codigoPerfil = '$codigoPerfil'
                    WHERE codigoUsuario = '$codigoUsuario'";
        }
        return mysqli_query($this->conexao, $sql);
    }

    public function remover($codigoUsuario)
    {
        $sql = "DELETE FROM usuario
                WHERE codigoUsuario = '$codigoUsuario'";
        return mysqli_query($this->conexao, $sql);
    }

    public function listarPerfis()
    {
        $sql = "SELECT codigoPerfil, nome
                FROM perfil
                ORDER BY codigoPerfil";
        $resultado = mysqli_query($this->conexao, $sql);
        $perfis = [];
        if ($resultado) {
            while ($dados = mysqli_fetch_assoc($resultado)) {
                $perfis[] = $dados;
            }
        }
        return $perfis;
    }
}