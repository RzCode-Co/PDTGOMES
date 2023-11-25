<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__.'/NFe.php';
use WebmaniaBR\NFe;



if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $data_nota = $_GET['data'];
  $nome_cliente = $_GET['nome'];
/**
* Cancelar Nota Fiscal
*
* Atenção: Somente poderá ser cancelada uma NF-e cujo uso tenha sido previamente
* autorizado pelo Fisco e desde que não tenha ainda ocorrido o fato gerador, ou seja,
* ainda não tenha ocorrido a saída da mercadoria do estabelecimento. Atualmente o prazo
* máximo para cancelamento de uma NF-e é de 24 horas (1 dia), contado a partir da autorização
* de uso. Caso já tenha passado o prazo de 24 horas ou já tenha ocorrido a circulação da
* mercadoria, emita uma NF-e de devolução para anular a NF-e anterior.
*/
$sql = "SELECT uuid FROM cancelar_nota_fiscal WHERE data_nota = $data_nota AND nome_cliente = $nome_cliente";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  $notas = array(); // Inicializa um array para armazenar os detalhes da ordem de serviço

  while ($row = $result1->fetch_assoc()) {
      // Armazena cada linha no array de detalhes da ordem de serviço
      $notas[] = $row;
  }
  foreach ($notas as $nota) {
      $chaveuuid = $nota["uuid"];
    }
}
$chave_uuid = $chaveuuid; // Chave ou UUID
$motivo = 'Cancelamento por cancelamento de compra';
}
/**
* Solicitação do cancelamento
*/
$webmaniabr = new NFe('muDrdWTocAO1i1wYprEAUMH5Qj4SOWGP', 'UeqKZoZXADQVuH7oIgrKsjFDUQNwcf2uI1PqfSiNDsscUDkh', '4308-7lG0HND6iJ8Z7P8TY4vSr944RglEJgDl6N9a4kYruuprh73B', ' J4Lc8spnRF1IB3MlNMDOJRGB13BodYBGf5ndeosKb2ppFzGm');
$response = $webmaniabr->cancelarNotaFiscal( $chave_uuid, $motivo );

/**
* Retorno
*/
if (!isset($response->error)){

  echo '<h2>Resultado do Cancelamento:</h2>';

  $status = (string) $response->status;
  $xml = (string) $response->xml;
  $log = $response->log;

  print_r($response);
  exit();

} else {

  echo '<h2>Erro: '.$response->error.'</h2>';

  if (isset($response->log)){

    echo '<h2>Log:</h2>';
    echo '<ul>';

    foreach ($response->log as $erros){
      foreach ($erros as $erro) {
        echo '<li>'.$erro.'</li>';
      }
    }

    echo '</ul>';

  }

  exit();

}
