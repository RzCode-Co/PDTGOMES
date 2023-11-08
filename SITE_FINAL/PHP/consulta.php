<?php
require_once('config.php'); // Inclua o arquivo de configuração do banco de dados
require_once('../tcpdf/tcpdf.php'); // Inclua a biblioteca TCPDF

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

// Inicialize o objeto TCPDF
$pdf = new TCPDF();
$pdf->SetMargins(10, 10, 10);
$pdf->AddPage();

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

        // Crie a tabela de débitos no PDF
        $pdf->SetFont('helvetica', '', 12);
        $pdf->Cell(0, 10, 'Débitos do Cliente', 0, 1, 'C');
        $pdf->Cell(0, 10, 'Mês: ' . $nomeMes, 0, 1);

        // Tabela de débitos
        $pdf->Ln();
        $pdf->SetFont('helvetica', 'B');
        $pdf->Cell(20, 10, 'ID', 1);
        $pdf->Cell(25, 10, 'Nome', 1);
        $pdf->Cell(30, 10, 'Nome da Peça', 1);
        $pdf->Cell(25, 10, 'Quantidade', 1);
        $pdf->Cell(25, 10, 'Data', 1);
        $pdf->Ln();

        while ($row = $result->fetch_assoc()) {
            $pdf->Cell(20, 10, $row['id'], 1);
            $pdf->Cell(25, 10, $row['nome_comprador'], 1);
            $pdf->Cell(30, 10, $row['nome_peca'], 1);
            $pdf->Cell(25, 10, $row['quantidade'], 1);
            $pdf->Cell(25, 10, $row['data_venda'], 1);
            
            $pdf->Ln();
        }

        // Consulta para verificar débitos na tabela "vendas" para CPF ou CNPJ
        $sql = "SELECT * FROM vendas WHERE MONTH(data_venda) = ? AND (CPF = ? OR CNPJ = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('iss', $mes, $clienteCPF, $clienteCNPJ);
        $stmt->execute();
        $result = $stmt->get_result();
        $pdf->Ln();
        if ($clienteCPF!== '0') {
            $pdf->Cell(40, 10, 'CPF', 1);
        }
        if ($clienteCNPJ !== '0') {
            $pdf->Cell(40, 10, 'CNPJ', 1);
        }
        $pdf->Cell(40, 10, 'Valor da Venda', 1);
        while ($row = $result->fetch_assoc()) {
            $pdf->Ln();
            // Verifique e mostre o CPF apenas se não for zero
            if ($row['CPF'] !== '0') {
                $pdf->Cell(40, 10, $row['CPF'], 1);
            }
            
            // Verifique e mostre o CNPJ apenas se não for zero
            if ($row['CNPJ'] !== '0') {
                $pdf->Cell(40, 10, $row['CNPJ'], 1);
            }
            $pdf->Cell(40, 10, 'R$ ' . number_format($row['valor_venda'], 2, ',', '.'), 1);
        }
        // Saída do PDF para o cliente
        $pdf->Output('debitos_cliente.pdf', 'D');
    } else {
        // Mês inválido
        echo "Mês inválido.";
    }
}
