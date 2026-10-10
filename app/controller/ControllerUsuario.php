<?php

include_once __DIR__ . '/../model/usuario.php';

class ControllerUsuario
{

    const SENHA_PADRAO = 'celular123';


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
        $estadoCivil = $usuario->getEstadoCivil();
        $genero = $usuario->getGenero();
        $bilhete = $usuario->getBilhete();
        $contacto = $usuario->getContacto();
        $codigoPerfil = $usuario->getCodigoPerfil();

        $senha = password_hash(self::SENHA_PADRAO, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuario
                (nome, apelido, username, email, contacto, senha, genero, codigoEstadoCivil, bilhete_de_identidade ,codigoPerfil, primeiro_acesso)
                VALUES
                ('$nome', '$apelido', '$username', '$email', '$contacto', '$senha', '$genero' , '$estadoCivil' , '$bilhete', '$codigoPerfil', 1)";
        return mysqli_query($this->conexao, $sql);
    }

    public function listar()
    {
        $usuarios = array();
        $sql = "SELECT u.codigoUsuario, u.nome, u.apelido, u.username, u.email, u.contacto, u.senha, u.genero, u.codigoEstadoCivil, u.bilhete_de_identidade, u.codigoPerfil, u.primeiro_acesso, p.nome AS nomePerfil, ec.estado_civil AS nomeEstadoCivil
            FROM usuario u
            INNER JOIN perfil p
                ON u.codigoPerfil = p.codigoPerfil
            INNER JOIN estado_civil ec
                ON u.codigoEstadoCivil = ec.codigoEstadoCivil
            ORDER BY u.codigoUsuario DESC";
        $result = mysqli_query($this->conexao, $sql);
        if ($result) {
            while ($rs = mysqli_fetch_assoc($result)) {
                $usuario = new usuario($rs["codigoUsuario"], $rs["nome"], $rs["apelido"], $rs["username"], $rs["email"], $rs["codigoEstadoCivil"], $rs["nomePerfil"], $rs["nomeEstadoCivil"], $rs["genero"], $rs["bilhete_de_identidade"], $rs["contacto"], $rs["senha"], $rs["codigoPerfil"], $rs["primeiro_acesso"]);
                array_push($usuarios, $usuario);
            }
        }
        return $usuarios;
    }


    public function encontrarId($codigoUsuario)
    {
        $sql = "SELECT u.codigoUsuario, u.nome, u.apelido, u.username, u.email, u.contacto, u.senha, u.genero, u.codigoEstadoCivil, u.bilhete_de_identidade, u.codigoPerfil, u.primeiro_acesso, p.nome AS nomePerfil, ec.estado_civil AS nomeEstadoCivil
            FROM usuario u
            INNER JOIN perfil p
            ON u.codigoPerfil = p.codigoPerfil
            INNER JOIN estado_civil ec
            ON u.codigoEstadoCivil = ec.codigoEstadoCivil
            WHERE u.codigoUsuario = '$codigoUsuario'
            LIMIT 1";
        $resultado = mysqli_query($this->conexao, $sql);
        if (!$resultado) {
            return null;
        }
        if (mysqli_num_rows($resultado) == 0) {
            return null;
        }
        $dados = mysqli_fetch_assoc($resultado);
        return new usuario($dados['codigoUsuario'], $dados['nome'], $dados['apelido'], $dados['username'], $dados['email'], $dados['codigoEstadoCivil'], $dados['nomePerfil'], $dados['nomeEstadoCivil'], $dados['genero'], $dados['bilhete_de_identidade'], $dados['contacto'], $dados['senha'], $dados['codigoPerfil'], $dados['primeiro_acesso']);
    }

