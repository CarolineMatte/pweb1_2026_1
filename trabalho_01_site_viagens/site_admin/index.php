<?php
define('BASE_PATH', '.');
require_once 'header.php';
require_once 'db.class.php';

// Inicializa a conexão
$database = new Database();
$db = $database->getConnection();

// Realiza as contagens
$countUsuarios = 0;
$countDestinos = 0;
$countClientes = 0;
$countReservas = 0;

if ($db) {
    try {
        $countUsuarios = $db->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
        $countDestinos = $db->query("SELECT COUNT(*) FROM destinos")->fetchColumn();
        $countClientes = $db->query("SELECT COUNT(*) FROM clientes")->fetchColumn();
        $countReservas = $db->query("SELECT COUNT(*) FROM reservas")->fetchColumn();
    } catch(PDOException $e) {
        // Silencioso ou exibir erro
    }
}
?>

<style>
    .dashboard-card {
        border: none;
        border-radius: 1rem;
        overflow: hidden;
        transition: all 0.3s ease;
        background: #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border-left: 5px solid #0A2A5E;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        border-left-color: #f0ad4e;
    }
    .dashboard-icon {
        font-size: 3rem;
        color: rgba(10, 42, 94, 0.15);
        position: absolute;
        right: 1.5rem;
        top: 50%;
        transform: translateY(-50%);
        transition: all 0.3s ease;
    }
    .dashboard-card:hover .dashboard-icon {
        color: rgba(240, 173, 78, 0.3);
        transform: translateY(-50%) scale(1.1);
    }
    .dashboard-value {
        font-size: 2.5rem;
        font-weight: 700;
        color: #0A2A5E;
    }
    .dashboard-title {
        color: #6c757d;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
    }
    .card-link {
        background-color: rgba(10, 42, 94, 0.03);
        color: #0A2A5E;
        font-weight: 600;
        font-size: 0.9rem;
        transition: background 0.3s;
    }
    .card-link:hover {
        background-color: rgba(240, 173, 78, 0.1);
        color: #f0ad4e;
    }
</style>

<div class="row mb-5 mt-2">
    <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-end border-bottom pb-3">
        <div>
            <h2 class="mb-1 text-dark fw-bold">Dashboard</h2>
            <p class="text-muted mb-0">Bem-vindo ao painel administrativo da <strong>BioViagens</strong>, <?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?>.</p>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-md-6 col-lg-3">
        <div class="card dashboard-card h-100">
            <div class="card-body position-relative p-4">
                <i class="fas fa-user-tie dashboard-icon"></i>
                <div class="dashboard-title mb-2">Funcionários</div>
                <div class="dashboard-value"><?php echo $countUsuarios; ?></div>
            </div>
            <a href="usuario/UsuarioList.php" class="card-footer card-link d-flex align-items-center justify-content-between text-decoration-none border-0 py-3">
                <span>Gerenciar Funcionários</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card dashboard-card h-100">
            <div class="card-body position-relative p-4">
                <i class="fas fa-map-marked-alt dashboard-icon"></i>
                <div class="dashboard-title mb-2">Destinos</div>
                <div class="dashboard-value"><?php echo $countDestinos; ?></div>
            </div>
            <a href="destino/DestinoList.php" class="card-footer card-link d-flex align-items-center justify-content-between text-decoration-none border-0 py-3">
                <span>Gerenciar Destinos</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card dashboard-card h-100">
            <div class="card-body position-relative p-4">
                <i class="fas fa-users dashboard-icon"></i>
                <div class="dashboard-title mb-2">Clientes</div>
                <div class="dashboard-value"><?php echo $countClientes; ?></div>
            </div>
            <a href="cliente/ClienteList.php" class="card-footer card-link d-flex align-items-center justify-content-between text-decoration-none border-0 py-3">
                <span>Gerenciar Clientes</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
    <div class="col-md-6 col-lg-3">
        <div class="card dashboard-card h-100">
            <div class="card-body position-relative p-4">
                <i class="fas fa-calendar-check dashboard-icon"></i>
                <div class="dashboard-title mb-2">Reservas</div>
                <div class="dashboard-value"><?php echo $countReservas; ?></div>
            </div>
            <a href="reserva/ReservaList.php" class="card-footer card-link d-flex align-items-center justify-content-between text-decoration-none border-0 py-3">
                <span>Gerenciar Reservas</span>
                <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
