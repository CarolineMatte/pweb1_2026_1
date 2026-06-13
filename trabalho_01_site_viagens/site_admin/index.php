<?php
require_once 'header.php';
require_once 'db.class.php';

$db = (new DB())->getConnection();

$totalUsuarios = $db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$totalDestinos = $db->query("SELECT COUNT(*) FROM destinos")->fetchColumn();
$totalClientes = $db->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
$totalReservas = $db->query("SELECT COUNT(*) FROM reservas")->fetchColumn();
?>

<div class="row mb-4">
    <div class="col-12">
        <h2 class="border-bottom pb-2">Dashboard</h2>
        <p class="text-muted">Bem-vindo ao painel de controle da BioViagens.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-primary shadow h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-users"></i> Usuários</h5>
                <p class="card-text fs-1"><?= $totalUsuarios ?></p>
                <a href="usuario/UsuarioList.php" class="text-white text-decoration-none">Gerenciar Usuários <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-success shadow h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-map-location-dot"></i> Destinos</h5>
                <p class="card-text fs-1"><?= $totalDestinos ?></p>
                <a href="destino/DestinoList.php" class="text-white text-decoration-none">Gerenciar Destinos <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-warning shadow h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-person-walking-luggage"></i> Clientes</h5>
                <p class="card-text fs-1"><?= $totalClientes ?></p>
                <a href="cliente/ClienteList.php" class="text-white text-decoration-none">Gerenciar Clientes <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card text-white bg-info shadow h-100">
            <div class="card-body">
                <h5 class="card-title"><i class="fa-solid fa-plane-departure"></i> Reservas</h5>
                <p class="card-text fs-1"><?= $totalReservas ?></p>
                <a href="reserva/ReservaList.php" class="text-white text-decoration-none">Gerenciar Reservas <i class="fa-solid fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
