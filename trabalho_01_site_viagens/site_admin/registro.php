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
    $cargo = trim($_POST['cargo'] ?? '');
    $data_contratacao = trim($_POST['data_contratacao'] ?? '');

    if (!empty($nome) && !empty($telefone) && !empty($email) && !empty($login) && !empty($senha) && !empty($cargo) && !empty($data_contratacao)) {
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
            $query = "INSERT INTO usuarios (nome, telefone, email, login, senha, cargo, data_contratacao) VALUES (:nome, :telefone, :email, :login, :senha, :cargo, :data_contratacao)";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':telefone', $telefone);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':login', $login);
            $stmt->bindParam(':senha', $senha);
            $stmt->bindParam(':cargo', $cargo);
            $stmt->bindParam(':data_contratacao', $data_contratacao);

            if ($stmt->execute()) {
                header("Location: login.php");
                exit;
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #0A2A5E 0%, #04122b 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 0;
        }
        .login-card {
            border: none;
            border-radius: 1.2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            background: #ffffff;
            overflow: hidden;
            width: 100%;
            max-width: 550px;
            animation: fadeIn 0.6s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .login-header {
            background: rgba(10, 42, 94, 0.03);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 2.5rem 1.5rem 1.5rem;
        }
        .login-header img {
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            border: 3px solid #fff;
            transition: transform 0.3s;
        }
        .login-header img:hover {
            transform: scale(1.05);
        }
        .form-control, .form-select {
            border-radius: 0.6rem;
            padding: 0.8rem 1.2rem;
            border: 1px solid #e0e0e0;
            background-color: #fbfbfb;
            transition: all 0.3s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #f0ad4e;
            background-color: #fff;
            box-shadow: 0 0 0 0.25rem rgba(240, 173, 78, 0.25);
        }
        .btn-login {
            background-color: #0A2A5E;
            color: #fff;
            border: none;
            border-radius: 0.6rem;
            padding: 0.8rem 1.2rem;
            font-weight: 600;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }
        .btn-login:hover {
            background-color: #f0ad4e;
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(240, 173, 78, 0.4);
        }
        .register-link {
            color: #6c757d;
            font-weight: 500;
            transition: color 0.3s;
        }
        .register-link:hover {
            color: #f0ad4e;
        }
        .form-label {
            font-weight: 600;
            color: #0A2A5E;
            margin-bottom: 0.4rem;
        }
        .input-group-text {
            background-color: #fbfbfb;
            border: 1px solid #e0e0e0;
            border-right: none;
            color: #0A2A5E;
        }
        .form-control.with-icon, .form-select.with-icon {
            border-left: none;
        }
        .form-control.with-icon:focus, .form-select.with-icon:focus {
            border-color: #e0e0e0;
            box-shadow: none;
        }
        .input-group:focus-within {
            border-radius: 0.6rem;
            box-shadow: 0 0 0 0.25rem rgba(240, 173, 78, 0.25);
        }
        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control,
        .input-group:focus-within .form-select {
            border-color: #f0ad4e;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center p-3">
        <div class="login-card">
            <div class="login-header text-center">
                <img src="../img/logo.png" alt="Logomarca" height="110" class="rounded-circle mb-3">
                <h4 class="mb-0 fw-bold" style="color: #0A2A5E;">Novo Funcionário</h4>
                <p class="text-muted small mt-1 mb-0">Preencha os dados abaixo para cadastrar</p>
            </div>
            
            <div class="p-4 p-md-5 pt-4">
                <?php if ($mensagem): ?>
                    <div class="mb-4">
                        <?php echo $mensagem; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="registro.php">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome Completo</label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-4"><i class="fas fa-id-card"></i></span>
                            <input type="text" class="form-control with-icon rounded-end-4" id="nome" name="nome" placeholder="Digite o nome completo" required>
                        </div>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="telefone" class="form-label">Telefone</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-4"><i class="fas fa-phone"></i></span>
                                <input type="text" class="form-control with-icon rounded-end-4" id="telefone" name="telefone" placeholder="(00) 0000-0000" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">E-mail</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-4"><i class="fas fa-envelope"></i></span>
                                <input type="email" class="form-control with-icon rounded-end-4" id="email" name="email" placeholder="email@exemplo.com" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="login" class="form-label">Login</label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-4"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control with-icon rounded-end-4" id="login" name="login" placeholder="Crie um usuário de acesso" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <label for="cargo" class="form-label">Cargo</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-4"><i class="fas fa-briefcase"></i></span>
                                <select class="form-select with-icon rounded-end-4" id="cargo" name="cargo" required>
                                    <option value="">Selecione...</option>
                                    <option value="Gerente">Gerente</option>
                                    <option value="Vendedor">Vendedor</option>
                                    <option value="Suporte">Suporte</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="data_contratacao" class="form-label">Data de Contratação</label>
                            <div class="input-group">
                                <span class="input-group-text rounded-start-4"><i class="fas fa-calendar-alt"></i></span>
                                <input type="date" class="form-control with-icon rounded-end-4" id="data_contratacao" name="data_contratacao" required>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="senha" class="form-label">Senha</label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-4"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control with-icon rounded-end-4" id="senha" name="senha" placeholder="Crie uma senha segura" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-login w-100 mb-4 mt-2">
                        <i class="fas fa-check-circle me-2"></i> Registrar Funcionário
                    </button>
                    
                    <div class="text-center mt-2 border-top pt-3">
                        <a href="login.php" class="text-decoration-none register-link">
                            <i class="fas fa-arrow-left me-1"></i> Voltar para o Login
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
