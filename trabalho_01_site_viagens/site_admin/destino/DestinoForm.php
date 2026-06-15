<?php
define('BASE_PATH', '..');
require_once '../header.php';
require_once '../db.class.php';

$database = new Database();
$db = $database->getConnection();

$id = $_GET['id'] ?? null;
$nome_cidade = '';
$pais = '';
$preco_base = '';
$tipo_voo = '';
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nome_cidade = trim($_POST['nome_cidade']);
    $pais = trim($_POST['pais']);
    $preco_base = trim($_POST['preco_base']);
    $tipo_voo = trim($_POST['tipo_voo']);

    if (!empty($nome_cidade) && !empty($pais) && $preco_base !== '' && !empty($tipo_voo)) {
        if ($id) {
            $query = "UPDATE destinos SET nome_cidade = :nome_cidade, pais = :pais, preco_base = :preco_base, tipo_voo = :tipo_voo WHERE id = :id";
        } else {
            $query = "INSERT INTO destinos (nome_cidade, pais, preco_base, tipo_voo) VALUES (:nome_cidade, :pais, :preco_base, :tipo_voo)";
        }

        $stmt = $db->prepare($query);
        $stmt->bindParam(':nome_cidade', $nome_cidade);
        $stmt->bindParam(':pais', $pais);
        $stmt->bindParam(':preco_base', $preco_base);
        $stmt->bindParam(':tipo_voo', $tipo_voo);
        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        try {
            if ($stmt->execute()) {
                $mensagem = "<div class='alert alert-success'>Destino salvo com sucesso! <a href='DestinoList.php'>Voltar à listagem</a></div>";
            } else {
                $mensagem = "<div class='alert alert-danger'>Erro ao salvar destino.</div>";
            }
        } catch(PDOException $e) {
             $mensagem = "<div class='alert alert-danger'>Erro no banco de dados: " . $e->getMessage() . "</div>";
        }
    } else {
        $mensagem = "<div class='alert alert-warning'>Preencha todos os campos obrigatórios.</div>";
    }
} else if ($id) {
    $stmt = $db->prepare("SELECT * FROM destinos WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $destino = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($destino) {
        $nome_cidade = $destino['nome_cidade'];
        $pais = $destino['pais'];
        $preco_base = $destino['preco_base'];
        $tipo_voo = $destino['tipo_voo'];
    } else {
        $mensagem = "<div class='alert alert-danger'>Destino não encontrado.</div>";
        $id = null;
    }
}
?>

<div class="mb-3">
    <h2><?php echo $id ? 'Editar Destino' : 'Novo Destino'; ?></h2>
</div>

<?php echo $mensagem; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="DestinoForm.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$id); ?>">
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="nome_cidade" class="form-label">Nome da Cidade *</label>
                    <input type="text" class="form-control" id="nome_cidade" name="nome_cidade" value="<?php echo htmlspecialchars($nome_cidade); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="pais" class="form-label">País *</label>
                    <input type="text" class="form-control" id="pais" name="pais" value="<?php echo htmlspecialchars($pais); ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="preco_base" class="form-label">Preço Base (R$) *</label>
                    <input type="number" step="0.01" min="0" class="form-control" id="preco_base" name="preco_base" value="<?php echo htmlspecialchars((string)$preco_base); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="tipo_voo" class="form-label">Tipo de Voo *</label>
                    <select class="form-select" id="tipo_voo" name="tipo_voo" required>
                        <option value="">Selecione...</option>
                        <option value="Econômica" <?php echo $tipo_voo === 'Econômica' ? 'selected' : ''; ?>>Econômica</option>
                        <option value="Executiva" <?php echo $tipo_voo === 'Executiva' ? 'selected' : ''; ?>>Executiva</option>
                        <option value="Primeira Classe" <?php echo $tipo_voo === 'Primeira Classe' ? 'selected' : ''; ?>>Primeira Classe</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
            <a href="DestinoList.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require_once '../footer.php'; ?>
