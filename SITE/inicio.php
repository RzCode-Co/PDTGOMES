<?php
session_start();

//Traz as informações do login pra ca
$nomeUsuario = $_SESSION['nome'];
$cargoUsuario = $_SESSION['cargo'];
$fotoUsuario = $_SESSION['foto']; // Caminho da imagem
?>

<?php
// Puxa os parametros da pesquisa
$termoPesquisa = $_GET['termo'];
$filtroPesquisa = $_GET['filtro'];

// Crie uma consulta base
$consulta = "SELECT * FROM tabela WHERE 1"; // WHERE 1 é usado para garantir que todos os registros sejam considerados inicialmente

// Adicione as condições de filtro de acordo com a escolha do usuário
if (!empty($termoPesquisa)) {
    $consulta .= " AND $filtroPesquisa LIKE '%$termoPesquisa%'";
}

// Exibe a consulta do bancario de dados
// ...
?>
