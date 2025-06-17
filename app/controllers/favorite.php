<?php

require '../../config/paths.php';
require MODELS_PATH . 'database.php';

session_start();

if (isset($_POST['favorite']) && isset($_POST['id_prompt'])) {
    $id_prompt = $_POST['id_prompt'];
    $id_user = $_SESSION['id'];

    $db = Connection::getInstance();

    // Verificar se o prompt já foi favoritado
    $query = "SELECT * FROM pmt_fav_prom WHERE ID_USER = :id_user AND ID_PROMPT = :id_prompt";
    $stmt = $db->prepare($query);
    $stmt->execute([
        ':id_user' => $id_user,
        ':id_prompt' => $id_prompt
    ]);

    if ($stmt->rowCount() == 0) {
        // Inserir o novo favorito
        $query = "INSERT INTO pmt_fav_prom (ID_USER, ID_PROMPT, DATE_FAVORITED) VALUES (:id_user, :id_prompt, NOW())";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':id_user' => $id_user,
            ':id_prompt' => $id_prompt
        ]);

        $_SESSION['message'] = 'Prompt favoritado com sucesso!';
        $_SESSION['alert_type'] = 'success';  // Define o tipo de alerta
    } else {
        $_SESSION['message'] = 'Prompt já está nos favoritos.';
        $_SESSION['alert_type'] = 'warning';  // Define o tipo de alerta
    }
} else {
    $_SESSION['message'] = 'Erro ao favoritar o prompt.';
    $_SESSION['alert_type'] = 'danger';  // Define o tipo de alerta
}

header("Location: /inspirationAI/index.php");
exit();
?>
