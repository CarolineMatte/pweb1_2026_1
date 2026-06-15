<?php
ob_start();
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
    <style>
        .sidebar {
            background: linear-gradient(180deg, #0A2A5E 0%, #061d42 100%);
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
            z-index: 1000;
        }
        .sidebar .nav-link {
            border-radius: 0.5rem;
            transition: all 0.3s ease;
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 500;
            padding: 0.8rem 1rem;
        }
        .sidebar .nav-link i {
            color: #f0ad4e;
            width: 25px;
            text-align: center;
            opacity: 0.9;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: #f0ad4e !important;
            transform: translateX(5px);
        }
        .sidebar .nav-link:hover i {
            opacity: 1;
            transform: scale(1.1);
        }
        .sidebar-brand {
            border-bottom: 1px solid rgba(255,255,255,0.1) !important;
        }
        .sidebar-logout .nav-link {
            color: #ff6b6b !important;
        }
        .sidebar-logout .nav-link:hover {
            background-color: rgba(255, 107, 107, 0.1);
            color: #ff6b6b !important;
        }
        .sidebar-logout .nav-link i {
            color: #ff6b6b;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <!-- Sidebar -->
            <div class="col-auto col-md-3 col-lg-2 px-sm-2 px-0 sidebar min-vh-100">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-4 min-vh-100">
                    <a href="<?php echo BASE_PATH; ?>/index.php" class="sidebar-brand d-flex align-items-center pb-3 mb-md-3 me-md-auto text-white text-decoration-none w-100 justify-content-center">
                        <img src="<?php echo BASE_PATH; ?>/../img/logo.png" alt="Logomarca" height="80" class="rounded-circle d-none d-sm-inline shadow-sm border border-2 border-white">
                        <span class="fs-5 d-sm-none ms-2"><i class="fas fa-plane text-warning"></i></span>
                    </a>
                    
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-center align-items-sm-start w-100 mt-2" id="menu">
                        <li class="nav-item w-100 mb-2">
                            <a href="<?php echo BASE_PATH; ?>/index.php" class="nav-link align-middle w-100">
                                <i class="fas fa-tachometer-alt fa-fw"></i> <span class="ms-2 d-none d-sm-inline">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item w-100 mb-2">
                            <a href="<?php echo BASE_PATH; ?>/usuario/UsuarioList.php" class="nav-link align-middle w-100">
                                <i class="fas fa-user-tie fa-fw"></i> <span class="ms-2 d-none d-sm-inline">Funcionários</span>
                            </a>
                        </li>
                        <li class="nav-item w-100 mb-2">
                            <a href="<?php echo BASE_PATH; ?>/destino/DestinoList.php" class="nav-link align-middle w-100">
                                <i class="fas fa-map-marked-alt fa-fw"></i> <span class="ms-2 d-none d-sm-inline">Destinos</span>
                            </a>
                        </li>
                        <li class="nav-item w-100 mb-2">
                            <a href="<?php echo BASE_PATH; ?>/cliente/ClienteList.php" class="nav-link align-middle w-100">
                                <i class="fas fa-users fa-fw"></i> <span class="ms-2 d-none d-sm-inline">Clientes</span>
                            </a>
                        </li>
                        <li class="nav-item w-100 mb-2">
                            <a href="<?php echo BASE_PATH; ?>/reserva/ReservaList.php" class="nav-link align-middle w-100">
                                <i class="fas fa-calendar-check fa-fw"></i> <span class="ms-2 d-none d-sm-inline">Reservas</span>
                            </a>
                        </li>
                    </ul>
                    
                    <div class="mt-auto w-100 mb-4 sidebar-logout">
                        <ul class="nav nav-pills flex-column w-100">
                            <li class="nav-item w-100 sidebar-brand pt-3">
                                <a href="<?php echo BASE_PATH; ?>/logout.php" class="nav-link align-middle w-100 fw-bold">
                                    <i class="fas fa-sign-out-alt fa-fw"></i> <span class="ms-2 d-none d-sm-inline">Sair</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="col p-0 d-flex flex-column min-vh-100">
                <main class="container-fluid p-4 p-md-5 flex-grow-1">
