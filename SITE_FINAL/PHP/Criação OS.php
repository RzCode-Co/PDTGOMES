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

// Prepare a consulta SQL com LIMIT e OFFSET para a página atual
$sql = "SELECT * FROM ordem_servico_completa LIMIT $registrosPorPagina OFFSET $deslocamento";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $os_details = array(); // Inicializa um array para armazenar os detalhes da ordem de serviço

    while ($row = $result->fetch_assoc()) {
        // Armazena cada linha no array de detalhes da ordem de serviço
        $os_details[] = $row;
    }
} else {
}

// Calcular o número total de páginas com base no total de registros
$sqlTotalRegistros = "SELECT COUNT(*) AS total FROM ordem_servico_completa";
$resultTotalRegistros = $conn->query($sqlTotalRegistros);
$totalRegistros = $resultTotalRegistros->fetch_assoc()['total'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// Fechar a conexão
$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../CSS/criacao-OS.css">
    <link rel="stylesheet" href="../CSS/pagina_inicial.css">

    <title>Criação/Consulta de OS</title>
</head>

<body>
    <nav class="menu_lateral">

        <!-- Barra MENU -->
        <div class="btn_expandir">
            <img src="../CSS/img/Três barras.svg" alt="menu" id="btn_exp">
        </div>

        <!--  itens MENU LATERAL-->
        <ul class="ul_menu_lateral">

            <li class="item_menu ativo">
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



    <div class="container-os">

        <div id="card-botoes-os">
            <button onclick="mostrarCriarOrdem()" class="botao-os">Criar Ordem</button>
            <button onclick="mostrarCancelarOrdem()" class="botao-os">Cancelar Ordem</button>
            <button onclick="mostrarConsultarOrdens()" class="botao-os">Consultar Ordens</button>
            <button onclick="mostrarOrdensConcluidas() " class="botao-os">Ordens Concluidas</button>

        </div>

        <div class="card-ordens">
            <div id="criar-ordem" style="display: none;">
                <!-- Conteúdo para criar uma nova ordem de serviço -->
                <div class="container-criar-ordem">

                    <section class="card-ordem-serviços">
                        <h2 class="estilizacao-h2">Criar Nova Ordem de Serviço</h2>
                        <form method="POST" action="processar_os.php">

                            <div class="card2-servico">
                                <div>
                                    <label>Nome do Cliente:</label>
                                    <input type="text" name="cliente_nome" required id="input-servicos">
                                </div>

                                <div id="cpf">

                                    <label>CPF/CNPJ:</label>
                                    <select name="cpf_cnpj" id="cpf_cnpj" onchange="mostrarCampo()">
                                        <option value="">Selecione...</option>
                                        <option value="CPF">CPF</option>
                                        <option value="CNPJ">CNPJ</option>
                                    </select>
                                </div>

                            </div>
                            <div id="CPF" style="display: none;">
                                <label for="CPF">CPF: <input type="text" name="CPF" maxlength="11"></label>
                            </div>
                            <div id="CNPJ" style="display: none;">
                                <label for="CNPJ">CNPJ: <input type="text" name="CNPJ" maxlength="14"></label>
                            </div>

                            <div class="card2-servico">
                                <div>
                                    <label>Nome Fantasia:</label>
                                    <input type="text" name="nome_fantasia">
                                </div>
                                <div>
                                    <label>Email:</label>
                                    <input type="text" name="email">
                                </div>
                            </div>

                            <div class="card2-servico">
                                <div>
                                    <label>Telefone:</label>
                                    <input type="varchar" name="telefone">
                                </div>
                                <div>
                                    <label>CEP:</label>
                                    <input type="int" name="CEP">
                                </div>
                            </div>

                            <div class="card2-servico">

                                <div>
                                    <label>Nome do Veículo:</label>
                                    <input type="text" name="veiculo_nome" required>
                                </div>

                                <div id="placa-veiculo">
                                    <label>Placa do Veículo:</label>
                                    <input type="text" name="veiculo_placa" required>
                                </div>

                            </div>

                            <div class="card2-servico">
                                <div id="endereco">
                                    <label>Endereço do Cliente:</label>
                                    <input type="varchar" name="endereco" required>
                                </div>
                                <div id="data">
                                    <label>Data de Abertura:</label>
                                    <input type="date" name="data_abertura" required>
                                </div>
                            </div>
                    </section>

                    <section class="card-ordem-produtos">
                        <h2 class="estilizacao-h2">Produtos Vendidos</h2>


                        <div class="card-produtos1">
                            <div>
                                <label>Código do Produto:</label>
                                <input type="number" name="codigo_produto[]">
                            </div>
                            <div>
                                <label>Produto:</label>
                                <input type="text" name="produto[]">
                            </div>
                        </div>

                        <div class="card-produtos1">

                            <div class="referencia">
                                <label>Referência:</label>
                                <input type="text" name="referencia[]">
                            </div>

                            <div class="tipo">
                                <label>Tipo:</label>
                                <input type="text" name="tipo[]">
                            </div>
                        </div>

                        <div class="card-produtos1">
                            <div>
                                <label>Quantidade:</label>
                                <input type="number" name="quantidade[]">
                            </div>
                            <div>
                                <label>Preço:</label>
                                <input type="number" name="preco[]">
                            </div>
                        </div>

                        <div class="card-produtos1">
                            <label for="pagamento_previo">Produtos Pagos Antes da OS?</label>
                            <input type="checkbox" name="pagamento_previo" id="pagamento_previo" value="1">
                        </div>
                    </section>

                    <section class="card-ordem-prestados">

                        <h2 class="estilizacao-h2">Serviços Prestados</h2>

                        <div class="card2-servico">
                            <div>
                                <label>Nome do Serviço:</label>
                                <input type="text" name="servico_nome[]">
                            </div>

                            <div>
                                <label>Técnico Responsável:</label>
                                <input type="text" name="tecnico_responsavel[]">
                            </div>
                        </div>


                        <div class="card2-servico">

                            <div>
                                <label>Valor do Serviço:</label>
                                <input type="number" name="valor_servico[]">
                            </div>

                            <div>
                                <label for="forma_pagamento">Forma de Pagamento:</label>
                                <select name="forma_pagamento" id="forma_pagamento" onchange="mostrarParcelas()">
                                    <option value="dinheiro">Dinheiro</option>
                                    <option value="cartao">Cartão de Crédito</option>
                                    <option value="cartao_debito">Cartão de Débito</option>
                                    <option value="Pix">Pix</option>
                                    <option value="Parcelado">Parcelado</option>
                                    <option value="Boleto">Boleto</option>
                                </select>
                            </div>
                        </div>

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
                    </section>

                    <section class="card-ordem-observacoes">
                        <label>Observações:</label>
                        <textarea name="observacoes_vendedor" rows="4" cols="50"></textarea>
                    </section>
                </div>

                <div class="card-botao-ordem">
                    <input type="submit" value="Cadastrar Ordem de Serviço" class="botao-ordens">
                </div>

                </form>
            </div>

            <div id="cancelar-ordem" style="display: none;">
                <h2 class="estilizacao-h2">Cancelar Ordem de Serviço</h2>
                <form method="POST" action="processar_os_devolucao.php">

                    <div class="container-card-cancelar">
                        <div class="card-numero-os">
                            <label>Numero da OS:</label>
                            <input type="int" name="ordem_servico_id" required>
                        </div>
                        <div class="card-estornar">
                            <label>Estornar os Produto para o Estoque ?</label>
                            <select name="estornar_produtos" id="estornar_produtos" onchange="mostrarCampo()">
                                <option value="">Selecione...</option>
                                <option value="Sim">Sim</option>
                                <option value="Nao">Não</option>
                            </select>
                        </div>
                    </div>

                    <input type="submit" value="Cancelar Ordem de Serviço" class="botao-ordem-cancelar">
                </form>
            </div>

            <div id="consultar-ordens"></div>

            <div id="ordens-concluidas"></div>
        </div>
    </div>


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

        if (formaPagamento.value === "Boleto"){
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