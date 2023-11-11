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

function removeAcentos($string) {
    return preg_replace('/[^\p{L}\p{N}\s]/u', '', strtoupper($string));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifique se todos os campos obrigatórios foram preenchidos
    if (isset($_POST["data_debito"]) && isset($_POST["nome"]) && isset($_POST["tipo"]) && isset($_POST["descricao"]) && isset($_FILES["arquivo"])) {
        
        // Configurações para o arquivo
        $diretorioDestino = "../uploads/"; // Diretório onde os arquivos serão armazenados
        $nomeArquivo = $_FILES["arquivo"]["name"];
        $caminhoCompleto = $diretorioDestino . $nomeArquivo;

        // Move o arquivo enviado para o diretório de destino
        if (move_uploaded_file($_FILES["arquivo"]["tmp_name"], $caminhoCompleto)) {
            // Dados do formulário
            $data_debito = $_POST["data_debito"];
            $nome = removeAcentos($_POST["nome"]);
            $valor_debito = $_POST["valor_debito"];
            $tipo = removeAcentos($_POST["tipo"]);
            $descricao = removeAcentos($_POST["descricao"]);

            // Consulta SQL para inserir os dados na tabela "debitos"
            $sql = "INSERT INTO debitos (data_debito, nome, valor_debito, tipo, descricao, arquivo) VALUES ('$data_debito', '$nome', '$valor_debito', '$tipo', '$descricao', '$caminhoCompleto')";

            if ($conn->query($sql) === TRUE) {
                $debito_id = mysqli_insert_id($conn);
                echo "<script>alert('Dados inseridos com sucesso.');</script>";
            } else {
                echo "<script>alert('Erro ao inserir os dados: " . $conn->error . ");</script>";
            }
            $preco_total_produtos = NULL;
            $preco_total_servicos = NULL;
            $preco_total_geral = NULL;

            $sql = "INSERT INTO valores (id_op, data_abertura, valor_venda, valor_servico, preco_total_geral, valor_debito) VALUES('$debito_id', '$data_debito','$preco_total_produtos', '$preco_total_servicos', '$preco_total_geral', '$valor_debito')";
            if ($conn->query($sql) === TRUE) {
                echo "valores atualizados.";
            } else {
                echo "Erro ao atualizar valor de venda: " . $conn->error;
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
    window.location.href = "debitos.php";
</script>