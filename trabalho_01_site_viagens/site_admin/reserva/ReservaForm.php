<?php
require_once '../header.php';
require_once '../db.class.php';

$db = (new DB())->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : null;
$reserva = [
    'id_cliente' => '',
    'id_destino' => '',
    'data_viagem' => '',
    'status_pagamento' => ''
];

if ($id) {
    $stmt = $db->prepare("SELECT * FROM reservas WHERE id = ?");
    $stmt->execute([$id]);
    $reserva = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cliente = trim($_POST['id_cliente']);
    $id_destino = trim($_POST['id_destino']);
    $data_viagem = trim($_POST['data_viagem']);
    $status_pagamento = trim($_POST['status_pagamento']);

    if (empty($id_cliente) || empty($id_destino) || empty($data_viagem) || empty($status_pagamento)) {
        $erro = "Todos os campos são obrigatórios.";
    } else {
        if ($id) {
            $stmt = $db->prepare("UPDATE reservas SET id_cliente = ?, id_destino = ?, data_viagem = ?, status_pagamento = ? WHERE id = ?");
            $stmt->execute([$id_cliente, $id_destino, $data_viagem, $status_pagamento, $id]);
        } else {
            $stmt = $db->prepare("INSERT INTO reservas (id_cliente, id_destino, data_viagem, status_pagamento) VALUES (?, ?, ?, ?)");
            $stmt->execute([$id_cliente, $id_destino, $data_viagem, $status_pagamento]);
        }
        echo "<script>window.location.href = 'ReservaList.php';</script>";
        exit();
    }
}

$clientes = $db->query("SELECT id, nome_completo FROM clientes ORDER BY nome_completo")->fetchAll(PDO::FETCH_ASSOC);
$destinos = $db->query("SELECT id, nome_cidade, pais FROM destinos ORDER BY nome_cidade")->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <h2><i class="fa-solid fa-plane"></i> <?= $id ? 'Editar' : 'Nova' ?> Reserva</h2>
    <a href="ReservaList.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
</div>

<?php if (isset($erro)): ?>
    <div class="alert alert-danger"><?= $erro ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="id_cliente" class="form-label">Cliente *</label>
                    <select class="form-select" id="id_cliente" name="id_cliente" required>
                        <option value="">Selecione um cliente...</option>
                        <?php foreach ($clientes as $c): ?>
                            <option value="<?= $c['id'] ?>" <?= $reserva['id_cliente'] == $c['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($c['nome_completo']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="id_destino" class="form-label">Destino *</label>
                    <select class="form-select" id="id_destino" name="id_destino" required>
                        <option value="">Selecione um destino...</option>
                        <?php foreach ($destinos as $d): ?>
                            <option value="<?= $d['id'] ?>" <?= $reserva['id_destino'] == $d['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($d['nome_cidade'] . ' - ' . $d['pais']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="data_viagem" class="form-label">Data da Viagem *</label>
                    <input type="date" class="form-control" id="data_viagem" name="data_viagem" value="<?= htmlspecialchars($reserva['data_viagem']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="status_pagamento" class="form-label">Status do Pagamento *</label>
                    <select class="form-select" id="status_pagamento" name="status_pagamento" required>
                        <option value="">Selecione...</option>
                        <option value="Pendente" <?= $reserva['status_pagamento'] == 'Pendente' ? 'selected' : '' ?>>Pendente</option>
                        <option value="Pago" <?= $reserva['status_pagamento'] == 'Pago' ? 'selected' : '' ?>>Pago</option>
                        <option value="Cancelado" <?= $reserva['status_pagamento'] == 'Cancelado' ? 'selected' : '' ?>>Cancelado</option>
                    </select>
                </div>
            </div>

            <div class="text-end">
                <button type="submit" class="btn btn-success"><i class="fa-solid fa-save"></i> Salvar</button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../footer.php'; ?>
