<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

// Defina o número máximo de registros por página
$registrosPorPagina = 5;

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
    <nav class="menu_lateral">

        <!-- Barra MENU -->
        <div class="btn_expandir">
            <img src="../CSS/img/Três barras.svg" alt="menu" id="btn_exp">
        </div>

        <!--  itens MENU LATERAL-->
        <ul class="ul_menu_lateral">

            <li class="item_menu">
                <a href="../PHP/Inicio.php">
                    <img class="icon" src="../CSS/img/Logo Circular verde.svg" alt="logo">
                    <span class="txt_link">Home</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../HTML/Venda.html">
                    <img class="icon" src="../CSS/img/VENDAS.svg" alt="icone compras">
                    <span class="txt_link">Vendas</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../PHP/estoque.php">
                    <img class="icon" src="../CSS/img/Compras.svg" alt="icone compras">
                    <span class="txt_link">Estoque</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../PHP/Financeiro.php">
                    <img class="icon" src="../CSS/img/Gráficos.svg" alt="icone graficos">
                    <span class="txt_link">Financeiro</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../PHP/Debitos.php">
                    <img class="icon" src="../CSS/img/Carteira.svg" alt="icone carteira">
                    <span class="txt_link">Débitos</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../PHP/Criação OS.php">
                    <img class="icon" src="../CSS/img/OS.svg" alt="icone OS">
                    <span class="txt_link">O.S</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../HTML/pagina_cadastro.html">
                    <img class="icon" src="../CSS/img/Perfil.svg" alt="icone perfil">
                    <span class="txt_link">Cadastro</span>
                </a>
            </li>

            <li class="item_menu">
                <a href="../PHP/Notificações.php">
                    <img class="icon" src="../CSS/img/Sino.svg" alt="logo">
                    <span class="txt_link">Notificações</span>
                </a>
            </li>

        </ul>
        <!-- importando o JS para o Menu Lateral-->
        <script src="../JS/menu.js"></script>

    </nav>


    <!-- Menu horizonatl -->
    <nav class="menu_horizontal">
        <ul>
            <li id="logo_menu_horizontal"><a href="../PHP/Inicio.php"><img src="../CSS/img/Logo Horizontal.png"
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

            <li id="direita"><a href="../PHP/Notificações.php"><img src="../CSS/img/Sino_menu_horizontal.svg"
                        alt="Notificações"></a></li>

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
        $veiculoPlaca = null;
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

                    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['editar_os']) && $_POST['ordem_servico_id'] == $os['ordem_servico_id']) {
                        // Formulário de edição enviado, processe a atualização
                        $ordem_servico_id = $os['ordem_servico_id'];
                        $cliente_nome = $_POST['cliente_nome'];
                        $veiculo_nome = $_POST['veiculo_nome'];
                        $veiculo_placa = $_POST['veiculo_placa'];
                        $data_abertura = $_POST['data_abertura'];

                        // Execute a atualização no banco de dados
                        $sqlAtualizacao = "UPDATE ordem_servico_completa SET
                    cliente_nome = '$cliente_nome',
                    veiculo_nome = '$veiculo_nome',
                    veiculo_placa = '$veiculo_placa',
                    data_abertura = '$data_abertura'
                    WHERE ordem_servico_id = $ordem_servico_id";

                        if ($conn->query($sqlAtualizacao) === TRUE) {
                            echo "<p>Ordem de Serviço atualizada com sucesso.</p>";
                        } else {
                            echo "<p>Erro ao atualizar a Ordem de Serviço: " . $conn->error . "</p>";
                        }
                    } else {
                        // Exiba os detalhes da ordem de serviço com um botão de edição
                        echo "<form method='POST' action=''>";
                        echo "<table>";
                        echo "<tr><th>ID:</th><td>{$os['ordem_servico_id']}</td></tr>";
                        echo "<tr>
                        <th>Cliente:</th>
                        <td><input type='text' name='cliente_nome' value='{$os['cliente_nome']}'></td>
                        </tr>";
                        // Verifique se há um CPF definido e não é nulo
                        if (!is_null($os['CPF']) && $os['CPF'] !== '0') {
                            echo "<th>CPF:</th><td> {$os['CPF']}</td>";
                        }

                        // Verifique se há um CNPJ definido e não é nulo
                        if (!is_null($os['CNPJ']) && $os['CNPJ'] !== '0') {
                            echo "<th>CNPJ:</th><td> {$os['CNPJ']}</td>";
                        }
                        echo "<tr>
                        <th>Veículo:</th>
                        <td><input type='text' name='veiculo_nome' value='{$os['veiculo_nome']}'></td>
                        </tr>";
                        echo "<tr>
                        <th>Placa do Veículo:</th>
                        <td><input type='text' name='veiculo_placa' value='{$os['veiculo_placa']}'></td>
                        </tr>";
                        echo "<tr>
                        <th>Data de Abertura:</th>
                        <td><input type='text' name='data_abertura' value='{$os['data_abertura']}'></td>
                        </tr>";
                        echo "</table>";
                        echo "<input type='hidden' name='ordem_servico_id' value='{$os['ordem_servico_id']}'>";
                        echo "<input type='submit' name='editar_os' value='Salvar'>";
                        echo "</form>";

                        echo "</div>";

                        echo "<div class='saiba_mais'>";
                        echo "<form method='GET' action='detalhes_os.php'>";
                        echo "<input type='hidden' name='ordem_servico_id' value='{$os['ordem_servico_id']}'>";
                        echo "<input type='submit' name='detalhar_os' value='Saiba mais'>";
                        echo "</form>";
                        echo "</div>";

                    }
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
                        echo "<a href='?pagina=" . ($paginaAtual + 1) . "&veiculoplaca=" . ($veiculoPlaca) . "' class='proxima-pagina'>&raquo;</a>";
                    }
                } else {
                    // Caso haja apenas uma página, mostre o link de página 1
                    echo "<span class='pagina-atual'>1</span>";
                }
                ?>
            </div>

            <div></div>

        </div>
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