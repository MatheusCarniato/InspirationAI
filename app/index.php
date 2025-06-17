<?php

require('config/paths.php');
require(CONTROLLERS_PATH . 'protect.php');
require(CONTROLLERS_PATH . 'promptController.php');

$controller = new PromptController();
$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title_prompt'];
    $group = $_POST['id_group'];
    $content = $_POST['content_prompt'];
    $tags = $_POST['tags'];
    $user = $_SESSION['id'];

    $message = $controller->createPrompt($title, $group, $content, $tags, $user);
}

$groups = $controller->getGroups();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
$alertType = isset($_SESSION['alert_type']) ? $_SESSION['alert_type'] : 'success';

unset($_SESSION['message']);
unset($_SESSION['alert_type']);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu de Grupos</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
</head>

<body class="d-flex flex-column" style="height: 100vh; margin: 0;">

    <!-- Navibar superior -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid d-flex justify-content-end">
            <a href="#">
                <img src="https://img.icons8.com/material-outlined/24/ffffff/picture-in-picture.png" alt="Picture-in-Picture Icon" title="Picture-in-Picture" class="ml-3">
            </a>
            <a href="app/controllers/logout.php">
                <img src="https://img.icons8.com/material-outlined/24/ffffff/exit.png" alt="Logout Icon" title="Logout" class="ml-3">
            </a>
        </div>
    </nav>

    <!-- Navibar lateral -->
    <div class="d-flex flex-grow-1">
        <div class="bg-primary text-white p-3" id="sidebar">
            <div id="toggleSidebar" onclick="toggleSidebar()" style="text-align: right;">
                <i class="bi bi-chevron-double-left"></i>
            </div>
            <a href="#" class="text-white mb-2 d-flex align-items-center" data-toggle="modal" data-target="#ModalCadastro">
                <i class="bi bi-file-earmark-plus-fill"></i>
                <span class="link-text" style="margin-left: 10px;">Cadastro de Prompts</span>
            </a>
            <a href="#" class="text-white mb-2 d-flex align-items-center" data-toggle="modal" data-target="#ModalFavorito">
                <i class="bi bi-star-fill"></i>
                <span class="link-text" style="margin-left: 10px;">Favoritos</span>
            </a>
        </div>

    <!-- Elementos da tela  -->
        <div class="flex-grow-1 p-4 d-flex flex-column align-items-center">
            <div class="greeting mb-4">
                <h1 class="text-center">Bem-vindo, <?php echo $_SESSION['name_user']; ?></h1>
            </div>
            <div id="mensagem"></div>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    <?php if ($message): ?>
                        showAlertInDiv('<?php echo addslashes($message); ?>', '<?php echo $alertType; ?>');
                    <?php endif; ?>
                });
            </script>
            <div class="w-100 row row-cols-1 row-cols-md-2 g-2">
                <?php
                foreach ($groups as $group) {
                    echo '<div class="col"> 
                            <button class="btn btn-primary w-100 mb-2" data-toggle="modal" data-target="#promptsModal" onclick="showPanoramaView(' . $group['id_group'] . ')">' . $group['name_group'] . '</button> 
                        </div>';
                }
                ?>
            </div>
        </div>
    </div>

    <!-- Modal Cadastro de Prompts -->
    <div class="modal fade" id="ModalCadastro" tabindex="-1" role="dialog" aria-labelledby="ModalCadastro" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Cadastro de Prompts</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="title">Título:</label>
                            <input type="text" class="form-control" id="title" name="title_prompt" required>
                        </div>
                        <div class="form-group">
                            <label for="group">Área:</label>
                            <select class="form-control" id="group" name="id_group" required>
                                <?php
                                foreach ($groups as $group) {
                                    echo '<option value="' . $group['id_group'] . '">' . $group['name_group'] . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="content">Prompt:</label>
                            <textarea class="form-control" id="content" name="content_prompt" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="tags">Tags:</label>
                            <input type="text" class="form-control" id="tags" name="tags">
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Favoritos -->
    <div class="modal fade" id="ModalFavorito" tabindex="-1" role="dialog" aria-labelledby="ModalFavorito" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ModalLongTitle">Seus Favoritos</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container my-4 flex-grow-1 d-flex flex-column align-items-center">
                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>Título do Prompt</th>
                                        <th>Data Favoritado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $id_user = $_SESSION['id'];
                                    $db = Connection::getInstance();
                                    $query = "SELECT pmt_cad_prom.title_prompt, pmt_fav_prom.date_favorited, pmt_cad_prom.id_prompt, pmt_cad_prom.content_prompt
                                          FROM pmt_fav_prom
                                          JOIN pmt_cad_prom ON pmt_fav_prom.ID_PROMPT = pmt_cad_prom.id_prompt
                                          WHERE pmt_fav_prom.ID_USER = :id_user";
                                    $stmt = $db->prepare($query);
                                    $stmt->execute([':id_user' => $id_user]);
                                    $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

                                    foreach ($favorites as $favorite) {
                                        echo '<tr data-toggle="modal" data-target="#promptDetailsModal" onclick="showPromptDetails(\'' . $favorite['title_prompt'] . '\', \'' . $favorite['content_prompt'] . '\', \'' . $favorite['id_prompt'] . '\')">
                                          <td>' . htmlspecialchars($favorite['title_prompt'], ENT_QUOTES, 'UTF-8') . '</td>
                                          <td>' . htmlspecialchars($favorite['date_favorited'], ENT_QUOTES, 'UTF-8') . '</td>
                                          </tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de cada área (Grupos) -->
    <div class="modal fade" id="promptsModal" tabindex="-1" role="dialog" aria-labelledby="promptsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="promptsModalLabel">Prompts do Grupo Selecionado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="table-responsive">
                    <div class="table-view">
                        <table class="table">
                            <thead class="bg-primary text-white">
                                <tr>
                                    <th>ID</th>
                                    <th>Título</th>
                                    <th>Data Registro</th>
                                    <th>Última Alteração</th>
                                    <th>Utilização</th>
                                    <th>Pontuação</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal de Detalhes do Prompt -->
    <div class="modal fade" id="promptDetailsModal" tabindex="-1" role="dialog" aria-labelledby="promptDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="promptDetailsModalLabel">Detalhes do Prompt</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h3 class="text-center" id="prompt-title">Título do Prompt</h3>
                    <p id="prompt-content">Conteúdo do Prompt</p>
                    <div class="d-flex justify-content-around">
                        <form method="POST" action="app/controllers/favorite.php">
                            <input type="hidden" name="id_prompt" id="id_prompt">
                            <button type="submit" class="btn btn-primary" name="favorite">Favoritar</button>
                        </form>
                        <button class="btn btn-secondary" onclick="copPromptsText()">Copiar</button>
                        <button class="btn btn-secondary" onclick="returnToPromptsModal()">Voltar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Chamadas JS, ajax e Bootstrap -->
    <script src="/inspirationAI/public/js/script.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>