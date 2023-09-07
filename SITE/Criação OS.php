<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

// Prepare a consulta SQL sem a cláusula WHERE
$sql = "SELECT * FROM ordem_servico_completa";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $os_details = array(); // Inicializa um array para armazenar os detalhes da ordem de serviço

    while ($row = $result->fetch_assoc()) {
        // Armazena cada linha no array de detalhes da ordem de serviço
        $os_details[] = $row;
    }
} else {
    echo "<p>Nenhuma Ordem de Serviço encontrada.</p>";
}

// Fechar a conexão
$conn->close();
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
                <li><a href="inicio.html">Inicio</a></li>
                <li><a href="Venda.html">Venda</a></li>
                <li><a href="Financeiro.php">Financeiro</a></li>
                <li><a href="Debitos.html">Debitos</a></li>
                <li><a href="#">Notificações</a></li>
                <li><a href="estoque.html">Estoque</a></li>
                <li><a href="Criação OS.php">Criação/Consulta de OS</a></li>
            </ul>
        </div>
        <div id="criar-consulta">
            <button onclick="mostrarCriarOrdem()">Criar Ordem</button>
            <button onclick="mostrarConsultarOrdens()">Consultar Ordens</button>
        </div>
    
        <div id="criar-ordem" style="display: none;">
            <!-- Conteúdo para criar uma nova ordem de serviço -->
            <h2>Criar Nova Ordem de Serviço</h2>
            <form method="POST" action="processar_os.php">
                <label>Nome do Cliente:</label>
                <input type="text" name="cliente_nome" required><br><br>

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

            <h2>Serviços Prestados</h2>
                <label>Nome do Serviço:</label>
                <input type="text" name="servico_nome[]"><br>
            
                <label>Técnico Responsável:</label>
                <input type="text" name="tecnico_responsavel[]"><br>
            
                <label>Valor do Serviço:</label>
                <input type="number" name="valor_servico[]"><br>
                
            <h2>Observações</h2>
                <label>Observações:</label>
                <textarea name="observacoes_vendedor" rows="4" cols="50"></textarea><br>

                
                <input type="submit" value="Cadastrar Ordem de Serviço">
            </form>
        </div>
    
        <div id="consultar-ordens" style="display: none;">
            <!-- Conteúdo para consultar ordens de serviço existentes -->
            <h2>Consultar Ordens de Serviço</h2>
            <?php
                if (!empty($os_details)) {
                    foreach ($os_details as $os) {
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
            <a href="detalhes_os.php">Detalhes</a>
        </div>
    
        <script>
            function mostrarCriarOrdem() {
                document.getElementById("criar-ordem").style.display = "block";
                document.getElementById("consultar-ordens").style.display = "none";
            }
    
            function mostrarConsultarOrdens() {
                document.getElementById("criar-ordem").style.display = "none";
                document.getElementById("consultar-ordens").style.display = "block";
                // Aqui você pode adicionar a lógica para consultar as ordens de serviço existentes
            }
        </script>
    </body>
</html>
