<?php
// Verifica se a sessão está iniciada, se não, inicia a sessão
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Verifica se a variável de sessão 'id' está definida
if (!isset($_SESSION['id'])) {
    // Redireciona para a página principal e encerra o script
    header("Location:/inspirationAI/app/views/login.php");
    exit;
}
?>



