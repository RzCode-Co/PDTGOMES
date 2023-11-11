<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Boleto do Cliente</title>
    <link rel="stylesheet" href="../CSS/nfe_clientes.css">
</head>

<body>
    <div class="titulo_icone">
        <a id="icone_voltar" href="../PHP/Debitos.php"><img src="../CSS/img/voltar.svg" alt="voltar página"></a>
        <h1>NFE CLIENTES</h1>
    </div>
    <?php
    require_once "config.php";

    $sql = "SELECT id, nome, cpf, cnpj FROM usuarios WHERE cargo = 'cliente'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        ?>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>CPF/CNPJ</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                while ($row = $result->fetch_assoc()) {
                    $clienteId = $row['id'];
                    $clienteNome = $row['nome'];
                    $clienteCPF = $row['cpf'];
                    $clienteCNPJ = $row['cnpj'];

                    echo "<tr>";
                    echo "<td>$clienteNome</td>";

                    if ($clienteCPF !== '0') {
                        echo "<td>$clienteCPF</td>";
                    } elseif ($clienteCNPJ !== '0') {
                        echo "<td>$clienteCNPJ</td>";
                    }

                    echo "<td>";
                    echo "<form action='gerar_boleto.php' method='post'>";
                    echo "<input type='hidden' name='clienteCPF' value='$clienteCPF'>";
                    echo "<input type='hidden' name='clienteCNPJ' value='$clienteCNPJ'>";
                    echo "<label for='mes'>Selecione o mês:</label>";
                    echo "<select name='mes'>";
                    $meses = [
                        'Janeiro',
                        'Fevereiro',
                        'Março',
                        'Abril',
                        'Maio',
                        'Junho',
                        'Julho',
                        'Agosto',
                        'Setembro',
                        'Outubro',
                        'Novembro',
                        'Dezembro'
                    ];
                    foreach ($meses as $key => $mes) {
                        $numeroMes = $key + 1;
                        echo "<option value='$numeroMes'>$mes</option>";
                    }
                    echo "</select>";
                    echo "<input type='submit' value='Gerar NFE'>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>

        <?php
    } else {
        echo "<p>Nenhum cliente encontrado.</p>";
    }

    $conn->close();
    ?>
</body>

</html>