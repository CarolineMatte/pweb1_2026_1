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
    $stmt = $db->prepare("DELETE FROM clientes WHERE id = :id");
    $stmt->bindParam(':id', $id_excluir);
    if ($stmt->execute()) {
        echo "<div class='alert alert-success'>Cliente excluído com sucesso!</div>";
    } else {
        echo "<div class='alert alert-danger'>Erro ao excluir cliente.</div>";
    }
}

// Listagem com busca
$query = "SELECT * FROM clientes";
if (!empty($busca)) {
    $query .= " WHERE nome_completo LIKE :busca OR cpf LIKE :busca OR email_contato LIKE :busca";
}
$stmt = $db->prepare($query);
if (!empty($busca)) {
    $termo = "%{$busca}%";
    $stmt->bindParam(':busca', $termo);
}
$stmt->execute();
$clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Clientes</h2>
    <a href="ClienteForm.php" class="btn btn-success"><i class="fas fa-plus"></i> Novo Cliente</a>
</div>

<form method="GET" action="ClienteList.php" class="mb-4">
    <div class="input-group">
        <input type="text" name="busca" class="form-control" placeholder="Buscar por nome, CPF ou e-mail..." value="<?php echo htmlspecialchars($busca); ?>">
        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Buscar</button>
        <a href="ClienteList.php" class="btn btn-secondary">Limpar</a>
    </div>
</form>

<div class="table-responsive bg-white rounded shadow-sm">
    <table class="table table-hover align-middle mb-0">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Nome Completo</th>
                <th>CPF</th>
                <th>Telefone</th>
                <th>E-mail</th>
                <th class="text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($clientes) > 0): ?>
                <?php foreach ($clientes as $c): ?>
                    <tr>
                        <td><?php echo $c['id']; ?></td>
                        <td><?php echo htmlspecialchars($c['nome_completo']); ?></td>
                        <td><?php echo htmlspecialchars($c['cpf']); ?></td>
                        <td><?php echo htmlspecialchars($c['telefone_contato']); ?></td>
                        <td><?php echo htmlspecialchars($c['email_contato']); ?></td>
                        <td class="text-center">
                            <a href="ClienteForm.php?id=<?php echo $c['id']; ?>" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i> Editar</a>
                            <a href="ClienteList.php?excluir_id=<?php echo $c['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tem certeza que deseja excluir este cliente?');"><i class="fas fa-trash"></i> Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="text-center py-4">Nenhum cliente encontrado.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once '../footer.php'; ?>
