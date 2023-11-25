<?php
header('Content-Type: text/html; charset=utf-8');
require_once __DIR__.'/NFe.php';
require_once "config.php";
use WebmaniaBR\NFe;



if ($_SERVER["REQUEST_METHOD"] == "GET") {
  $data_nota = $_GET['data'];
  $nome_cliente = $_GET['nome'];

  // Prepara a consulta usando prepared statements
  $stmt = $conn->prepare("SELECT uuid FROM cancelar_nota_fiscal WHERE data_nota = ? AND nome_cliente = ?");
  
  // Vincula os parâmetros
  $stmt->bind_param("ss", $data_nota, $nome_cliente);
  
  // Executa a consulta
  $stmt->execute();
  
  // Obtém o resultado
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
      $notas = array(); // Inicializa um array para armazenar os detalhes da ordem de serviço

      while ($row = $result->fetch_assoc()) {
          // Armazena cada linha no array de detalhes da ordem de serviço
          $notas[] = $row;
      }

      foreach ($notas as $nota) {
          $chaveuuid = $nota["uuid"];
      }
  }

  // Fecha a declaração
  $stmt->close();

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
