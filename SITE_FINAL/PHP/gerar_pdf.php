<?php
require 'vendor/autoload.php'; // Carregue o Composer autoload

use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isPhpEnabled', true);
$dompdf = new Dompdf($options);

$html = '<!DOCTYPE html>
<html>

<head>
    <title>Download_O.S</title>
    <style>
            *{
        margin: 0px;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, Helvetica, sans-serif;
    }

    body{
        margin: 30px; 
        color: #033d20;
    }

    /* Cabeçalho, logo, nome da empresa e etc */
    .cabecalho{
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .div_direita_img{
        display: flex;
        align-items: center;
    }

    .div_direita_img img{
        width: 150px;
    }

    .div_direita{
        margin-left: 20px;
    }

    /* Endereço */
    .cabecalho_2{
        text-align: center;
    }

    /* Numerod da Venda */
    .cabecalho_2 p{
        font-size: 14pt;
    }


    .line{
        margin: 10px 0px;
        border: 2px solid #09562F;
    }


    /* Cliente */
    .cliente{
        margin: 20px 0px;
    }
    .cliente p{
        font-size: 14pt;
    }

    /* Tabela  - relação dos Produtos */
    .relacao_produtos{
        margin: 10px 0px 10px 0px;
    }

    /* Títulos */
    .relacao_produtos th{
        padding: 5px;
        margin: 0px;
        background-color: #09562F;
        color: white;
    }

    /* Dados */
    .relacao_produtos td{
        background-color:#ddd;
        padding: 5px;
    }

    /* Ordenando tamanho das tuplas */
    #grande{
        width: 700px;   
    }
    #pequeno{
        width: 100px;
    }

    .rodape_relacao_dos_produtos{
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }

    /* Tabela total */
    .total_geral{
        margin: 10px 0px 10px 0px;
        display: flex;
        justify-content: end;
    }

    /* Títulos */
    .total_geral th{
        padding: 5px;
        margin: 0px;
        background-color: #09562F;
        color: white;
    }

    /* Dados */
    .total_geral td{
        background-color:#ddd;
        padding: 5px;
    }


    /* Tabela pagamentos */
    .pagamentos{
        margin: 10px 0px 10px 0px;
    }

    /* Títulos */
    .pagamentos th{
        padding: 5px;
        margin: 0px;
        background-color: #09562F;
        color: white;
    }

    /* Dados */
    .pagamentos td{
        background-color:#ddd;
        padding: 5px;
    }

    /* Ordenando tamanho das tuplas */
    #pg_grande{
        width: 700px;   
    }
    #pg_pequeno{
        width: 100px;
    }

    .rodape_pagamentos{
        display: flex;
        justify-content: space-between;
        margin-bottom: 20px;
    }


    /* Tabela de Duplicatas */
    .duplicatas{
        margin: 10px 0px 10px 0px;
    }

    /* Títulos */
    .duplicatas th{
        padding: 5px;
        margin: 0px;
        background-color: #09562F;
        color: white;
    }

    /* Dados */
    .duplicatas td{
        background-color:#ddd;
        padding: 5px;
    }

    #dp_grande{
    width: 200px;
    }

    /* Rodape */
    .rodape{
        margin: 30px 0px 30px 0px;
    }

    footer{
        font-weight: bold;
    }
    </style>
</head>

