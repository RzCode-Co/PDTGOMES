<?php
    require_once "config.php"; // Arquivo de configuração do banco de dados

    // Consulta SQL para recuperar as ordens de serviço em andamento
    $sql = "SELECT * FROM ordem_servico_completa WHERE status = 'Em Andamento'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $os_details = array(); // Inicializa um array para armazenar os detalhes da ordem de serviço

        while ($row = $result->fetch_assoc()) {
            // Armazena cada OS em andamento no array de detalhes da ordem de serviço
            $os_details[] = $row;
        }
    } else {
        echo "<p>Nenhuma Ordem de Serviço em andamento encontrada.</p>";
    }

    // Se houver menos de 5 OS em andamento, exiba todas em uma página
    $registrosPorPagina = min(5, count($os_details));

    // Calcule o total de páginas com base na quantidade de registros e na quantidade de registros por página
    $totalPaginas = ceil(count($os_details) / $registrosPorPagina);

    // Recupere o número da página atual a partir da consulta GET
    if (isset($_GET['pagina'])) {
        $paginaAtual = $_GET['pagina'];
    } else {
        $paginaAtual = 1;
    }

    // Verifique se a página atual está dentro dos limites
    $paginaAtual = max(1, min($paginaAtual, $totalPaginas));

    // Calcule o deslocamento a partir da página atual
    $deslocamento = ($paginaAtual - 1) * $registrosPorPagina;

    // Exiba as OS em andamento da página atual
    $inicio = $deslocamento;
    $fim = min($deslocamento + $registrosPorPagina, count($os_details));

    // Exiba as OS em andamento da página atual
    for ($i = $inicio; $i < $fim; $i++) {
        $os = $os_details[$i];
    }

