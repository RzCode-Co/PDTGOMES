<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica se os campos obrigatórios estão preenchidos
    if (isset($_POST['nome'], $_POST['senha'], $_POST['cargo'])) {
        // Coleta os dados do formulário
        $nome = $_POST['nome'];
        $senha = $_POST['senha'];
        $cargo = $_POST['cargo'];
        $cpf_cnpj = $_POST['cpf_cnpj'];

        // Verifique se a escolha do usuário (CPF ou CNPJ) é válida
        if ($_POST["cpf_cnpj"] == "CPF") {
            $cpf_cnpj = $_POST["CPF"];
            $CNPJ = 0; // Defina CNPJ como nulo
        } elseif ($_POST["cpf_cnpj"] == "CNPJ") {
            $cpf_cnpj = $_POST["CNPJ"];
            $CPF = 0; // Defina CPF como nulo
        } else {
            echo '<script>
            alert("Escolha CPF ou CNPJ.");
            window.location.href = "../HTML/index.html";
            </script>';
            exit;
        }

        // Utilize instruções preparadas para evitar injeção SQL
        $sql = "SELECT id FROM usuarios WHERE nome = ? AND senha = ? AND cargo = ? AND cpf_cnpj = ? AND CPF = ? AND CNPJ = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssss", $nome, $senha, $cargo, $cpf_cnpj, $CPF, $CNPJ);
        $stmt->execute();
        $stmt->store_result();
        $contar = $stmt->num_rows;

        if ($contar >= 1) {
            $stmt->bind_result($idUsuario);
            $stmt->fetch();
            $stmt->close();

            // Redirecione para a página "inicio.php" com o ID do usuário como parâmetro
            echo '<script>
                alert("Login feito com sucesso.");
                window.location.href = "../PHP/inicio.php?id=' . $idUsuario . '";
                </script>';
        } else {
            echo '<script> alert("Credenciais inválidas."); window.location.href = "../HTML/index.html";</script>';
        }
    } else {
        echo '<script> alert("Faltou preencher campos."); window.location.href = "../HTML/index.html";</script>';
    }
}
?>
