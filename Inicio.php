<?php
session_start();

//Traz as informações do login pra ca
$nomeUsuario = $_SESSION['nome'];
$cargoUsuario = $_SESSION['cargo'];
$fotoUsuario = $_SESSION['foto']; // Caminho da imagem
?>