<body>

    <div class="cabecalho">
        <div class="div_direita_img">
            <div class="div_direita">
                <h1>PDT P GOMES</h1>
                <p>VENDA RÁPIDA (AUTORIZADA)</p>
                <p>Data da venda: 14/10/2023</p>
            </div>
        </div>

        <div class="div_esquerda">
            <h3>RZSystem</h3>
            <p>Data/Hora: 14/10/2023 12:19:07</p>
            <p>Usuário: PAULO THALLYS DE MEDEIROS GOMES</p>
        </div>
    </div>

    <div class="cabecalho_2">
        <div class="line"></div>
        <p>AV. WILSON ROSADO - 6 - ALTO SUMARE - Mossoró - RN</p>
        <p>CNPJ/CPF: 05236182000118 - Inscrição Estadual: 200920383 - Fone: 8433121151</p>
        <div class="line"></div>
        <h2>
            Nº VENDA: 013344 * DATA: 14/10/2023 12:18:36 * VENDEDOR: THALLYS
        </h2>
    </div>

    <div class="cliente">
        <div class="cliente_esquerda">
            <h3>Cliente</h3>
            <p>Nome: BOX CAR CENTRO AUTOMOTIVO - CNPJ/CPF: 32593037000167 - I.E: 205088465</p>
            <p>Nome Fantasia: BOX CAR CENTRO AUTOMOTIVO</p>
            <p>Endereço: AVENIDA PRESIDENTE DUTRA,436 ALTO DE SÃO MANOEL CEP: 59628000 MOSSORO/RN</p>
            <p>Pto. Ref.: - Email: - Fone: 84991358362</p>
            <p>Data Cadastro: 21/03/2023 - Data Última Venda: 14/10/2023</p>
            <p>OBS.:</p>
        </div>
    </div>

    <div class="relacao_dos_produtos">
        <h3>Relação dos Produtos</h3>
        <table class="relacao_produtos">
            <tr>
                <th id="pequeno">Cód.</th>
                <th id="grande">Produto</th>
                <th id="pequeno">NCM</th>
                <th id="pequeno">Ref.</th>
                <th id="pequeno">Tipo</th>
                <th id="pequeno">Quant.</th>
                <th id="pequeno">Preço(UN)</th>
                <th id="pequeno">Valor Item</th>
            </tr>

            <tr>
                <td>1</td>
                <td>Produto A</td>
                <td>123456</td>
                <td>REF001</td>
                <td>Tipo 1</td>
                <td>10</td>
                <td>R$ 20.00</td>
                <td>R$ 200.00</td>
            </tr>
            <tr>
                <td>2</td>
                <td>Produto B</td>
                <td>789012</td>
                <td>REF002</td>
                <td>Tipo 2</td>
                <td>5</td>
                <td>R$ 15.00</td>
                <td>R$ 75.00</td>
            </tr>
        </table>

    </div class="relacao_dos_produtos">

    <div class="rodape_relacao_dos_produtos">
        <h3>Total dos produtos:</h3>
        <h3>Valor Item</h3>
    </div>


    <div class="total_geral">
        <table>
            <tr>
                <th>Descrição</th>
                <th>Valor</th>
            </tr>
            <tr>
                <td>Total Geral:</td>
                <td>1000.00</td>
            </tr>
            <tr>
                <td>Desconto:</td>
                <td>100.00</td>
            </tr>
            <tr>
                <td>Frete:</td>
                <td>50.00</td>
            </tr>
            <tr>
                <td>Total Liquido:</td>
                <td>950.00</td>
            </tr>
        </table>
    </div>


    <div class="pagamentos">
        <h3>PAGAMENTOS</h3>
        <table>
            <tr>
                <th id="pg_grande">Modalidade</th>
                <th id="pg_grande">Condição de Pagamento</th>
                <th id="pg_pequeno">Valor</th>
            </tr>
            <tr>
                <td>Boleto</td>
                <td>À vista</td>
                <td>R$ 100,00</td>
            </tr>
            <tr>
                <td>Cartão de Crédito</td>
                <td>Parcelado em 3 vezes</td>
                <td>R$ 150,00</td>
            </tr>
            <tr>
                <td>Pix</td>
                <td>À vista</td>
                <td>R$ 90,00</td>
            </tr>
        </table>
    </div>
    <div class="rodape_pagamentos">
        <h3>Total dos Pagamentos:</h3>
        <h3>Valor</h3>
    </div>


    <div class="duplicatas">
        <h3>DUPLICATAS</h3>
        <table>
            <thead>
                <tr>
                    <th id="dp_grande">Título</th>
                    <th id="dp_grande">Vencimento</th>
                    <th id="dp_grande">Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Duplicata 1</td>
                    <td>01/11/2023</td>
                    <td>R$ 1,000.00</td>
                </tr>
                <tr>
                    <td>Duplicata 2</td>
                    <td>15/11/2023</td>
                    <td>R$ 750.50</td>
                </tr>
                <tr>
                    <td>Duplicata 3</td>
                    <td>30/11/2023</td>
                    <td>R$ 1,500.75</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="rodape">
        <h3>Autorizado por: (012 PAULO THALLYS DE MEDEIROS GOMES) 14/10/2023 12:19:00</h3>
        <p>*Produtos pendentes de entrega</p>
    </div>

    <footer>RZ CODE</footer>
</body>

</html>';

// Carregue o HTML no Dompdf
$dompdf->loadHtml($html);

// Renderize o PDF (renderização)
$dompdf->render();

// Envie o PDF gerado para o navegador ou salve em um arquivo
$dompdf->stream('documento.pdf');
