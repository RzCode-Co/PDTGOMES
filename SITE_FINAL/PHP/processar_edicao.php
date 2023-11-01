<?php
require_once "config.php"; // arquivo de configuração do banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST["id"]) ? intval($_POST["id"]) : 0; // O ID do produto a ser editado

    if ($id <= 0) {
        echo "ID do produto inválido.";
        exit();
    }

    // Inicialize as variáveis para os campos que podem ser atualizados
    $updateFields = array();
    $bindTypes = "";
    $bindValues = array();

    // Função para validar e limpar entradas
    function validarEntrada($entrada) {
        $entrada = trim($entrada);  // Remover espaços em branco
        $entrada = stripslashes($entrada);  // Remover barras invertidas
        $entrada = htmlspecialchars($entrada);  // Evitar ataques XSS
        return $entrada;
    }

    // Valide e sanitize os campos e adicione-os à atualização
    if (isset($_POST["quantidade"]) && is_numeric($_POST["quantidade"])) {
        $updateFields[] = "quantidade = ?";
        $bindTypes .= "d";
        $bindValues[] = doubleval($_POST["quantidade"]);
    }

    if (isset($_POST["valorVarejo"]) && is_numeric($_POST["valorVarejo"])) {
        $updateFields[] = "valor_varejo = ?";
        $bindTypes .= "d";
        $bindValues[] = doubleval($_POST["valorVarejo"]);
    }

    if (isset($_POST["valorAtacado"]) && is_numeric($_POST["valorAtacado"])) {
        $updateFields[] = "valor_atacado = ?";
        $bindTypes .= "d";
        $bindValues[] = doubleval($_POST["valorAtacado"]);
    }

    // Valide e sanitize o novo nome do produto
    $novoNome = validarEntrada($_POST["nome"]);
    if (!empty($novoNome)) {
        $updateFields[] = "nome = ?";
        $bindTypes .= "s";
        $bindValues[] = $novoNome;
    }

    // Construa a consulta SQL apenas com os campos a serem atualizados
    if (!empty($updateFields)) {
        $sql = "UPDATE estoque SET " . implode(", ", $updateFields) . " WHERE id = ?";

        $stmt = $conn->prepare($sql);

        // Adicione o tipo dos valores a serem vinculados
        $bindTypes .= "i";
        $bindValues[] = $id;

        // Vincule os valores aos marcadores na consulta
        $stmt->bind_param($bindTypes, ...$bindValues);

        if ($stmt->execute()) {
            // Os dados foram atualizados com sucesso
            header("Location: estoque.php"); // Redirecionar de volta para a página de estoque ou para onde desejar
            exit();
        } else {
            echo "Erro ao atualizar os dados no banco de dados.";
        }

        $stmt->close();
    }
}

// Feche a conexão com o banco de dados
$conn->close();
?>
