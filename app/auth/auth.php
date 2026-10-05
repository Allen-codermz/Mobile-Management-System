<?php
session_start();

// Impedir que o navegador guarde páginas protegidas em cache
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");

// Verificar se o usuário está autenticado
if (!isset($_SESSION['codigoUsuario'])) {
    header("Location: login.php");
    exit;
}

?>