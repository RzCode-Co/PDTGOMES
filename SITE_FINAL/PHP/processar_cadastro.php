<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

function removeAcentos($string) {
    return preg_replace('/[^\p{L}\p{N}\s]/u', '', strtoupper($string));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se os campos obrigatórios estão preenchidos
    if (isset($_POST['nome'], $_POST['endereco'])) {
        // Validação e sanitização dos campos de entrada
        $nome = removeAcentos(filter_var($_POST['nome'], FILTER_SANITIZE_STRING));
        $endereco = removeAcentos(filter_var($_POST['endereco'], FILTER_SANITIZE_STRING));
        $cargo = "cliente";
        $cpf_cnpj = filter_var($_POST['cpf_cnpj'], FILTER_SANITIZE_STRING);
        // Verifique se a escolha do usuário (CPF ou CNPJ) é válida
        if ($_POST["cpf_cnpj"] == "CPF") {
            $CPF = filter_var($_POST["CPF"], FILTER_SANITIZE_STRING);
            $CNPJ = 0; // Define CNPJ como nulo
        } elseif ($_POST["cpf_cnpj"] == "CNPJ") {
            $CNPJ = filter_var($_POST["CNPJ"], FILTER_SANITIZE_STRING);
            $CPF = 0; // Define CPF como nulo
        } else {
            // Trate o caso em que nenhum dos campos foi escolhido
            echo '<script>
                alert("Escolha CPF ou CNPJ.");
                window.location.href = "../HTML/cadastro.html";
            </script>';
            exit; // Saia do script
        }

        // Verifica se um arquivo foi enviado
        if ($_FILES["arquivo"]["size"] > 0) {
            $diretorioDestino = "../img_clientes/"; // Diretório onde os arquivos serão armazenados
            $nomeArquivo = $_FILES["arquivo"]["name"];
            $caminhoCompleto = $diretorioDestino . $nomeArquivo;
            if (move_uploaded_file($_FILES["arquivo"]["tmp_name"], $caminhoCompleto)) {
                // Arquivo movido com sucesso
            } else {
                // Erro ao mover o arquivo
                echo '<script> alert("Erro ao mover o arquivo."); window.location.href = "../HTML/cadastro.html";</script>';
                exit;
            }
        } else {
            // Nenhum arquivo enviado
            $caminhoCompleto = null; // ou $caminhoCompleto = "";
        }

        // Instrução SQL segura usando prepared statement
        $stmt = $conn->prepare("INSERT INTO usuarios (nome, cargo, cpf_cnpj, CPF, CNPJ, arquivo, endereco) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $nome, $cargo, $cpf_cnpj, $CPF, $CNPJ, $caminhoCompleto, $endereco);
        $stmt->execute();
        $stmt->store_result();
        $contar = $stmt->num_rows;

        if ($contar >= '1') {
            echo '<script> alert("Usuario já tem cadastro."); window.location.href = "../HTML/cadastro.html";</script>';
        } else {
            echo '<script> alert("Cadastro realizado com sucesso."); window.location.href = "../HTML/index.html";</script>';
        }
    } else {
        echo '<script> alert("Campos Obrigatórios não preenchido."); window.location.href = "../HTML/cadastro.html";</script>';
    }
} else {
    echo '<script> alert("Acesso Negado."); window.location.href = "../HTML/cadastro.html";</script>';
}
?>
