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

require_once "config.php";

// Número de clientes por página
$items_per_page = 10;

$sql = "SELECT id, nome, cpf, cnpj FROM usuarios WHERE cargo = 'cliente'";
$result = $conn->query($sql);

// Total de resultados
$total_results = $result->num_rows;

// Número total de páginas
$total_pages = ceil($total_results / $items_per_page);

// Página atual
if (isset($_GET['page']) && is_numeric($_GET['page'])) {
    $current_page = intval($_GET['page']);
} else {
    $current_page = 1;
}

// Calcula o índice de início e fim dos resultados
$index_start = ($current_page - 1) * $items_per_page;
$index_end = $index_start + $items_per_page;

// Reexecute a consulta com LIMIT para obter os resultados da página atual
$sql .= " LIMIT $index_start, $items_per_page";
$result = $conn->query($sql);

?>

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
                    echo "<a href='gerar_boleto.php?CNPJ=$clienteCNPJ&CPF=$clienteCPF'>Gerar NFE</a>";
                }
                ?>
            </tbody>
        </table>

        <?php
        // Exibir links de páginação
        echo "<div class='pagination'>";
        if ($total_pages > 1) {
            for ($i = 1; $i <= $total_pages; $i++) {
                if ($i == $current_page) {
                    echo "<span class='current'>$i</span>";
                } else {
                    echo "<a href='?page=$i'>$i</a>";
                }
            }
        }
        echo "</div>";
        ?>

    <?php
    } else {
        echo "<p>Nenhum cliente encontrado.</p>";
    }

    $conn->close();
    ?>
</body>

</html>
