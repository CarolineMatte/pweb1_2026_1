<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
require_once 'db.class.php';

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $login = trim($_POST['login'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (!empty($nome) && !empty($telefone) && !empty($email) && !empty($login) && !empty($senha)) {
        $database = new Database();
        $db = $database->getConnection();

        // Verifica se login já existe
        $checkQuery = "SELECT id FROM usuarios WHERE login = :login";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bindParam(':login', $login);
        $checkStmt->execute();

        if ($checkStmt->rowCount() > 0) {
            $mensagem = "<div class='alert alert-danger'>O login informado já está em uso.</div>";
        } else {
            $query = "INSERT INTO usuarios (nome, telefone, email, login, senha) VALUES (:nome, :telefone, :email, :login, :senha)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':senha', $senha);

            if ($stmt->execute()) {
                $mensagem = "<div class='alert alert-success'>Funcionário cadastrado com sucesso! <br><a href='login.php' class='alert-link'>Clique aqui para fazer login</a>.</div>";
            } else {
                $mensagem = "<div class='alert alert-danger'>Erro ao cadastrar funcionário. Tente novamente.</div>";
            }
        }
    } else {
        $mensagem = "<div class='alert alert-warning'>Preencha todos os campos obrigatórios.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro Administrativo - BioViagens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="bg-light py-5">
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="card shadow-lg p-4" style="width: 100%; max-width: 500px;">
            <div class="text-center mb-4">
                <img src="../img/logo.png" alt="Logomarca" height="100" class="rounded-circle mb-3">
                <h4 class="mb-0">Cadastro de Funcionário</h4>
            </div>
            
            <?php echo $mensagem; ?>

            <form method="POST" action="registro.php">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome Completo *</label>
                    <input type="text" class="form-control" id="nome" name="nome" required>
                </div>
                <div class="mb-3">
                    <label for="telefone" class="form-label">Telefone *</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">E-mail *</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="login" class="form-label">Login *</label>
                    <input type="text" class="form-control" id="login" name="login" required>
                </div>
                <div class="mb-3">
                    <label for="senha" class="form-label">Senha *</label>
                    <input type="password" class="form-control" id="senha" name="senha" required>
                </div>
                <button type="submit" class="btn btn-success w-100 mb-3">Registrar</button>
                <div class="text-center">
                    <a href="login.php" class="text-decoration-none">Voltar para o Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
