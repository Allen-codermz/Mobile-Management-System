<?php

$conexao = mysqli_connect("127.0.0.1", "root", "Ilovejava@123", "celular");
if (mysqli_connect_error()) {
    die("erro de conexao com a base de dados" . mysqli_connect_error() . "(" . mysqli_connect_error() . ")");
}
echo "funciona";
