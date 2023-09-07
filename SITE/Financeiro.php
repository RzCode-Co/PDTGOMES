<?php
require_once "config.php"; // Arquivo de configuração do banco de dados

// Consulta SQL para buscar todas as vendas na tabela "vendas"
$sql = "SELECT * FROM vendas";

$result = $conn->query($sql);

// Array para armazenar o histórico de vendas
$historico = array();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $historico[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Financeiro</title>
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

        <div id="historico-de-vendas">
            <h1>Historico de vendas</h1>
            <table>
                <tr>
                    <th>Funcionário</th>
                    <th>Comprador</th>
                    <th>Peça</th>
                    <th>Forma de Pagamento</th>
                    <th>Valor</th>
                    <th>Quantidade</th>
                </tr>
                <?php
                foreach ($historico as $venda) {
                    echo "<tr>";
                    echo "<td>" . $venda["funcionario_vendedor"] . "</td>";
                    echo "<td>" . $venda["nome_comprador"] . "</td>";
                    echo "<td>" . $venda["nome_peca"] . "</td>";
                    echo "<td>" . $venda["forma_pagamento"] . "</td>";
                    echo "<td>" . $venda["valor_venda"] . "</td>";
                    echo "<td>" . $venda["quantidade"] . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
        <div id="contas-a-receber">
            <h1>Contas a Receber</h1>
            <table>
                <tr>
                    <th>Comprador</th>
                    <th>Número de Parcelas</th>
                    <th>Data de Venda</th>
                </tr>
                <?php
                foreach ($historico as $conta) {
                    if ($conta["numero_parcelas"] > 0) { // Verifica se o numero_parcelas é maior que 0
                        echo "<tr>";
                        echo "<td>" . $conta["nome_comprador"] . "</td>";
                        echo "<td>" . $conta["numero_parcelas"] . "x</td>"; // Exibe o número de parcelas com um "x" após o número
                        echo "<td>" . $conta["data_venda"] . "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>
    </body>
</html>
