<?php
require_once('config.php'); // Inclua o arquivo de configuração do banco de dados

$meses = array(
    1 => 'Janeiro',
    2 => 'Fevereiro',
    3 => 'Março',
    4 => 'Abril',
    5 => 'Maio',
    6 => 'Junho',
    7 => 'Julho',
    8 => 'Agosto',
    9 => 'Setembro',
    10 => 'Outubro',
    11 => 'Novembro',
    12 => 'Dezembro'
);

// Verifique se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $mes = $_POST['mes'];
    $clienteCPF = $_POST['clienteCPF'];
    $clienteCNPJ = $_POST['clienteCNPJ'];

    // Verifique se o valor de $mes está dentro do intervalo válido (1 a 12)
    if (array_key_exists($mes, $meses)) {
        $nomeMes = $meses[$mes]; // Obtenha o nome do mês correspondente

        // Consulta para verificar débitos na tabela "vendas" para CPF ou CNPJ
        $sql = "SELECT * FROM vendas WHERE MONTH(data_venda) = ? AND (CPF = ? OR CNPJ = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iss', $mes, $clienteCPF, $clienteCNPJ);
        $stmt->execute();
        $result = $stmt->get_result();
    }else {
        // Mês inválido
        echo "Mês inválido.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gerar PDF de Débitos</title>
    <!-- Inclua a biblioteca jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.68/vfs_fonts.js"></script>
</head>
<body>
    <h1>Gerar PDF de Débitos</h1>
        <?php
        echo"<form id='formulario' action='consulta.php' method='post'>";
        echo "<input type='hidden' name='clienteCNPJ' value='$clienteCNPJ'>";
        echo "<input type='hidden' name='clienteCPF' value='$clienteCPF'>";
        echo "<input type='hidden' name='mes' value='$mes'>";
        echo "<input type='submit' value='Gerar Boleto'>";
        echo"</form>";
        ?>
</body>
<script>
        document.getElementById('gerarPDF').addEventListener('click', function() {
            // Consulte o servidor para obter as informações dos débitos
            // Substitua a URL pela URL correta para sua consulta
            fetch('consulta.php', {
                method: 'POST',
                body: new FormData(document.querySelector('form')),
            })
            .then(response => response.json())
            .then(data => {
                var doc = new jsPDF();

                // Inicialize a posição vertical
                var yPos = 10;

                // Adicione os dados do débito ao PDF
                data.forEach(debito => {
                    doc.text("ID: " + debito.id, 10, yPos);
                    yPos += 10;
                    doc.text("Nome: " + debito.nome_comprador, 10, yPos);
                    yPos += 10;
                    doc.text("Nome da Peça: " + debito.nome_peca, 10, yPos);
                    yPos += 10;
                    doc.text("Quantidade: " + debito.quantidade, 10, yPos);
                    yPos += 10;
                    doc.text("Data de Venda: " + debito.data_venda, 10, yPos);
                    yPos += 10;
                    doc.text("CPF: " + debito.CPF, 10, yPos);
                    yPos += 10;
                    doc.text("CNPJ: " + debito.CNPJ, 10, yPos);
                    yPos += 10;
                    doc.text("Valor de Venda: R$ " + debito.valor_venda, 10, yPos);
                    yPos += 10;
                    doc.text("-----------------------------------------------------", 10, yPos);
                    yPos += 10;
                });

                // Salve ou abra o PDF
                doc.save('debitos_cliente.pdf');
            });
        });
</script>
</html>
