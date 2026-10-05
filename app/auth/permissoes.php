<?php

/*
|--------------------------------------------------------------------------
| PERFIS
|--------------------------------------------------------------------------
|
| 1 = operador
| 2 = superOperador
| 3 = administrador
| 4 = auditor
|
*/


// Verificar se existe um usuário autenticado
if (!isset($_SESSION['codigoPerfil'])) {
    header("Location: login.php");
    exit;
}


// Obter o perfil do usuário logado
$codigoPerfil = (int) $_SESSION['codigoPerfil'];


// ---------------------------------------------------------
// LISTAR
// Todos os perfis podem listar
// ---------------------------------------------------------
function podeListar()
{
    return true;
}


// ---------------------------------------------------------
// ADICIONAR
// Todos os perfis podem adicionar
// ---------------------------------------------------------
function podeAdicionar()
{
    return in_array($_SESSION['codigoPerfil'], [1, 2, 3, 4]);
}


// ---------------------------------------------------------
// EDITAR
// Operador NÃO pode editar
// SuperOperador, Administrador e Auditor podem
// ---------------------------------------------------------
function podeEditar()
{
    return in_array($_SESSION['codigoPerfil'], [2, 3, 4]);
}


// ---------------------------------------------------------
// APAGAR
// Operador NÃO pode apagar
// SuperOperador, Administrador e Auditor podem
// ---------------------------------------------------------
function podeApagar()
{
    return in_array($_SESSION['codigoPerfil'], [2, 3, 4]);
}


// ---------------------------------------------------------
// GERIR USUÁRIOS
// Apenas Administrador
// ---------------------------------------------------------
function podeGerirUsuarios()
{
    return $_SESSION['codigoPerfil'] == 3;
}


// ---------------------------------------------------------
// VER LOGS
// Apenas Auditor
// ---------------------------------------------------------
function podeVerLogs()
{
    return $_SESSION['codigoPerfil'] == 4;
}

function exigirGestaoUsuarios()
{
    if (!podeGerirUsuarios()) {
        http_response_code(403);
        die("Acesso negado. Você não tem permissão para gerir usuários.");
    }
}

function exigirLogs()
{
    if (!podeVerLogs()) {
        http_response_code(403);
        die("Acesso negado. Você não tem permissão para acessar os logs.");
    }
}