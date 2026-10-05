<?php

require_once __DIR__ . '/../model/Logs.php';
require_once __DIR__ . '/../controller/ControllerLogs.php';
require_once __DIR__ . '/../config/conexao.php';

function registrarLog($acao, $descricao)
{
    global $conexao;
    if (!isset($_SESSION['codigoUsuario'])) {
        return false;
    }
    $codigoUsuario = $_SESSION['codigoUsuario'];
    $log = new Logs(null, $codigoUsuario, $acao, $descricao);
    $controllerLog = new ControllerLog($conexao);
    return $controllerLog->criar($log);
}
