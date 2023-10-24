<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Estoque</title>
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
        #pagination {
            margin: 20px 0;
        }

        #pagination a {
            padding: 5px 10px;
            background-color: lightgray;
            text-decoration: none;
            margin-right: 5px;
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
        <div id="botoes-estoque">
            <button onclick="mostrarAdicionarItem()">Adicionar Item</button>
            <button onclick="mostrarRemoverItem()">Remover Item</button>
            <button onclick="mostrarConsultarItem()">Consultar Item</button>
            <button onclick="window.location.href='consulta_geral_estoque.php'">Consultar Todos os Itens</button>
        </div>
        <div id="conteudo">

            <div id="adicionar-item" style="display: none;">
                <h2>Adicionar Item ao Estoque</h2>
                <form action="processar_adicionar_item.php" method="post" onchange="mostrarAdicionarItem()">
                    <label>Nome do Item: <input type="text" name="nome"></label><br>
                    <label>Referência: <input type="text" name="referencia"></label><br>
                    <label>Marca: <input type="text" name="marca"></label><br>
                    <label>Aplicação: <input type="text" name="aplicacao"></label><br>
                    <label>Ano: <input type="number" name="ano"></label><br>
                    <label>Quantidade: <input type="number" name="quantidade"></label><br>
                    <label>Valor de Custo: <input type="float" name="valor_custo"></label><br>
                    <label>Valor de Varejo: <input type="float" name="valor_varejo"></label><br>
                    <label>Valor de Atacado: <input type="float" name="valor_atacado"></label><br>
                    <label>Local: <input type="text" name="local"></label><br>
                    <label>Imagem do Produto: <input type="file" name="imagem" accept="image/*"></label><br>
                    <input type="submit" value="Adicionar">
                </form>
            </div>    
                
            <div id="remover-item" style="display: none;">
                <h2>Remover Item do Estoque</h2>
                <form action="processar_remover_item.php" method="post" onchange="mostrarRemoverItem()">
                    <label>Nome do Item: <input type="text" name="nome"></label><br>
                    <label>Referência: <input type="text" name="referencia"></label><br>
                    <label>Marca: <input type="text" name="marca"></label><br>
                    <label>Aplicação: <input type="text" name="aplicacao"></label><br>
                    <label>Ano: <input type="number" name="ano"></label><br>
                    <label>Quantidade: <input type="number" name="quantidade"></label><br>
                    <input type="submit" value="Remover">
                </form>
            </div>

            <div id="consultar-item" style="display: none;">
                <h2>Pesquisa de Estoque</h2>
                <form action="consulta_item.php" method="post"  onchange="mostrarConsultarItem()">
                    <label>Nome do Item: <input type="text" name="nome"></label><br>
                    <label>Referência: <input type="text" name="referencia"></label><br>
                    <label>Marca: <input type="text" name="marca"></label><br>
                    <label>Aplicação: <input type="text" name="aplicacao"></label><br>
                    <label>Ano: <input type="number" name="ano"></label><br>
                    <input type="submit" value="Pesquisar">
                </form>
            </div>
    </body>
    <script>
        function mostrarAdicionarItem() {
        document.getElementById("adicionar-item").style.display = "block";
        document.getElementById("remover-item").style.display = "none";
        document.getElementById("consultar-item").style.display = "none";
        document.getElementById("resultado_busca_geral").style.display = "none";
        }
        function mostrarRemoverItem() {
            document.getElementById("adicionar-item").style.display = "none";
            document.getElementById("remover-item").style.display = "block";
            document.getElementById("consultar-item").style.display = "none";
            document.getElementById("resultado_busca_geral").style.display = "none";
        }
        function mostrarConsultarItem() {
            document.getElementById("adicionar-item").style.display = "none";
            document.getElementById("remover-item").style.display = "none";
            document.getElementById("consultar-item").style.display = "block";
            document.getElementById("resultado_busca_geral").style.display = "none";
        }
        function mostrarConsultarTodosItens() {
            document.getElementById("adicionar-item").style.display = "none";
            document.getElementById("remover-item").style.display = "none";
            document.getElementById("consultar-item").style.display = "none";
            document.getElementById("resultado_busca_geral").style.display = "block";
        }
    </script>
</html>
