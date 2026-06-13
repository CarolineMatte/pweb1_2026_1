<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    $path = explode('/site_admin/', $_SERVER['REQUEST_URI']);
    $base_url = rtrim($path[0], '/') . '/site_admin';
    header("Location: $base_url/login.php");
    exit();
}

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
$domainName = $_SERVER['HTTP_HOST'];
$pathParts = explode('/site_admin', $_SERVER['REQUEST_URI']);
$baseAdminUrl = rtrim($protocol . $domainName . $pathParts[0], '/') . '/site_admin';
$baseSiteUrl = rtrim($protocol . $domainName . $pathParts[0], '/');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - BioViagens</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="<?= $baseSiteUrl ?>/css/style.css">
    <style>
        .admin-wrapper { min-height: 80vh; }
    </style>
</head>
<body>
    <header class="py-3 border-bottom bg-white shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <div class="logo">
                <a href="<?= $baseAdminUrl ?>/index.php">
                    <img src="<?= $baseSiteUrl ?>/img/logo.png" alt="Logomarca BioViagens" height="60" class="rounded-circle">
                </a>
            </div>
            
            <nav class="navbar navbar-expand-lg navbar-light">
                <div class="container-fluid">
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAdmin">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNavAdmin">
                        <ul class="navbar-nav align-items-center">
                            <li class="nav-item"><a class="nav-link" href="<?= $baseAdminUrl ?>/index.php">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= $baseAdminUrl ?>/usuario/UsuarioList.php">Usuários</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= $baseAdminUrl ?>/destino/DestinoList.php">Destinos</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= $baseAdminUrl ?>/cliente/ClienteList.php">Clientes</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= $baseAdminUrl ?>/reserva/ReservaList.php">Reservas</a></li>
                            <li class="nav-item ms-3">
                                <span class="badge bg-secondary">Olá, <?= htmlspecialchars($_SESSION['user_nome']) ?></span>
                            </li>
                            <li class="nav-item ms-2"><a class="btn btn-outline-danger btn-sm" href="<?= $baseAdminUrl ?>/logout.php">Sair</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <main class="container my-4 admin-wrapper">
