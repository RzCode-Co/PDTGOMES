<?php

$servidor = "localhost";
$usuario = "autop317_pdtoficial";
$senha = "sistemapaulodetasso";
$dbname = "autop317_pdt";

$conn = mysqli_connect($servidor, $usuario, $senha, $dbname);

if(!$conn){
    die("Falha na conexao: ".mysqli_connect_error());
}

?>
