    <!DOCTYPE html>
    <html lang="pt-br">
        <head>
            <meta charset="UTF-8">
            <title>Tela Inicial</title>
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
            <div id="caixa-pesquisa">
                <form action="resultado_inicio.php" method="get">
                    <label>Nome do Item: <input type="text" name="nome" value="<?php echo isset($nome) ? $nome : ''; ?>"></label><br>
                    <label>Referência: <input type="text" name="referencia" value="<?php echo isset($referencia) ? $referencia : ''; ?>"></label><br>
                    <label>Marca: <input type="text" name="marca" value="<?php echo isset($marca) ? $marca : ''; ?>"></label><br>
                    <label>Aplicação: <input type="text" name="aplicacao" value="<?php echo isset($aplicacao) ? $aplicacao : ''; ?>"></label><br>
                    <label>Ano: <input type="number" name="ano" value="<?php echo isset($ano) ? $ano : ''; ?>"></label><br>
                    <button id="btn-pesquisar">Pesquisar</button>
                </form>
            </div>
            <script>
            // Função para verificar se pelo menos um campo está preenchido
            function verificarPreenchimento() {
                var campos = document.querySelectorAll('input[type="text"], input[type="number"]');
                var botaoPesquisar = document.getElementById('btn-pesquisar');
                var preenchido = false;

                for (var i = 0; i < campos.length; i++) {
                    if (campos[i].value.trim() !== '') {
                        preenchido = true;
                        break;
                    }
                }

                if (preenchido) {
                    botaoPesquisar.removeAttribute('disabled');
                } else {
                    botaoPesquisar.setAttribute('disabled', 'disabled');
                }
            }

            // Adicione um evento de entrada para os campos
            var campos = document.querySelectorAll('input[type="text"], input[type="number"]');
            for (var i = 0; i < campos.length; i++) {
                campos[i].addEventListener('input', verificarPreenchimento);
            }
        </script>
        </body>
    </html>