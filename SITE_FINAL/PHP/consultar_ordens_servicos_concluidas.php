<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

// Defina o número máximo de registros por página
$registrosPorPagina = 6;

// Recupere o número da página atual a partir da consulta GET
if (isset($_GET['pagina'])) {
    $paginaAtual = $_GET['pagina'];
} else {
    $paginaAtual = 1;
}

// Calcule o deslocamento a partir da página atual
$deslocamento = ($paginaAtual - 1) * $registrosPorPagina;

// Consulta SQL para recuperar as ordens de serviço concluídas com base no deslocamento
$sqlConcluidas = "SELECT * FROM ordem_servico_completa WHERE status = 'Concluída' LIMIT $registrosPorPagina OFFSET $deslocamento";
$resultConcluidas = $conn->query($sqlConcluidas);

if ($resultConcluidas->num_rows > 0) {
    $os_details = array(); // Inicializa um array para armazenar os detalhes da ordem de serviço

    while ($row = $resultConcluidas->fetch_assoc()) {
        // Armazena cada linha no array de detalhes da ordem de serviço
        $os_details[] = $row;
    }
} else {
    echo "<p>Nenhuma Ordem de Serviço concluída encontrada.</p>";
}

// Calcule o número total de registros para as ordens de serviço concluídas
$sqlTotalRegistrosConcluidas = "SELECT COUNT(*) AS total FROM ordem_servico_completa WHERE status = 'Concluída'";
$resultTotalRegistrosConcluidas = $conn->query($sqlTotalRegistrosConcluidas);
$totalRegistrosConcluidas = $resultTotalRegistrosConcluidas->fetch_assoc()['total'];

// Calcule o número total de páginas com base no total de registros das ordens de serviço concluídas
$totalPaginas = ceil($totalRegistrosConcluidas / $registrosPorPagina);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Criação/Consulta de OS</title>
    <link rel="stylesheet" href="../CSS/pagina_inicial.css">
    <link rel="stylesheet" href="../CSS/consultar_ordens_geral.css">
</head>


<body>


    <!-- Menu horizonatl -->
    <nav class="menu_horizontal">
        <ul>
            <li id="logo_menu_horizontal"><a href="../HTML/pagina_incial.html"><img src="../CSS/img/Logo Horizontal.png"
                        alt="logo da empresa"></a>
            </li>

            <li id="direita">

                <!-- Perfil -->
                <div class="image_container">
                    <img src="../CSS/img/editar.png" alt="insira foto de perfil" id="img_photo">
                </div>

                <!-- Escolhendo Imagem -->
                <input type="file" id="file_image" name="file_image" accept="image/*">

                <script src="../JS/login_preview.js"></script>
            </li>

            <li id="direita">
                <!-- Cargo e nome -->
                <div class="cargo_nome">
                    <h3>Cargo</h3>
                    <p>Nome e Sobrenome</p>
                </div>
            </li>

            <li id="direita"><a href="#"><img src="../CSS/img/Sino_menu_horizontal.svg" alt="Notificações"></a></li>

        </ul>

    </nav>

    <div id="ordens-concluidas">
        <div class="titulo_icone">
            <a id="icone_voltar" href="../PHP/Criação OS.php"><img src="../CSS/img/voltar.svg" alt="voltar página"></a>
            <h1>Ordens concluídas</h1>
        </div>

        <div id="pesquisa_placa">
            <form method="GET">
                <label>Pesquisar por Placa do Veículo:</label>
                <input type="text" name="veiculo_placa">
                <input type="submit" value="Pesquisar">
            </form>
        </div>


        <?php
        require_once "config.php"; // Arquivo de configuração do banco de dados
        
        // Verifique se o campo de pesquisa está preenchido
        if (isset($_GET['veiculo_placa'])) {
            $veiculoPlaca = $_GET['veiculo_placa'];
            // Consulta SQL para recuperar a ordem de serviço com a placa do veículo especificada
            $sql = "SELECT * FROM ordem_servico_completa WHERE veiculo_placa = '$veiculoPlaca' AND status = 'Concluída'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                $os_details = array(); // Inicializa um array para armazenar os detalhes da ordem de serviço
        
                while ($row = $result->fetch_assoc()) {
                    // Armazena a ordem de serviço encontrada no array de detalhes da ordem de serviço
                    $os_details[] = $row;
                }
            } else {
                echo "<p>Nenhuma Ordem de Serviço encontrada com a placa do veículo especificada.</p>";
            }
        }
        ?>

        <div id="div_ordens_concluidas">
            <?php
            if (!empty($os_details)) {
                foreach ($os_details as $os) {
                    if ($os['status'] != 'Concluída') {
                        // Ignorar ordens com status diferente de "Concluída"
                        continue;
                    }
                    echo "<div class='ordem_servico'>";
                    echo "<h3>Ordem de Serviço ID: {$os['ordem_servico_id']}</h3>";
                    echo "<table>";
                    echo "<tr><th>ID</th><td>{$os['ordem_servico_id']}</td></tr>";
                    echo "<tr><th>Cliente</th><td>{$os['cliente_nome']}</td></tr>";
                    echo "<tr><th>Veículo</th><td>{$os['veiculo_nome']}</td></tr>";
                    echo "<tr><th>Placa do Veículo</th><td>{$os['veiculo_placa']}</td></tr>";
                    echo "<tr><th>Data de Abertura</th><td>{$os['data_abertura']}</td></tr>";
                    echo "<tr><th>Status</th><td>{$os['status']}</td></tr>";
                    echo "</table>";
                    echo "</div>";
                }
            }
            ?>
        </div>

        <div class="paginacao_detalhes">
            <div class="paginacao">
                <?php
                // Exibir links de paginação apenas se houver mais de uma página
                if ($totalPaginas > 1) {
                    // Link para a página anterior
                    if ($paginaAtual > 1) {
                        echo "<a href='?pagina=" . ($paginaAtual - 1) . "' class='pagina-anterior'>&laquo;</a>";
                    }

                    // Links para as páginas intermediárias
                    $quantidadeLinks = 5; // Quantidade de links visíveis
                    $inicio = max(1, $paginaAtual - floor($quantidadeLinks / 2));
                    $fim = min($totalPaginas, $paginaAtual + floor($quantidadeLinks / 2));

                    for ($i = $inicio; $i <= $fim; $i++) {
                        if ($paginaAtual == $i) {
                            echo "<span class='pagina-atual'>$i</span>";
                        } else {
                            echo "<a href='?pagina=$i' class='pagina'>$i</a>";
                        }
                    }

                    // Link para a próxima página
                    if ($paginaAtual < $totalPaginas) {
                        echo "<a href='?pagina=" . ($paginaAtual + 1) . "&veiculoplaca=". ($veiculoPlaca)."' class='proxima-pagina'>&raquo;</a>";
                    }
                } else {
                    // Caso haja apenas uma página, mostre o link de página 1
                    echo "<span class='pagina-atual'>1</span>";
                }
                ?>
            </div>

            <div></div>

        </div>

        <a class="detalhes_os" href="detalhes_os_concluidas.php">Detalhes</a>
    </div>

</body>
<script>

    function validarPesquisa() {
        var campoPesquisa = document.querySelector("input[name='ordem_servico_id']");

        if (campoPesquisa.value === "") {
            alert("Preencha o campo de pesquisa antes de realizar a busca.");
            return false; // Impede o envio do formulário
        }

        // Se o campo estiver preenchido, permite o envio do formulário
        return true;
    }
</script>

</html>