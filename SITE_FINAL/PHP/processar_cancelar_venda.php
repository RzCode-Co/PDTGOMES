<?php
// Inicie a sessão
session_start();

// Verifique se o usuário está logado
if (!isset($_SESSION['id'])) {
    // Se o usuário não estiver logado, redirecione para a página de login
    header("Location: ../HTML/index.html");
    exit();
}

// Você agora pode acessar as informações do usuário a partir de $_SESSION
$idUsuario = $_SESSION['id'];
$nomeUsuario = $_SESSION['nome'];
$cargoUsuario = $_SESSION['cargo'];
$arquivo = $_SESSION['arquivo'];

// Conexão com o banco de dados
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recupere os dados do formulário
    $nome_comprador = formatarTexto($_POST["nome_comprador"]);
    $nome_peca = formatarTexto($_POST["nome_peca"]);
    $marca = formatarTexto($_POST["marca"]);
    $ano = formatarTexto($_POST["ano"]);
    $referencia = formatarTexto($_POST["referencia"]);
    $aplicacao = formatarTexto($_POST["aplicacao"]);
    $cpf_cnpj = $_POST["cpf_cnpj2"];

    // Verifique se a escolha do usuário (CPF ou CNPJ) é válida
    if ($_POST["cpf_cnpj2"] == "CPF2") {
        $CPF = $_POST["CPF2"];
        $CNPJ = 0; // Defina CNPJ como nulo
    } elseif ($_POST["cpf_cnpj2"] == "CNPJ2") {
        $CNPJ = $_POST["CNPJ2"];
        $CPF = 0; // Defina CPF como nulo
    } else {
        // Trate o caso em que nenhum dos campos foi escolhido
        echo '<script>
            alert("Escolha CPF ou CNPJ.");
            window.location.href = "../PHP/Venda.php";
        </script>';
        exit; // Saia do script
    }
    $funcionario_vendedor = formatarTexto($_POST["funcionario_vendedor"]);

    function formatarTexto($texto) {
        // Remove a acentuação
        $texto = iconv('UTF-8', 'ASCII//TRANSLIT', $texto);

        // Converte para maiúsculas
        $texto = mb_strtoupper($texto, 'UTF-8');

        return $texto;
    }


    // Consulta SQL para obter o ID da venda com base nos dados do formulário
    $consulta_id_venda = "SELECT id, quantidade FROM vendas WHERE nome_comprador = '$nome_comprador' AND nome_peca = '$nome_peca' AND CPF ='$CPF' AND CNPJ ='$CNPJ' AND funcionario_vendedor ='$funcionario_vendedor'";


    $resultado = $conn->query($consulta_id_venda);

    if ($resultado->num_rows > 0) {
        // Obtém o ID da venda a partir do resultado da consulta
        $row = $resultado->fetch_assoc();
        $venda_id = $row["id"];
        $quantidade = $row["quantidade"];
        // Agora você tem o ID da venda que deseja cancelar
        // Faça a exclusão da venda e dos valores com base no ID como mostrado no exemplo anterior

        // Consulta SQL para excluir a venda da tabela "vendas" com base no ID
        $sql_excluir_venda = "DELETE FROM vendas WHERE id = $venda_id";

        // Consulta SQL para excluir valores da tabela "valores" com base no ID da venda (id_op)
        $sql_excluir_valores = "DELETE FROM valores WHERE id_op = $venda_id";

        // Execute as consultas SQL de exclusão
        if ($conn->query($sql_excluir_venda) === TRUE && $conn->query($sql_excluir_valores) === TRUE) {
            // Consulta SQL para atualizar a quantidade no estoque
            $sql_atualizar_estoque = "UPDATE estoque SET quantidade = quantidade + $quantidade WHERE nome = '$nome_peca' AND marca = '$marca' AND ano = '$ano' AND referencia = '$referencia' AND aplicacao = '$aplicacao'";

            // Execute a consulta SQL de atualização do estoque
            if ($conn->query($sql_atualizar_estoque) === TRUE) {
                echo '<script>alert("Venda cancelada com sucesso.");window.location.href = "../PHP/Venda.php";</script>';
            } else {
                echo "Erro ao atualizar o estoque: " . $conn->error;
            }
        } else {
                echo "Erro ao cancelar a venda: " . $conn->error;
        }
    } else {
        echo "Venda não encontrada com os dados fornecidos.";
    }
}

// Feche a conexão com o banco de dados
$conn->close();
?>