    public function editar(usuario $usuario)
    {
        $codigoUsuario = $usuario->getCodigoUsuario();
        $nome = $usuario->getNome();
        $apelido = $usuario->getApelido();
        $username = $usuario->getUsername();
        $email = $usuario->getEmail();
        $estadoCivil = $usuario->getEstadoCivil();
        $genero = $usuario->getGenero();
        $bilhete = $usuario->getBilhete();
        $contacto = $usuario->getContacto();
        $senha = $usuario->getSenha();
        $codigoPerfil = $usuario->getCodigoPerfil();
        // Se foi informada uma nova senha
        if (!empty($senha)) {
            $senha = password_hash($senha, PASSWORD_DEFAULT);
            $sql = "UPDATE usuario
                    SET nome = '$nome', apelido = '$apelido', username = '$username', email = '$email', contacto = '$contacto', senha = '$senha', genero = '$genero', codigoEstadoCivil = '$estadoCivil', bilhete_de_identidade = '$bilhete', codigoPerfil = '$codigoPerfil'
                    WHERE codigoUsuario = '$codigoUsuario'";
        } else {
            // Se a senha estiver vazia,
            // mantém a senha que já existe
            $sql = "UPDATE usuario
                    SET nome = '$nome', apelido = '$apelido', username = '$username', email = '$email', contacto = '$contacto', genero = '$genero', codigoEstadoCivil = '$estadoCivil', bilhete_de_identidade = '$bilhete', codigoPerfil = '$codigoPerfil'
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

    public function listarEstadoCivil()
    {
        $sql = "SELECT codigoEstadoCivil, estado_civil
            FROM estado_civil
            ORDER BY codigoEstadoCivil";
        $resultado = mysqli_query($this->conexao, $sql);
        $estados = [];
        if ($resultado) {
            while ($dados = mysqli_fetch_assoc($resultado)) {
                $estados[] = $dados;
            }
        }
        return $estados;
    }

    public function alterarSenha($codigoUsuario, $novaSenha)
    {
        $senhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);

        $sql = "UPDATE usuario
            SET senha = '$senhaHash',
                primeiro_acesso = 0
            WHERE codigoUsuario = '$codigoUsuario'";

        return mysqli_query($this->conexao, $sql);
    }


    public function resetarSenha($codigoUsuario)
    {
        $senhaHash = password_hash(self::SENHA_PADRAO, PASSWORD_DEFAULT);

        $sql = "UPDATE usuario
            SET senha = '$senhaHash', primeiro_acesso = 1
            WHERE codigoUsuario = '$codigoUsuario'";
        return mysqli_query($this->conexao, $sql);
    }

    public function pesquisar($termo)
    {
        $usuarios = array();
        $t = mysqli_real_escape_string($this->conexao, $termo);

        $sql = "SELECT u.codigoUsuario, u.nome, u.apelido, u.username, u.email, u.contacto, u.senha, u.genero, u.codigoEstadoCivil, u.bilhete_de_identidade, u.codigoPerfil, u.primeiro_acesso, p.nome AS nomePerfil, ec.estado_civil AS nomeEstadoCivil
            FROM usuario u
            INNER JOIN perfil p ON u.codigoPerfil = p.codigoPerfil
            INNER JOIN estado_civil ec ON u.codigoEstadoCivil = ec.codigoEstadoCivil
            WHERE u.codigoUsuario LIKE '%$t%'
                OR CONCAT(u.nome, ' ', u.apelido) LIKE '%$t%'
                OR u.username LIKE '%$t%'
                OR u.email LIKE '%$t%'
                OR u.contacto LIKE '%$t%'
                OR u.genero LIKE '%$t%'
                OR u.bilhete_de_identidade LIKE '%$t%'
                OR p.nome LIKE '%$t%'
                OR ec.estado_civil LIKE '%$t%'
            ORDER BY u.codigoUsuario DESC";
        $result = mysqli_query($this->conexao, $sql);
        if ($result) {
            while ($rs = mysqli_fetch_assoc($result)) {
                $usuarios[] = new usuario($rs["codigoUsuario"], $rs["nome"], $rs["apelido"], $rs["username"], $rs["email"], $rs["codigoEstadoCivil"], $rs["nomePerfil"], $rs["nomeEstadoCivil"], $rs["genero"], $rs["bilhete_de_identidade"], $rs["contacto"], $rs["senha"], $rs["codigoPerfil"], $rs["primeiro_acesso"]);
            }
        }
        return $usuarios;
    }
}
