<?php
define('BASE_PATH', '..');
require_once '../header.php';
require_once '../db.class.php';

$database = new Database();
$db = $database->getConnection();

$busca = $_GET['busca'] ?? '';

// Exclusão
if (isset($_GET['excluir_id'])) {
    $id_excluir = $_GET['excluir_id'];
    $stmt = $db->prepare("DELETE FROM reservas WHERE id = :id");
    $stmt->bindParam(':id', $id_excluir);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Reserva excluída com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao excluir reserva.</div>";
    }
}

// Listagem com busca (com JOIN para exibir nomes em vez de IDs)
$query = "
    SELECT r.id, r.data_viagem, r.status_pagamento, 
           c.nome_completo AS cliente_nome, 
           d.nome_cidade AS destino_cidade, d.pais AS destino_pais
    FROM reservas r
    JOIN clientes c ON r.id_cliente = c.id
    JOIN destinos d ON r.id_destino = d.id
";

if (!empty($busca)) {
    $query .= " WHERE c.nome_completo LIKE :busca OR d.nome_cidade LIKE :busca OR r.status_pagamento LIKE :busca";
}
$query .= " ORDER BY r.data_viagem DESC";

$stmt = $db->prepare($query);
if (!empty($busca)) {
    $termo = "%{$busca}%";
    $stmt->bindParam(':busca', $termo);
}
$stmt->execute();
$reservas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Reservas</h2>
    <a href="ReservaForm.php" class="btn btn-success"><i class="fas fa-plus"></i> Nova Reserva</a>
</div>

<form method="GET" action="ReservaList.php" class="mb-4">
    <div class="input-group">
        <input type="text" name="busca" class="form-control" placeholder="Buscar por cliente, destino ou status..." value="<?php echo htmlspecialchars($busca); ?>">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
        <a href="ReservaList.php" class="btn btn-secondary">Limpar</a>
    </div>
</form>

<div class="table-responsive bg-white rounded shadow-sm">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Destino</th>
                <th>Data da Viagem</th>
                <th>Status</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($reservas) > 0): ?>
                <?php foreach ($reservas as $r): ?>
                    <tr>
                        <td><?php echo $r['id']; ?></td>
                        <td><?php echo htmlspecialchars($r['cliente_nome']); ?></td>
                        <td><?php echo htmlspecialchars($r['destino_cidade'] . ' - ' . $r['destino_pais']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($r['data_viagem'])); ?></td>
                        <td>
                            <?php 
                                $status = $r['status_pagamento'];
                                $badge = 'bg-secondary';
                                if ($status == 'Pago') $badge = 'bg-success';
                                else if ($status == 'Pendente') $badge = 'bg-warning text-dark';
                                else if ($status == 'Cancelado') $badge = 'bg-danger';
                            ?>
                            <span class="badge <?php echo $badge; ?>"><?php echo htmlspecialchars($status); ?></span>
                        </td>
                        <td class="text-center">
                            <a href="ReservaForm.php?id=<?php echo $r['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Editar</a>
                            <a href="ReservaList.php?excluir_id=<?php echo $r['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir esta reserva?');"><i class="fas fa-trash"></i> Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center py-4">Nenhuma reserva encontrada.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../footer.php'; ?>
