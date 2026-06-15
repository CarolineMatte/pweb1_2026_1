<?php
session_start();
if (isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit;
}
require_once 'db.class.php';

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login'] ?? '');
    $senha = trim($_POST['senha'] ?? '');

    if (!empty($login) && !empty($senha)) {
        $database = new Database();
        $db = $database->getConnection();

        $query = "SELECT id, nome, senha FROM usuarios WHERE login = :login LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':login', $login);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($senha === $row['senha']) {
                $_SESSION['usuario_id'] = $row['id'];
                $_SESSION['usuario_nome'] = $row['nome'];
                header("Location: index.php");
                exit;
            } else {
                $erro = "Senha incorreta.";
            }
        } else {
            $erro = "Usuário não encontrado.";
        }
    } else {
        $erro = "Preencha todos os campos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrativo - BioViagens</title>
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
        }
        .login-card {
            border: none;
            border-radius: 1.2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            background: #ffffff;
            overflow: hidden;
            width: 100%;
            max-width: 420px;
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
        .form-control {
            border-radius: 0.6rem;
            padding: 0.8rem 1.2rem;
            border: 1px solid #e0e0e0;
            background-color: #fbfbfb;
            transition: all 0.3s;
        }
        .form-control:focus {
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
        .form-control.with-icon {
            border-left: none;
        }
        .form-control.with-icon:focus {
            border-color: #e0e0e0; /* manter borda original */
            box-shadow: none; /* sombra vai pro input-group no foco manual */
        }
        .input-group:focus-within {
            border-radius: 0.6rem;
            box-shadow: 0 0 0 0.25rem rgba(240, 173, 78, 0.25);
        }
        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: #f0ad4e;
        }
    </style>
</head>
<body>
    <div class="container d-flex justify-content-center p-3">
        <div class="login-card">
            <div class="login-header text-center">
                <img src="../img/logo.png" alt="Logomarca" height="110" class="rounded-circle mb-3">
                <h4 class="mb-0 fw-bold" style="color: #0A2A5E;">Acesso Restrito</h4>
                <p class="text-muted small mt-1 mb-0">Insira suas credenciais para continuar</p>
            </div>
            
            <div class="p-4 p-md-5 pt-4">
                <?php if ($erro): ?>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        <div><?php echo $erro; ?></div>
                    </div>
                <?php endif; ?>

                <form method="POST" action="login.php">
                    <div class="mb-4">
                        <label for="login" class="form-label">Usuário</label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-4"><i class="fas fa-user"></i></span>
                            <input type="text" class="form-control with-icon rounded-end-4" id="login" name="login" placeholder="Digite seu login" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label for="senha" class="form-label">Senha</label>
                        <div class="input-group">
                            <span class="input-group-text rounded-start-4"><i class="fas fa-lock"></i></span>
                            <input type="password" class="form-control with-icon rounded-end-4" id="senha" name="senha" placeholder="Digite sua senha" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-login w-100 mb-4 mt-2">
                        <i class="fas fa-sign-in-alt me-2"></i> Entrar no Sistema
                    </button>
                    
                    <div class="text-center mt-2 border-top pt-3">
                        <a href="registro.php" class="text-decoration-none register-link">
                            <i class="fas fa-user-plus me-1"></i> Cadastrar Novo Funcionário
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
