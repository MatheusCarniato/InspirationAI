<?php

require '../../config/paths.php';
require MODELS_PATH . 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $groupId = $_POST['id_group'];

    // Tratamento da SQL injection
    $db = Connection::getInstance();
    
    // Verifica se a conexão com o banco foi bem-sucedida
    if ($db === null) {
        echo json_encode(['error' => 'Erro ao conectar ao banco de dados.']);
        exit;
    }

    try {
        $stmt = $db->prepare("SELECT id_prompt, title_prompt, content_prompt, date_created, date_modified, usage_cont, ratng FROM pmt_cad_prom WHERE id_group = :group");
        $stmt->execute([':group' => $groupId]);

        $prompts = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($prompts);

    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
} else {
    $_SESSION['message'] = 'Método de requisição inválido. Use POST.';
    $_SESSION['alert_type'] = 'danger';  // Define o tipo de alerta
}

?>

