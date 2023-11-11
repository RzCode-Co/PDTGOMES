<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

function removeAcentos($string) {
    return preg_replace('/[^\p{L}\p{N}\s]/u', '', strtoupper($string));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Inicialize a variável de SQL
    $sql = "";

    // Verifica se os campos obrigatórios estão preenchidos
    if (isset($_POST['nome'], $_POST['senha'], $_POST['cargo'], $_POST['CPF'])) {
        // Coleta os dados do formulário
        $nome = strtoupper(removeAcentos($_POST['nome']));
        $senha = $_POST['senha'];
        $cargo = $_POST['cargo'];
        $cpf = $_POST['CPF']; // Variável para representar o CPF

        // Usuário escolheu CPF
        $sql = "SELECT id, nome, cargo, arquivo FROM usuarios WHERE nome = ? AND senha = ? AND cargo = ? AND cpf_cnpj = 'CPF' AND CPF = ?";

        if (!empty($sql)) {
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $nome, $senha, $cargo, $cpf);
            $stmt->execute();
            $stmt->store_result();
            $contar = $stmt->num_rows;

            if ($contar >= 1) {
                $stmt->bind_result($id, $nome, $cargo, $arquivo);
                $stmt->fetch();
                $stmt->close();

                // Inicie a sessão
                session_start();

                // Armazene informações do usuário na sessão
                $_SESSION['id'] = $id;
                $_SESSION['nome'] = $nome;
                $_SESSION['cargo'] = $cargo;
                $_SESSION['arquivo'] = $arquivo;

                // Redirecione para a página "inicio.php"
                echo '<script>
                    alert("Login feito com sucesso.");
                    window.location.href = "../PHP/inicio.php";
                    </script>';
            } else {
                echo '<script> alert("Credenciais inválidas."); window.location.href = "../HTML/index.html";</script>';
            }
        }
    } else {
        echo '<script> alert("Faltou preencher campos."); window.location.href = "../HTML/index.html";</script>';
    }
}
?>
