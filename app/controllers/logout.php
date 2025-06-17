<?php 

if(!isset($_SESSION)) {
    session_start();
}

// Regenera o ID da sessão para evitar ataques de fixação de sessão
session_regenerate_id(true);

session_destroy();

header("Location: ../views/login.php");
exit();

?>
