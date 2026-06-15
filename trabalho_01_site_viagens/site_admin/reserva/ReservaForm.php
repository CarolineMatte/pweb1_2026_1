<?php
define('BASE_PATH', '..');
require_once '../header.php';
require_once '../db.class.php';

$database = new Database();
$db = $database->getConnection();

// Carregar clientes e destinos para os selects
$clientes = $db->query("SELECT id, nome_completo, cpf FROM clientes ORDER BY nome_completo")->fetchAll(PDO::FETCH_ASSOC);
$destinos = $db->query("SELECT id, nome_cidade, pais, preco_base FROM destinos ORDER BY nome_cidade")->fetchAll(PDO::FETCH_ASSOC);

$id = $_GET['id'] ?? null;
$id_cliente = '';
$id_destino = '';
$data_viagem = '';
$status_pagamento = '';
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $id_cliente = trim($_POST['id_cliente']);
    $id_destino = trim($_POST['id_destino']);
    $data_viagem = trim($_POST['data_viagem']);
    $status_pagamento = trim($_POST['status_pagamento']);

    if (!empty($id_cliente) && !empty($id_destino) && !empty($data_viagem) && !empty($status_pagamento)) {
        if ($id) {
            $query = "UPDATE reservas SET id_cliente = :id_cliente, id_destino = :id_destino, data_viagem = :data_viagem, status_pagamento = :status_pagamento WHERE id = :id";
        } else {
            $query = "INSERT INTO reservas (id_cliente, id_destino, data_viagem, status_pagamento) VALUES (:id_cliente, :id_destino, :data_viagem, :status_pagamento)";
        }

        $stmt = $db->prepare($query);
        $stmt->bindParam(':id_cliente', $id_cliente);
        $stmt->bindParam(':id_destino', $id_destino);
        $stmt->bindParam(':data_viagem', $data_viagem);
        $stmt->bindParam(':status_pagamento', $status_pagamento);
        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        try {
            if ($stmt->execute()) {
                $mensagem = "<div class='alert alert-success'>Reserva salva com sucesso! <a href='ReservaList.php'>Voltar à listagem</a></div>";
            } else {
                $mensagem = "<div class='alert alert-danger'>Erro ao salvar reserva.</div>";
            }
        } catch(PDOException $e) {
             $mensagem = "<div class='alert alert-danger'>Erro no banco de dados: " . $e->getMessage() . "</div>";
        }
    } else {
        $mensagem = "<div class='alert alert-warning'>Preencha todos os campos obrigatórios.</div>";
    }
} else if ($id) {
    $stmt = $db->prepare("SELECT * FROM reservas WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $reserva = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($reserva) {
        $id_cliente = $reserva['id_cliente'];
        $id_destino = $reserva['id_destino'];
        $data_viagem = $reserva['data_viagem'];
        $status_pagamento = $reserva['status_pagamento'];
    } else {
        $mensagem = "<div class='alert alert-danger'>Reserva não encontrada.</div>";
        $id = null;
    }
}
?>

<div class="mb-3">
    <h2><?php echo $id ? 'Editar Reserva' : 'Nova Reserva'; ?></h2>
</div>

<?php echo $mensagem; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="ReservaForm.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$id); ?>">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="id_cliente" class="form-label">Cliente *</label>
                    <select class="form-select" id="id_cliente" name="id_cliente" required>
                        <option value="">Selecione o Cliente...</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?php echo $c['id']; ?>" <?php echo $id_cliente == $c['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($c['nome_completo'] . ' (CPF: ' . $c['cpf'] . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="id_destino" class="form-label">Destino *</label>
                    <select class="form-select" id="id_destino" name="id_destino" required>
                        <option value="">Selecione o Destino...</option>
                        <?php foreach ($destinos as $d): ?>
                            <option value="<?php echo $d['id']; ?>" <?php echo $id_destino == $d['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($d['nome_cidade'] . ' - ' . $d['pais'] . ' (R$ ' . number_format($d['preco_base'], 2, ',', '.') . ')'); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="data_viagem" class="form-label">Data da Viagem *</label>
                    <input type="date" class="form-control" id="data_viagem" name="data_viagem" value="<?php echo htmlspecialchars($data_viagem); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="status_pagamento" class="form-label">Status do Pagamento *</label>
                    <select class="form-select" id="status_pagamento" name="status_pagamento" required>
                        <option value="">Selecione...</option>
                        <option value="Pendente" <?php echo $status_pagamento === 'Pendente' ? 'selected' : ''; ?>>Pendente</option>
                        <option value="Pago" <?php echo $status_pagamento === 'Pago' ? 'selected' : ''; ?>>Pago</option>
                        <option value="Cancelado" <?php echo $status_pagamento === 'Cancelado' ? 'selected' : ''; ?>>Cancelado</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
            <a href="ReservaList.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require_once '../footer.php'; ?>
