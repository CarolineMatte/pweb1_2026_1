<?php
session_start();
require_once 'db.class.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $database = new DB();
    $db = $database->getConnection();

    $query = "SELECT * FROM usuarios WHERE login = :login AND senha = :senha LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':senha', $senha);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nome'] = $user['nome'];
        header("Location: index.php");
        exit();
    } else {
        $erro = "Login ou senha inválidos!";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Admin BioViagens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; }
        .login-container { max-width: 400px; margin-top: 100px; }
    </style>
</head>
<body>
    <div class="container login-container">
        <div class="card shadow">
            <div class="card-body text-center">
                <img src="../img/logo.png" alt="Logomarca" height="80" class="rounded-circle mb-3">
                <h4 class="card-title mb-4">Acesso Restrito - Admin</h4>
                <?php if (isset($erro)): ?>
                    <div class="alert alert-danger"><?php echo $erro; ?></div>
                <?php endif; ?>
                <form method="POST" action="">
                    <div class="mb-3 text-start">
                        <label for="login" class="form-label">Login</label>
                        <input type="text" class="form-control" id="login" name="login" required>
                    </div>
                    <div class="mb-3 text-start">
                        <label for="senha" class="form-label">Senha</label>
                        <input type="password" class="form-control" id="senha" name="senha" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Entrar</button>
                    <a href="../index.html" class="btn btn-link mt-2">Voltar para o site</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
