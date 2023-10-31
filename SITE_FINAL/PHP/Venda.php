
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

                <li class="item_menu ativo">
                    <a href="../HTML/pagina_incial.html">
                        <img class="icon" src="../CSS/img/Logo Circular verde.svg" alt="logo">
                        <span class="txt_link">Home</span>
                    </a>
                </li>

                <li class="item_menu">
                    <a href="Venda.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">
                        <img class="icon" src="../CSS/img/VENDAS.svg" alt="icone compras">
                        <span class="txt_link">Vendas</span>
                    </a>
                </li>

                <li class="item_menu">
                    <a href="estoque.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">
                        <img class="icon" src="../CSS/img/Compras.svg" alt="icone compras">
                        <span class="txt_link">Estoque</span>
                    </a>
                </li>

                <li class="item_menu">
                    <a href="Graficos.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">
                        <img class="icon" src="../CSS/img/Gráficos.svg" alt="icone graficos">
                        <span class="txt_link">Gráficos</span>
                    </a>
                </li>

                <li class="item_menu">
                    <a href="Financeiro.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">
                        <img class="icon" src="../CSS/img/Carteira.svg" alt="icone carteira">
                        <span class="txt_link">Carteira</span>
                    </a>
                </li>

                <li class="item_menu">
                    <a href="Criação OS.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">
                        <img class="icon" src="../CSS/img/OS.svg" alt="icone OS">
                        <span class="txt_link">O.S</span>
                    </a>
                </li>

                <li class="item_menu">
                    <a href="#">
                        <img class="icon" src="../CSS/img/Perfil.svg" alt="icone perfil">
                        <span class="txt_link">Perfil</span>
                    </a>
                </li>

                <li class="item_menu">
                    <a href="Notificações.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>">
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
                <li id="logo_menu_horizontal"><a href="../HTML/pagina_incial.html"><img
                            src="../CSS/img/Logo Horizontal.png" alt="logo da empresa"></a>
                </li>

                <li id="direita">

                    <!-- Perfil -->
                    <div class="image_container">
                        <?php echo '<img src="' . $arquivo . '" alt="Foto do Usuário">';?>
                    </div>

                    <script src="../JS/login_preview.js"></script>
                </li>

                <li id="direita">
                    <!-- Cargo e nome -->
                    <div class="cargo_nome">
                    <?php echo '<p>' . $cargo . '</p>';?>
                    <?php echo '<p>' . $nome . '</p>';?>
                    </div>
                </li>

                <li id="direita"><a href="Notificações.php?id=<?php echo $idUsuario; ?>&cargo=<?php echo $cargo ?>"><img src="../CSS/img/Sino_menu_horizontal.svg" alt="Notificações"></a></li>

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
                    <form class="form_vendas" action="../PHP/processar_venda.php" method="post">


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
                    <form class="form_vendas" action="../PHP/processar_cancelar_venda.php" method="post">

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
    }
    function mostrarFazerVenda() {
        document.getElementById("fazer-venda").style.display = "block";
        document.getElementById("cancelar-venda").style.display = "none";
    }
    function mostrarCancelarVenda() {
        document.getElementById("fazer-venda").style.display = "none";
        document.getElementById("cancelar-venda").style.display = "block";
    }
</script>

</html>