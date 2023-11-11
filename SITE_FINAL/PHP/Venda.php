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
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Venda</title>
    <link rel="stylesheet" href="../CSS/pagina_inicial.css">
    <link rel="stylesheet" href="../CSS/vendas.css">
</head>

<body>
    <main>
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
                    <a href="../PHP/Venda.php">
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
    
                <?php if ($cargoUsuario != 'vendedor') { ?>
                    <li class="item_menu">
                        <a href="../PHP/Debitos.php">
                            <img class="icon" src="../CSS/img/Carteira.svg" alt="icone carteira">
                            <span class="txt_link">Débitos</span>
                        </a>
                    </li>
                <?php } ?>
    
                <li class="item_menu">
                    <a href="../PHP/Criação OS.php">
                        <img class="icon" src="../CSS/img/OS.svg" alt="icone OS">
                        <span class="txt_link">O.S</span>
                    </a>
                </li>
    
                <li class="item_menu">
                    <a href="../PHP/pagina_cadastro.php">
                        <img class="icon" src="../CSS/img/Perfil.svg" alt="icone perfil">
                        <span class="txt_link">Cadastro</span>
                    </a>
                </li>
    
                <?php if ($cargoUsuario != 'vendedor') { ?>
                    <li class="item_menu">
                        <a href="../PHP/Notificações.php">
                            <img class="icon" src="../CSS/img/Sino.svg" alt="logo">
                            <span class="txt_link">Notificações</span>
                        </a>
                    </li>
                <?php } ?>
    
            </ul>
            <!-- importando o JS para o Menu Lateral-->
            <script src="../JS/menu.js"></script>
    
        </nav>

        <!-- Menu horizonatl -->
        <nav class="menu_horizontal">
            <ul>
                <li id="logo_menu_horizontal"><a href="../PHP/Inicio.php"><img
                            src="../CSS/img/Logo Horizontal.png" alt="logo da empresa"></a>
                </li>

                <li id="direita">

                    <!-- Perfil -->
                    <div class="image_container">
                        <?php echo '<img src="' . $arquivo . '" alt="Foto do Usuário">';?>
                    </div>

                    <div><a href="logout.php">Sair</a></div>

                    <script src="../JS/login_preview.js"></script>
                </li>

                <li id="direita">
                    <!-- Cargo e nome -->
                    <div class="cargo_nome">
                        <h3><?php echo $cargoUsuario; ?></h3>
                        <p><?php echo $nomeUsuario; ?></p>
                    </div>
                </li>

                <?php if ($cargoUsuario != 'vendedor') { ?>
                    <li id="direita"><a href="../PHP/Notificações.php"><img src="../CSS/img/Sino_menu_horizontal.svg" alt="Notificações"></a></li>
                <?php } ?>

            </ul>

        </nav>
        <section class="geral_vendas">
            <div id="botoes-estoque">
                <button onclick="mostrarFazerVenda()">Registrar Venda</button>
                <button onclick="mostrarCancelarVenda()">Cancelar Venda</button>
            </div>

            <div class="conteudo">
                <div id="fazer-venda" style="display: none;">
                    <h1>Registrar Venda</h1>
                    <form class="form_vendas" action="../PHP/processar_venda.php" method="post" onsubmit="return validarVenda()">
                        <section class="one">
                            <div class="div_one">
                                <label for="nome_comprador">Nome do Comprador:</label>
                                <input type="text" name="nome_comprador">
                            </div>
                        </section>

                        <section class="two">
                            <div class="div_two">
                                <label for="nome_peca">Nome da peça:</label>
                                <input type="text" name="nome_peca">
                            </div>
                            <div class="div_two">
                                <label for="marca">Marca:</label>
                                <input type="text" name="marca">
                            </div>
                            <div class="div_two">
                                <label for="ano" id="input_pqn">Ano:</label>
                                <input type="text" name="ano">
                            </div>
                        </section>



                        <section class="three">

                            <div class="div_three">
                                <label for="referencia">Referência:</label>
                                <input type="text" name="referencia">
                            </div>

                            <div class="div_three">
                                <label for="aplicacao">Aplicação:</label>
                                <input type="text" name="aplicacao">
                            </div>

                            <div class="div_three">
                                <label for="quantidade" id="input_pqn">Quantidade vendida:</label>
                                <input type="number" name="quantidade">
                            </div>
                        </section>




                        <section class="four">

                            <div class="div_four">
                                <label>CPF/CNPJ:</label>
                                <select name="cpf_cnpj" id="cpf_cnpj" onchange="mostrarCampo()">
                                    <option value="">Selecione...</option>
                                    <option value="CPF">CPF</option>
                                    <option value="CNPJ">CNPJ</option>
                                </select>


                                <div id="CPF" style="display: none;">
                                    <label for="CPF"></label>
                                    <input type="text" name="CPF" maxlength="11">
                                </div>


                                <div id="CNPJ" style="display: none;">
                                    <label for="CNPJ"><input type="text" name="CNPJ" maxlength="14"></label>
                                </div>
                            </div>
                        </section>



                        <section class="five">

                            <div class="div_five">
                                <label for="forma_pagamento">Forma de Pagamento:</label>
                                <select name="forma_pagamento" id="forma_pagamento" onchange="mostrarParcelas()">
                                    <option value="Crédito">Crédito</option>
                                    <option value="Débito">Débito</option>
                                    <option value="Pix">Pix</option>
                                    <option value="Dinheiro">Dinheiro</option>
                                    <option value="Parcelado">Parcelado</option>
                                    <option value="Boleto">Boleto</option>
                                </select>
                            </div>
                            
                            <div class="div_five">
                                <div id="parcelas" style="display: none;">
                                    <label for="numero_parcelas">Número de Parcelas:</label>
                                    <select name="numero_parcelas" id="numero_parcelas">
                                        <option value="1">1x</option>
                                        <option value="2">2x</option>
                                        <option value="3">3x</option>
                                        <option value="4">4x</option>
                                        <option value="5">5x</option>
                                        <option value="6">6x</option>
                                        <!-- Adicione mais opções conforme necessário -->
                                    </select>
                                </div>
                            </div>                            

                        </section>


                        <section class="six">

                            <div class="div_six">
                                <label for="valor_venda">Valor da Venda:</label>
                                <input type="number" step="0.01" name="valor_venda">
                            </div>


                            <div class="div_six">
                                <label for="funcionario_vendedor">Funcionário Vendedor:</label>
                                <input type="text" name="funcionario_vendedor">
                            </div>

                            <div class="div_six">
                                <label for="garantia_produto">Garantia do produto:</label>
                                <input type="number" name="garantia_produto" placeholder="EM DIAS">
                            </div>
                        </section>

                        <input type="submit" value="Registrar Venda">
                    </form>
                </div>

                <div id="cancelar-venda" style="display: none;">
                    <h1>Cancelar Venda</h1>
                    <form class="form_vendas" action="../PHP/processar_cancelar_venda.php" method="post" onsubmit="return validarCancelamentoVenda()">
                        <section class="one">
                            <div class="div_one">
                                <label for="nome_comprador">Nome do Comprador:</label>
                                <input type="text" name="nome_comprador">
                            </div>
                        </section>

                        <section class="two">

                            <div class="div_two">
                                <label for="nome_peca">Nome da peça:</label>
                                <input type="text" name="nome_peca">
                            </div>

                            <div class="div_two">
                                <label for="marca">Marca:</label>
                                <input type="text" name="marca">
                            </div>

                            <div class="div_two">
                                <label for="ano">Ano:</label>
                                <input type="text" name="ano">
                            </div>

                        </section>

                        <section class="three">
                            <div class="div_three">
                                <label for="referencia">Referência:</label>
                                <input type="text" name="referencia">
                            </div>

                            <div class="div_three">
                                <label for="aplicacao">Aplicação:</label>
                                <input type="text" name="aplicacao">
                            </div>

                            <div class="div_three">
                                <label for="f">Funcionário Vendedor:</label>
                                <input type="text" name="funcionario_vendedor">
                            </div>
                        </section>

                        <section class="four">
                            <div class="div_four">
                                <label for="cpf_cnpj2">CPF/CNPJ:</label>
                                <select name="cpf_cnpj2" id="cpf_cnpj2" onchange="mostrarSelecao()">
                                    <option value="">Selecione...</option>
                                    <option value="CPF2">CPF</option>
                                    <option value="CNPJ2">CNPJ</option>
                                </select>


                                <div id="CPF2" style="display: none;">
                                    <label for="CPF2">CPF:</label>
                                    <input type="text" name="CPF2" maxlength="11">

                                </div>

                                <div id="CNPJ2" style="display: none;">
                                    <label for="CNPJ2">CNPJ:</label>
                                    <input type="text" name="CNPJ2" maxlength="14">
                                </div>
                            </div>
                        </section>
                        <input type="submit" value="Cancelar Venda">
                    </form>
                </div>
            </div>
        </section>
    </main>
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
    function mostrarSelecao() {
        var selecao = document.getElementById("cpf_cnpj2");
        var CPF = document.getElementById("CPF2");
        var CNPJ = document.getElementById("CNPJ2");

        if (selecao.value === "CPF2") {
            CPF.style.display = "block";
            CNPJ.style.display = "none";
        } else if (selecao.value === "CNPJ2") {
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
        
        if (formaPagamento.value === "Boleto") {
            parcelasDiv.style.display = "block";
        } else {
            parcelasDiv.style.display = "none";
        }
    }

    
    function mostrarFazerVenda() {
        document.getElementById("fazer-venda").style.display = "block";
        document.getElementById("cancelar-venda").style.display = "none";
    }
    function mostrarCancelarVenda() {
        document.getElementById("fazer-venda").style.display = "none";
        document.getElementById("cancelar-venda").style.display = "block";
    }

    function validarVenda() {
        var nome_comprador = document.getElementsByName("nome_comprador")[0].value;
        var nome_peca = document.getElementsByName("nome_peca")[0].value;
        var marca = document.getElementsByName("marca")[0].value;
        var ano = document.getElementsByName("ano")[0].value;
        var referencia = document.getElementsByName("referencia")[0].value;
        var aplicacao = document.getElementsByName("aplicacao")[0].value;
        var quantidade = document.getElementsByName("quantidade")[0].value;
        var cpf_cnpj = document.getElementsByName("cpf_cnpj")[0].value;
        var CPF = document.getElementsByName("CPF")[0].value;
        var CNPJ = document.getElementsByName("CNPJ")[0].value;
        var forma_pagamento = document.getElementsByName("forma_pagamento")[0].value;
        var valor_venda = document.getElementsByName("valor_venda")[0].value;
        var funcionario_vendedor = document.getElementsByName("funcionario_vendedor")[0].value;
        var garantia_produto = document.getElementsByName("garantia_produto")[0].value;

        if (
            nome_comprador === "" ||
            nome_peca === "" ||
            marca === "" ||
            ano === "" ||
            referencia === "" ||
            aplicacao === "" ||
            quantidade === "" ||
            cpf_cnpj === "" ||
            (cpf_cnpj === "CPF" && CPF === "") ||
            (cpf_cnpj === "CNPJ" && CNPJ === "") ||
            forma_pagamento === "" ||
            valor_venda === "" ||
            funcionario_vendedor === "" ||
            garantia_produto === ""
        ) {
            alert("Por favor, preencha todos os campos obrigatórios.");
            return false; // Impede o envio do formulário
        }
    }

    function validarCancelamentoVenda() {
        var nome_comprador = document.getElementsByName("nome_comprador")[0].value;
        var nome_peca = document.getElementsByName("nome_peca")[0].value;
        var marca = document.getElementsByName("marca")[0].value;
        var ano = document.getElementsByName("ano")[0].value;
        var referencia = document.getElementsByName("referencia")[0].value;
        var aplicacao = document.getElementsByName("aplicacao")[0].value;
        var funcionario_vendedor = document.getElementsByName("funcionario_vendedor")[0].value;
        var cpf_cnpj2 = document.getElementsByName("cpf_cnpj2")[0].value;
        var CPF2 = document.getElementsByName("CPF2")[0].value;
        var CNPJ2 = document.getElementsByName("CNPJ2")[0].value;

        if (
            nome_comprador === "" ||
            nome_peca === "" ||
            marca === "" ||
            ano === "" ||
            referencia === "" ||
            aplicacao === "" ||
            funcionario_vendedor === "" ||
            cpf_cnpj2 === "" ||
            (cpf_cnpj2 === "CPF2" && CPF2 === "") ||
            (cpf_cnpj2 === "CNPJ2" && CNPJ2 === "")
        ) {
            alert("Por favor, preencha todos os campos obrigatórios.");
            return false; // Impede o envio do formulário
        }
    }
</script>

</html>