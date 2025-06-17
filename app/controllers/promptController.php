<?php

require 'config/paths.php';
require MODELS_PATH . 'database.php';

class PromptController
{
    public function createPrompt($title, $group, $content, $tags, $user)
    {
        $db = Connection::getInstance();
        $query = "SELECT * FROM pmt_cad_prom WHERE ID_GROUP = :group AND TITLE_PROMPT = :title AND CONTENT_PROMPT = :content AND ID_USER = :user";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':group' => $group,
            ':title' => $title,
            ':content' => $content,
            ':user' => $user
        ]);

        if ($stmt->rowCount() == 0) {
            $currentDate = date('Y-m-d');
            $sql_cad = "INSERT INTO pmt_cad_prom (ID_GROUP, ID_USER, TITLE_PROMPT, CONTENT_PROMPT, DATE_CREATED, DATE_MODIFIED, USAGE_CONT, RATNG, TAGS)
                    VALUES (:group, :user, :title, :content, :created, :modified, 1, 5, :tags)";
            $stmt = $db->prepare($sql_cad);
            $stmt->execute([
                ':group' => $group,
                ':user' => $user,
                ':title' => $title,
                ':content' => $content,
                ':created' => $currentDate,
                ':modified' => $currentDate,
                ':tags' => $tags
            ]);

            $_SESSION['message'] = 'Novo registro criado com sucesso!';
            $_SESSION['alert_type'] = 'success';  // Define o tipo de alerta
        } else {
            $_SESSION['message'] = 'Registro já existente.';
            $_SESSION['alert_type'] = 'warning';  // Define o tipo de alerta
        }
    }

    public function getGroups()
    {
        $db = Connection::getInstance();
        $query = "SELECT id_group, name_group FROM pmt_reg_grup";
        $stmt = $db->query($query);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
