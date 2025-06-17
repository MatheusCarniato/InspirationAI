<?php

require('../../config/paths.php');
require(MODELS_PATH . 'database.php');

$error_message = '';

if (isset($_POST['user_nick']) && isset($_POST['senha'])) {

    if (strlen($_POST['user_nick']) == 0) {
        $error_message = "Preencha seu usuário";
    } else if (strlen($_POST['senha']) == 0) {
        $error_message = "Preencha sua senha";
    } else {
        // Tratamento da SQL injection 
        $user_nick = $_POST['user_nick'];
        $senha = $_POST['senha'];

        $db = Connection::getInstance();
        $query = "SELECT * FROM pmt_reg_user WHERE nick_user = :user_nick AND password_user = :senha";
        $stmt = $db->prepare($query);
        $stmt->execute([
            ':user_nick' => $user_nick,
            ':senha' => $senha
        ]);

        if ($stmt->rowCount() == 1) {
            $user_data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!isset($_SESSION)) {
                session_start();
            }

            $_SESSION['id'] = $user_data['ID_USER'];
            $_SESSION['nick_user'] = $user_data['NICK_USER'];
            $_SESSION['name_user'] = $user_data['NAME_USER'];

            header("Location: /inspirationAI/index.php");
            exit();
        } else {
            $error_message = "Falha ao logar! Usuario ou senha incorretos";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.5.0/font/bootstrap-icons.min.css">
</head>

<body class="d-flex justify-content-center align-items-center bg-light" style="height: 100vh; margin: 0;">
    <div class="container p-5 bg-white rounded shadow login-container" style="max-width: 400px;">
        <div class="text-center mb-">
            <i class="bi bi-person-circle" style="font-size: 10rem; color: #007bff;"></i>
        </div>
        <h2 class="text-center mb-4">Login</h2>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?>
            </div>
        <?php endif; ?>
        <form method="post" action="">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Usuário" name="user_nick" required>
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Senha" name="senha" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Entrar</button>
        </form>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>