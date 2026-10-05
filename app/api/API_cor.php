<?php
function obterDescricaoCor($cor){
    $cor = ltrim($cor, '#');
    $url = "https://www.thecolorapi.com/id?hex=" . $cor;
    $resposta = file_get_contents($url);
    if ($resposta === false) {
        return false;
    }
    $dados = json_decode($resposta, true);
    if ($dados === null) {
        return false;
    }
    if (!isset($dados["name"]["value"])) {
        return false;
    }
    return $dados["name"]["value"];
}
