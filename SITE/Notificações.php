<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Notificações</title>
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
                <li><a href="Notificações.php">Notificações</a></li>
                <li><a href="estoque.html">Estoque</a></li>
                <li><a href="Criação OS.php">Criação/Consulta de OS</a></li>
            </ul>
        </div>
<?php
// Consulta do banco de dados para recuperar as notificações do usuário "ADM" não lidas
$notificacoes = getNotificacoesNaoLidasParaADM($id_usuario_adm);
?>

<h2>Notificações</h2>

    <ul>
        <?php foreach ($notificacoes as $notificacao): ?>
            <li>
                <strong><?php echo $notificacao['data']; ?></strong><br>
                <?php echo $notificacao['mensagem']; ?><br>
                <!-- Adicionar links ou botões para ações relacionadas à notificação, se a gente achar necessário (eu acho que é)-->
            </li>
        <?php endforeach; ?>
    </ul>

    <form method="post" action="marcar_notificacoes_lidas.php">
        <input type="submit" value="Marcar Todas Como Lidas">
    </form>
