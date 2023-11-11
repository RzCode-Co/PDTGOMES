<?php
// Inicialize a sessão, se ainda não estiver inicializada
session_start();

// Encerre a sessão
session_destroy();

// Redirecione o usuário de volta para a página de login ou qualquer outra página desejada
header("Location: ../HTML/index.html"); // Altere para a página desejada
exit;
?>