?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Criação/Consulta de OS</title>
    </head>
    <style>
        body {
            background-color: gray; /* Define o fundo cinza */
            color: yellow; /* Define a cor do texto como amarelo */
        }
        
        #conteudo {
            margin: 20px; /* Adiciona margem para separar o conteúdo do cabeçalho e do menu lateral */
        }
        
        /* Estilização básica para o cabeçalho */
        #cabecalho {
            background-color: black; /* Cor de fundo do cabeçalho (pode ajustar conforme desejado) */
            color: white; /* Cor do texto no cabeçalho (pode ajustar conforme desejado) */
            padding: 10px; /* Espaçamento interno no cabeçalho */
            display: flex; /* Para alinhar os elementos do cabeçalho na horizontal */
            justify-content: space-between; /* Distribui os elementos horizontalmente */
        }
        
        #usuario-info {
            display: flex; /* Alinha os elementos do usuário na horizontal */
            align-items: center; /* Centraliza verticalmente os elementos do usuário */
        }
        
        #icone-notificacoes {
            /* Adicione estilos para o ícone de notificações, como tamanho, margem, etc. */
        }
        
        /* Estilização para o menu lateral */
        #menu-lateral {
            background-color: black; /* Cor de fundo do menu (pode ajustar conforme desejado) */
        }
        
        #menu-lateral ul {
            list-style-type: none; /* Remove marcadores de lista */
            padding: 0; /* Remove o preenchimento padrão da lista */
        }
        
        #menu-lateral ul li {
            margin: 0; /* Remove a margem padrão dos itens da lista */
        }
        
        #menu-lateral ul li a {
            display: block; /* Transforma os links em blocos para preencher o espaço disponível */
            padding: 10px 20px; /* Espaçamento interno nos links */
            color: white; /* Cor do texto dos links */
            text-decoration: none; /* Remove sublinhado dos links */
        }
        
        #menu-lateral ul li a:hover {
            background-color: gray; /* Cor de fundo quando o mouse passa por cima */
        }
        table {
            border-collapse: collapse;
            width: 80%;
            margin: 20px auto;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: black;
        }
        .paginacao {
        margin-top: 20px;
        text-align: center;
        }
        
        .paginacao a {
            padding: 5px 10px;
            background-color: black;
            color: white;
            text-decoration: none;
            margin: 5px;
        }
        
        .paginacao a:hover {
            background-color: gray;
        }
    </style>
    <body>
        <div id="cabecalho">
            <div id="usuario-info">
                <img src="<?php echo $fotoUsuario; ?>" alt="Foto do Usuário">
                <p><?php echo $nomeUsuario; ?></p>
                <p><?php echo $cargoUsuario; ?></p>
            </div>
            <!-- Ícone de notificações -->
            <div id="icone-notificacoes">
                <img src="caminho-para-o-icone.png" alt="Ícone de Notificações">
            </div>
        </div>
        <!-- Seu menu lateral -->
        <div id="menu-lateral">
            <ul>
                <li><a href="inicio.php">Inicio</a></li>
                <li><a href="Venda.html">Venda</a></li>
                <li><a href="Financeiro.php">Financeiro</a></li>
                <li><a href="Graficos.php">Gráficos</a></li>
                <li><a href="Debitos.php">Debitos</a></li>
                <li><a href="Notificações.php">Notificações</a></li>
                <li><a href="Estoque.php">Estoque</a></li>
                <li><a href="Criação OS.php">Criação/Consulta de OS</a></li>
            </ul>
        </div>
        <div id="botoes-os">
            <button onclick="mostrarCriarOrdem()">Criar Ordem</button>
            <button onclick="mostrarConsultarOrdens()">Consultar Ordens</button>
            <button onclick="mostrarOrdensConcluidas()">Ordens Concluidas</button>
            <button onclick="mostrarCancelarOrdem()">Cancelar Ordem</button>
        </div>
        <div id="criar-ordem" style="display: none;">
            <!-- Conteúdo para criar uma nova ordem de serviço -->
            <h2>Criar Nova Ordem de Serviço</h2>
            <form method="POST" action="processar_os.php">
                <label>Nome do Cliente:</label>
                <input type="text" name="cliente_nome" required><br>
                <label>CPF/CNPJ:
                    <select name="cpf_cnpj" id="cpf_cnpj" onchange="mostrarCampo()">
                        <option value="">Selecione...</option>
                        <option value="CPF">CPF</option>
                        <option value="CNPJ">CNPJ</option>
                    </select>
                    <br>
                </label>
                <div id="CPF" style="display: none;">
                    <label for="CPF">CPF: <input type="text" name="CPF" maxlength="11"></label>
                </div>
            
                <div id="CNPJ" style="display: none;">
                    <label for="CNPJ">CNPJ: <input type="text" name="CNPJ" maxlength="14"></label>
                </div>
                <label>Nome do Veículo:</label>
                <input type="text" name="veiculo_nome" required><br>

                <label>Placa do Veículo:</label>
                <input type="text" name="veiculo_placa" required><br>

                <label>Endereço do Cliente:</label>
                <input type="text" name="endereco_cliente" required><br><br>
                
                <label>Data de Abertura:</label>
                <input type="date" name="data_abertura" required><br><br>

                <h2>Produtos Vendidos</h2>
                <label>Código do Produto:</label>
                <input type="number" name="codigo_produto[]"><br>

                <label>Produto:</label>
                <input type="text" name="produto[]"><br>

                <label>Referência:</label>
                <input type="text" name="referencia[]"><br>

                <label>Tipo:</label>
                <input type="text" name="tipo[]"><br>

                <label>Quantidade:</label>
                <input type="number" name="quantidade[]"><br>

                <label>Preço:</label>
                <input type="number" name="preco[]"><br>

                <label for="pagamento_previo">Produtos Pagos Antes da OS?</label>
                <input type="checkbox" name="pagamento_previo" id="pagamento_previo" value="1">

                <h2>Serviços Prestados</h2>
                <label>Nome do Serviço:</label>
                <input type="text" name="servico_nome[]"><br>
            
                <label>Técnico Responsável:</label>
                <input type="text" name="tecnico_responsavel[]"><br>
            
                <label>Valor do Serviço:</label>
                <input type="number" name="valor_servico[]"><br>

                <label for="forma_pagamento">Forma de Pagamento:</label>
                <select name="forma_pagamento" id="forma_pagamento"onchange="mostrarParcelas()">
                    <option value="dinheiro">Dinheiro</option>
                    <option value="cartao">Cartão de Crédito</option>
                    <option value="cartao_debito">Cartão de Débito</option>
                    <option value="pix">Pix</option>
                    <option value="Parcelado">Parcelado</option>
                </select>
                <div id="parcelas" style="display: none;">
                    <label for="numero_parcelas">Número de Parcelas: 
                        <select name="numero_parcelas" id="numero_parcelas">
                            <option value="1">1x</option>
                            <option value="2">2x</option>
                            <option value="3">3x</option>
                            <option value="4">4x</option>
                            <option value="5">5x</option>
                            <option value="6">6x</option>
                            <!-- Adicione mais opções conforme necessário -->
                        </select>
                    </label>
                </div>
                
                <h2>Observações</h2>
                <label>Observações:</label>
                <textarea name="observacoes_vendedor" rows="4" cols="50"></textarea><br>

                
                <input type="submit" value="Cadastrar Ordem de Serviço">
            </form>
        </div>
        <div id="cancelar-ordem" style="display: none;">
            <h2>Cancelar Ordem de Serviço</h2>
            <form method="POST" action="processar_os_devolucao.php">
                <label>Numero da OS:</label>
                <input type="int" name="ordem_servico_id" required><br><br>

                <label>Deseja Estornar os Produto para o Estoque ?</label>
                <select name="estornar_produtos" id="estornar_produtos" onchange="mostrarCampo()">
                            <option value="">Selecione...</option>
                            <option value="Sim">Sim</option>
                            <option value="Nao">Não</option>
                </select>
                
                <br>

                <input type="submit" value="Cancelar Ordem de Serviço">
            </form>
        </div>
        
        <div id="consultar-ordens">
            <!-- Conteúdo para consultar ordens de serviço existentes -->
            <h2>Consultar Ordens de Serviço</h2>
            <form method="GET">
                <label>Pesquisar por ID da Ordem de Serviço:</label>
                <input type="number" name="ordem_servico_id">
                <input type="submit" value="Pesquisar">
            </form>
            <?php
                require_once "config.php"; // Arquivo de configuração do banco de dados

                // Verifique se o campo de pesquisa está preenchido
                if (isset($_GET['ordem_servico_id'])) {
                    $ordemServicoID = $_GET['ordem_servico_id'];
    
                    // Consulta SQL para recuperar a ordem de serviço com o ID especificado
                    $sql = "SELECT * FROM ordem_servico_completa WHERE ordem_servico_id = $ordemServicoID";
                    $result = $conn->query($sql);
    
                    if ($result->num_rows > 0) {
                        $os_details = array(); // Inicializa um array para armazenar os detalhes da ordem de serviço
    
                        while ($row = $result->fetch_assoc()) {
                            // Armazena a ordem de serviço encontrada no array de detalhes da ordem de serviço
                            $os_details[] = $row;
                        }
                    } else {
                        echo "<p>Nenhuma Ordem de Serviço encontrada com o ID especificado.</p>";
                    }
                } 

                if (!empty($os_details)) {
                    foreach ($os_details as $os) {
                        if ($os['status'] == 'Concluída') {
                            // Não exiba ordens concluídas aqui
                            continue;
                        }
                        echo "<h3>Ordem de Serviço ID: {$os['ordem_servico_id']}</h3>";
                        echo "<table>";
                        echo "<tr><th>ID</th><td>{$os['ordem_servico_id']}</td></tr>";
                        echo "<tr><th>Cliente</th><td>{$os['cliente_nome']}</td></tr>";
                        echo "<tr><th>Veículo</th><td>{$os['veiculo_nome']}</td></tr>";
                        echo "<tr><th>Placa do Veículo</th><td>{$os['veiculo_placa']}</td></tr>";
                        echo "<tr><th>Data de Abertura</th><td>{$os['data_abertura']}</td></tr>";
                        echo "<tr><th>Status</th><td>{$os['status']}</td></tr>";
                        echo "</table>";
                    }
                }
            ?>
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
                        echo "<a href='?pagina=" . ($paginaAtual + 1) . "' class='proxima-pagina'>&raquo;</a>";
                    }
                } else {
                    // Caso haja apenas uma página, mostre o link de página 1
                    echo "<span class='pagina-atual'>1</span>";
                }
                ?>
            </div>


            <a href="detalhes_os_em_andamento.php">Detalhes</a>
        </div>

        <div id="ordens-concluidas"></div>

    </body>
    <script>
        function mostrarCampo() {
            var selecao = document.getElementById("cpf_cnpj");
            var CPF = document.getElementById("CPF");
            var CNPJ = document.getElementById("CNPJ");

            if (selecao.value === "CPF") {
                CPF.style.display = "block";
                CNPJ.style.display = "none";
            } else if (selecao.value === "CNPJ") {
                CPF.style.display = "none";
                CNPJ.style.display = "block";
            } else {
                CPF.style.display = "none";
                CNPJ.style.display = "none";
            }
        }
        function mostrarParcelas() {
            var formaPagamento = document.getElementById("forma_pagamento");
            var parcelasDiv = document.getElementById("parcelas");
    
            if (formaPagamento.value === "Parcelado") {
                parcelasDiv.style.display = "block";
            } else {
                parcelasDiv.style.display = "none";
            }
        }
        function mostrarCriarOrdem() {
            document.getElementById("criar-ordem").style.display = "block";
            document.getElementById("consultar-ordens").style.display = "none";
            document.getElementById("ordens-concluidas").style.display = "none";
            document.getElementById("cancelar-ordem").style.display = "none";
        }
    
        function mostrarConsultarOrdens() {
            document.getElementById("criar-ordem").style.display = "none";
            document.getElementById("consultar-ordens").style.display = "block";
            document.getElementById("ordens-concluidas").style.display = "none";
            document.getElementById("cancelar-ordem").style.display = "none";
            document.location.href = "consultar_ordens_servico.php";
        }
        function mostrarOrdensConcluidas() {
            document.getElementById("criar-ordem").style.display = "none";
            document.getElementById("consultar-ordens").style.display = "none";
            document.getElementById("ordens-concluidas").style.display = "block";
            document.getElementById("cancelar-ordem").style.display = "none";
            document.location.href = "consultar_ordens_servicos_concluidas.php";
        }
        function mostrarCancelarOrdem() {
            document.getElementById("criar-ordem").style.display = "none";
            document.getElementById("consultar-ordens").style.display = "none";
            document.getElementById("ordens-concluidas").style.display = "none";
            document.getElementById("cancelar-ordem").style.display = "block";
        }
    </script>
</html>