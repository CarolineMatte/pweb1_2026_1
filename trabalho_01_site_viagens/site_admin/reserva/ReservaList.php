<?php
require_once '../header.php';
require_once '../db.class.php';

$db = (new DB())->getConnection();

if (isset($_GET['excluir'])) {
    $id = $_GET['excluir'];
    $stmt = $db->prepare("DELETE FROM reservas WHERE id = ?");
    $stmt->execute([$id]);
    echo "<div class='alert alert-success'>Reserva excluída com sucesso!</div>";
}

$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';

$query = "SELECT r.id, r.data_viagem, r.status_pagamento, 
          c.nome_completo AS cliente_nome, 
          d.nome_cidade AS destino_cidade, d.pais AS destino_pais, d.preco_base 
          FROM reservas r 
          INNER JOIN clientes c ON r.id_cliente = c.id 
          INNER JOIN destinos d ON r.id_destino = d.id";

if (!empty($busca)) {
    $query .= " WHERE c.nome_completo LIKE ? OR d.nome_cidade LIKE ? OR r.status_pagamento LIKE ?";
    $stmt = $db->prepare($query);
    $stmt->execute(["%$busca%", "%$busca%", "%$busca%"]);
} else {
    $stmt = $db->query($query);
}
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <h2><i class="fa-solid fa-plane-departure"></i> Gerenciar Reservas</h2>
    <a href="ReservaForm.php" class="btn btn-primary"><i class="fa-solid fa-plus"></i> Nova Reserva</a>
</div>

<form method="GET" class="mb-4">
    <div class="input-group">
        <input type="text" name="busca" class="form-control" placeholder="Buscar por cliente, destino ou status..." value="<?= htmlspecialchars($busca) ?>">
        <button class="btn btn-outline-secondary" type="submit"><i class="fa-solid fa-magnifying-glass"></i> Buscar</button>
        <?php if (!empty($busca)): ?>
            <a href="ReservaList.php" class="btn btn-outline-danger">Limpar</a>
        <?php endif; ?>
    </div>
</form>

<div class="table-responsive shadow-sm">
    <table class="table table-hover table-bordered bg-white mb-0">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Destino</th>
                <th>Data da Viagem</th>
                <th>Status Pagamento</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($reservas) > 0): ?>
                <?php foreach ($reservas as $r): ?>
                <tr>
                    <td><?= $r['id'] ?></td>
                    <td><?= htmlspecialchars($r['cliente_nome']) ?></td>
                    <td><?= htmlspecialchars($r['destino_cidade'] . ' - ' . $r['destino_pais']) ?></td>
                    <td><?= date('d/m/Y', strtotime($r['data_viagem'])) ?></td>
                    <td>
                        <?php
                        $badge = 'bg-secondary';
                        if ($r['status_pagamento'] == 'Pago') $badge = 'bg-success';
                        else if ($r['status_pagamento'] == 'Pendente') $badge = 'bg-warning text-dark';
                        else if ($r['status_pagamento'] == 'Cancelado') $badge = 'bg-danger';
                        ?>
                        <span class="badge <?= $badge ?>"><?= htmlspecialchars($r['status_pagamento']) ?></span>
                    </td>
                    <td class="text-center">
                        <a href="ReservaForm.php?id=<?= $r['id'] ?>" class="btn btn-sm btn-warning" title="Editar"><i class="fa-solid fa-pen"></i></a>
                        <a href="ReservaList.php?excluir=<?= $r['id'] ?>" class="btn btn-sm btn-danger" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir esta reserva?');"><i class="fa-solid fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center">Nenhuma reserva encontrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../footer.php'; ?>
