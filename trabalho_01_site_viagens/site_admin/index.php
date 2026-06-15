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

<div class="row mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center border-bottom pb-2">
        <h2 class="mb-0">Dashboard</h2>
        <a href="logout.php" class="btn btn-danger"><i class="fas fa-sign-out-alt"></i> Sair</a>
    </div>
    <div class="col-12 mt-3">
        <p>Bem-vindo ao painel administrativo da BioViagens, <strong><?php echo htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário'); ?></strong>.</p>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-primary h-100 shadow">
            <div class="card-body">
                <h5 class="card-title">Usuários</h5>
                <p class="card-text display-4"><?php echo $countUsuarios; ?></p>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link text-decoration-none" href="usuario/UsuarioList.php">Ver detalhes</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-success h-100 shadow">
            <div class="card-body">
                <h5 class="card-title">Destinos</h5>
                <p class="card-text display-4"><?php echo $countDestinos; ?></p>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link text-decoration-none" href="destino/DestinoList.php">Ver detalhes</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-warning h-100 shadow">
            <div class="card-body">
                <h5 class="card-title">Clientes</h5>
                <p class="card-text display-4"><?php echo $countClientes; ?></p>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link text-decoration-none" href="cliente/ClienteList.php">Ver detalhes</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card text-white bg-danger h-100 shadow">
            <div class="card-body">
                <h5 class="card-title">Reservas</h5>
                <p class="card-text display-4"><?php echo $countReservas; ?></p>
            </div>
            <div class="card-footer d-flex align-items-center justify-content-between">
                <a class="small text-white stretched-link text-decoration-none" href="reserva/ReservaList.php">Ver detalhes</a>
                <div class="small text-white"><i class="fas fa-angle-right"></i></div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'footer.php'; ?>
