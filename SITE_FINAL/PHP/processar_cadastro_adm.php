<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

function removeAcentos($string) {
    return preg_replace('/[^\p{L}\p{N}\s]/u', '', strtoupper($string));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se os campos obrigatórios estão preenchidos
    if (isset($_POST['nome'], $_POST['senha'], $_POST['cargo'])) {
        // Validação e sanitização dos campos de entrada
        $nome = removeAcentos(filter_var($_POST['nome'], FILTER_SANITIZE_STRING));
        $senha = filter_var($_POST['senha'], FILTER_SANITIZE_STRING);
        $cargo = filter_var($_POST['cargo'], FILTER_SANITIZE_STRING);
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
                window.location.href = "../HTML/cadastro_adm.html";
            </script>';
            exit; // Saia do script
        }

        $diretorioDestino = null;
        $caminhoCompleto = null;

        if (isset($_FILES["arquivo"]) && $_FILES["arquivo"]["size"] > 0) {
            if ($_POST["cargo"] == "vendedor") {
                $diretorioDestino = "../img_vendedores/"; // Diretório onde os arquivos serão armazenados
            } elseif ($_POST["cargo"] == "admin") {
                $diretorioDestino = "../img_adm/"; // Diretório onde os arquivos serão armazenados
            }
            $nomeArquivo = $_FILES["arquivo"]["name"];
            $caminhoCompleto = $diretorioDestino . $nomeArquivo;
            if (move_uploaded_file($_FILES["arquivo"]["tmp_name"], $caminhoCompleto)) {
                // Arquivo movido com sucesso
            } else {
                // Erro ao mover o arquivo
                echo '<script> alert("Erro ao mover o arquivo."); window.location.href = "../HTML/cadastro_adm.html";</script>';
                exit;
            }
        }
        
        // Instrução SQL segura usando prepared statement
        $stmt = $conn->prepare("SELECT id FROM usuarios WHERE nome = ? AND senha = ? AND cargo = ? AND cpf_cnpj = ? AND CPF = ? AND CNPJ = ?");
        $stmt->bind_param("ssssss", $nome, $senha, $cargo, $cpf_cnpj, $CPF, $CNPJ);
        $stmt->execute();
        $stmt->store_result();
        $contar = $stmt->num_rows;

        if ($contar >= '1'){
            echo '<script> alert("Usuario já tem cadastro."); window.location.href = "../HTML/cadastro_adm.html";</script>';
        } else {
            $stmt->close();

            // Inserção segura no banco de dados usando prepared statement
            $stmt = $conn->prepare("INSERT INTO usuarios (nome, senha, cargo, cpf_cnpj, CPF, CNPJ, arquivo) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssssss", $nome, $senha, $cargo, $cpf_cnpj, $CPF, $CNPJ, $caminhoCompleto);
            if ($stmt->execute()) {
                echo '<script> alert("Cadastro realizado com sucesso."); window.location.href = "../HTML/index.html";</script>';
            } else {
                echo '<script> alert("Erro ao salvar no banco de dados."); window.location.href = "../HTML/cadastro_adm.html";</script>';
            }
        }
    } else {
        echo '<script> alert("Campos Obrigatórios não preenchido."); window.location.href = "../HTML/cadastro_adm.html";</script>';
    }
} else {
    echo '<script> alert("Acesso Negado."); window.location.href = "../HTML/cadastro_adm.html";</script>';
}
?>
