<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once __DIR__ . '/../../auth/log.php';

if (isset($_SESSION['codigoUsuario'])) {

    registrarLog("LOGOUT", "Terminou a sessão");
}
// Limpar todas as variáveis da sessão
$_SESSION = [];
// Destruir a sessão
session_destroy();
// Impedir cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");
// Voltar para o login
header("Location: login.php");
exit;
