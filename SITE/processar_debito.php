<?php
// Conexão com o banco de dados
require_once "config.php";

// Verifique se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifique se todos os campos obrigatórios foram preenchidos
    if (isset($_POST["data_debito"]) && isset($_POST["nome"]) && isset($_POST["tipo"]) && isset($_POST["descricao"]) && isset($_FILES["arquivo"])) {
        
        // Configurações para o arquivo
        $diretorioDestino = "uploads/"; // Diretório onde os arquivos serão armazenados
        $nomeArquivo = $_FILES["arquivo"]["name"];
        $caminhoCompleto = $diretorioDestino . $nomeArquivo;

        // Move o arquivo enviado para o diretório de destino
        if (move_uploaded_file($_FILES["arquivo"]["tmp_name"], $caminhoCompleto)) {
            // Dados do formulário
            $data_debito = $_POST["data_debito"];
            $nome = $_POST["nome"];
            $valor_debito = $_POST["valor_debito"];
            $tipo = $_POST["tipo"];
            $descricao = $_POST["descricao"];

            // Consulta SQL para inserir os dados na tabela "debitos"
            $sql = "INSERT INTO debitos (data_debito, nome, valor_debito, tipo, descricao, arquivo) VALUES ('$data_debito', '$nome', '$valor_debito', '$tipo', '$descricao', '$caminhoCompleto')";

            if ($conn->query($sql) === TRUE) {
                echo "<script>alert('Dados inseridos com sucesso.');</script>";
            } else {
                echo "<script>alert('Erro ao inserir os dados: " . $conn->error . ");</script>";
            }
        } else {
            echo "<script>alert('Erro ao fazer o upload do arquivo.');</script>";
        }
    } else {
        echo "<script>alert('Todos os campos do formulário devem ser preenchidos.');</script>";
    }
}

$conn->close();
?>
<script>
    // Redireciona para a página "estoque.html" após o pop-up
    window.location.href = "debitos.html";
</script>