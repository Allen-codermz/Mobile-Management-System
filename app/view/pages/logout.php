<?php

session_start();

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