<?php
// Conexão com o banco de dados (substitua pelas suas configurações)
$servername = "seu_servidor";
$username = "seu_usuario";
$password = "sua_senha";
$dbname = "seu_banco_de_dados";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["nome"]) && isset($_POST["quantidade"])) {
    $nome = $_POST["nome"];
    $quantidade = $_POST["quantidade"];
    $valor = $_POST["valor"];
    $local = $_POST["local"];

    // Verifica se o item já existe no estoque
    $verifica_sql = "SELECT * FROM estoque WHERE nome = '$nome'";
    $verifica_result = $conn->query($verifica_sql);

    if ($verifica_result->num_rows > 0) {
        // Atualiza a quantidade existente
        $update_sql = "UPDATE estoque SET quantidade = quantidade + $quantidade WHERE nome = '$nome'";
        if ($conn->query($update_sql) === TRUE) {
            header("Location: gerenciar_estoque.php?msg=Quantidade adicionada ao estoque com sucesso!");
        } else {
            header("Location: gerenciar_estoque.php?msg=Erro ao atualizar a quantidade: " . $conn->error);
        }
    } else {
        // Insere um novo registro no estoque
        $inserir_sql = "INSERT INTO estoque (nome, quantidade, valor, local) VALUES ('$nome', $quantidade, $valor, $local)";
        if ($conn->query($inserir_sql) === TRUE) {
            header("Location: gerenciar_estoque.php?msg=Item adicionado ao estoque com sucesso!");
        } else {
            header("Location: gerenciar_estoque.php?msg=Erro ao adicionar o item: " . $conn->error);
        }
    }
}

$conn->close();
?>
