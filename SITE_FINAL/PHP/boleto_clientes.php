<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Boleto do Cliente</title>
</head>
<body>
    <h1>Boleto do Cliente</h1>
    <?php
    // Conecte-se ao banco de dados ou inclua seu arquivo de configuração do banco de dados
    require_once "config.php";

    $ano = date('Y');

    // Consulta para obter os dados dos clientes
    $sql = "SELECT id, nome, cpf, cnpj FROM usuarios WHERE cargo = 'cliente'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $clienteId = $row['id'];
            $clienteNome = $row['nome'];
            $clienteCPF = $row['cpf'];
            $clienteCNPJ = $row['cnpj'];

            echo "<p>Nome: $clienteNome</p>";

            if ($clienteCPF !== '0') {
                echo "<p>CPF: $clienteCPF</p>";
            } elseif ($clienteCNPJ !== '0') {
                echo "<p>CNPJ: $clienteCNPJ</p>";
            }
            // Adicione o campo hidden para armazenar o clienteId
            echo "<form action='download_boleto_cliente.php' method='post'>";
            echo "<input type='hidden' name='CPF' value='$clienteCPF'>";
            echo "<input type='hidden' name='CNPJ' value='$clienteCNPJ'>";
            echo "<label for='mes'>Selecione o mês:</label>";
            echo "<select name='mes' id='mes'>";
            echo "<option value='1'>Janeiro</option>";
            echo "<option value='2'>Fevereiro</option>";
            echo "<option value='3'>Março</option>";
            echo "<option value='4'>Abril</option>";
            echo "<option value='5'>Maio</option>";
            echo "<option value='6'>Junho</option>";
            echo "<option value='7'>Julho</option>";
            echo "<option value='8'>Agosto</option>";
            echo "<option value='9'>Setembro</option>";
            echo "<option value='10'>Outubro</option>";
            echo "<option value='11'>Novembro</option>";
            echo "<option value='12'>Dezembro</option>";
            echo "</select>";
            echo"<input type='hidden' name='ano' value='$ano'>";
            echo "<input type='submit' value='Gerar Boleto'>";
            echo "</form>";

            echo "<hr>";
        }
    } else {
        echo "Nenhum cliente encontrado.";
    }

    // Feche a conexão com o banco de dados (lembre-se de ajustar isso de acordo com sua configuração)
    $conn->close();
    ?>
</body>
</html>