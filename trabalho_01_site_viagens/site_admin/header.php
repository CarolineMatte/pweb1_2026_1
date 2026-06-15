<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!defined('BASE_PATH')) {
    define('BASE_PATH', '.');
}

if (!isset($_SESSION['usuario_id'])) {
    header("Location: " . BASE_PATH . "/login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - BioViagens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_PATH; ?>/../css/style.css">
</head>
<body class="bg-light">
    <header class="py-3 border-bottom mb-4 bg-white">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo">
                <a href="<?php echo BASE_PATH; ?>/index.php">
                    <img src="<?php echo BASE_PATH; ?>/../img/logo.png" alt="Logomarca" height="80" class="rounded-circle">
                </a>
            </div>

            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar" aria-controls="adminNavbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="adminNavbar">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_PATH; ?>/index.php">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_PATH; ?>/usuario/UsuarioList.php">Usuários</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_PATH; ?>/destino/DestinoList.php">Destinos</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_PATH; ?>/cliente/ClienteList.php">Clientes</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?php echo BASE_PATH; ?>/reserva/ReservaList.php">Reservas</a></li>
                            <li class="nav-item"><a class="nav-link text-danger fw-bold" href="<?php echo BASE_PATH; ?>/logout.php">Sair</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <main class="container my-4">
