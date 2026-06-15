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
    $stmt = $db->prepare("DELETE FROM destinos WHERE id = :id");
    $stmt->bindParam(':id', $id_excluir);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Destino excluído com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao excluir destino.</div>";
    }
}

// Listagem com busca
$query = "SELECT * FROM destinos";
if (!empty($busca)) {
    $query .= " WHERE nome_cidade LIKE :busca OR pais LIKE :busca OR tipo_voo LIKE :busca";
}
$stmt = $db->prepare($query);
if (!empty($busca)) {
    $termo = "%{$busca}%";
    $stmt->bindParam(':busca', $termo);
}
$stmt->execute();
$destinos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Destinos</h2>
    <a href="DestinoForm.php" class="btn btn-success"><i class="fas fa-plus"></i> Novo Destino</a>
</div>

<form method="GET" action="DestinoList.php" class="mb-4">
    <div class="input-group">
        <input type="text" name="busca" class="form-control" placeholder="Buscar por cidade, país ou tipo de voo..." value="<?php echo htmlspecialchars($busca); ?>">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
        <a href="DestinoList.php" class="btn btn-secondary">Limpar</a>
    </div>
</form>

<div class="table-responsive bg-white rounded shadow-sm">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Cidade</th>
                <th>País</th>
                <th>Preço Base</th>
                <th>Tipo de Voo</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($destinos) > 0): ?>
                <?php foreach ($destinos as $d): ?>
                    <tr>
                        <td><?php echo $d['id']; ?></td>
                        <td><?php echo htmlspecialchars($d['nome_cidade']); ?></td>
                        <td><?php echo htmlspecialchars($d['pais']); ?></td>
                        <td>R$ <?php echo number_format($d['preco_base'], 2, ',', '.'); ?></td>
                        <td><?php echo htmlspecialchars($d['tipo_voo']); ?></td>
                        <td class="text-center">
                            <a href="DestinoForm.php?id=<?php echo $d['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Editar</a>
                            <a href="DestinoList.php?excluir_id=<?php echo $d['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este destino?');"><i class="fas fa-trash"></i> Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center py-4">Nenhum destino encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../footer.php'; ?>
