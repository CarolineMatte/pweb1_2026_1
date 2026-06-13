<?php
require_once '../header.php';
require_once '../db.class.php';

$db = (new DB())->getConnection();

$id = isset($_GET['id']) ? $_GET['id'] : null;
$destino = [
    'nome_cidade' => '',
    'pais' => '',
    'preco_base' => '',
    'tipo_voo' => ''
];

if ($id) {
    $stmt = $db->prepare("SELECT * FROM destinos WHERE id = ?");
    $stmt->execute([$id]);
    $destino = $stmt->fetch(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_cidade = trim($_POST['nome_cidade']);
    $pais = trim($_POST['pais']);
    $preco_base = trim($_POST['preco_base']);
    $tipo_voo = trim($_POST['tipo_voo']);

    if (empty($nome_cidade) || empty($pais) || empty($preco_base) || empty($tipo_voo)) {
        $erro = "Todos os campos são obrigatórios.";
    } else {
        if ($id) {
            $stmt = $db->prepare("UPDATE destinos SET nome_cidade = ?, pais = ?, preco_base = ?, tipo_voo = ? WHERE id = ?");
            $stmt->execute([$nome_cidade, $pais, $preco_base, $tipo_voo, $id]);
        } else {
            $stmt = $db->prepare("INSERT INTO destinos (nome_cidade, pais, preco_base, tipo_voo) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nome_cidade, $pais, $preco_base, $tipo_voo]);
        }
        echo "<script>window.location.href = 'DestinoList.php';</script>";
        exit();
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
    <h2><i class="fa-solid fa-map"></i> <?= $id ? 'Editar' : 'Novo' ?> Destino</h2>
    <a href="DestinoList.php" class="btn btn-secondary"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
</div>

<?php if (isset($erro)): ?>
    <div class="alert alert-danger"><?= $erro ?></div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nome_cidade" class="form-label">Nome da Cidade *</label>
                    <input type="text" class="form-control" id="nome_cidade" name="nome_cidade" value="<?= htmlspecialchars($destino['nome_cidade']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="pais" class="form-label">País *</label>
                    <input type="text" class="form-control" id="pais" name="pais" value="<?= htmlspecialchars($destino['pais']) ?>" required>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="preco_base" class="form-label">Preço Base (R$) *</label>
                    <input type="number" step="0.01" class="form-control" id="preco_base" name="preco_base" value="<?= htmlspecialchars($destino['preco_base']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="tipo_voo" class="form-label">Tipo de Voo *</label>
                    <select class="form-select" id="tipo_voo" name="tipo_voo" required>
                        <option value="">Selecione...</option>
                        <option value="Econômica" <?= $destino['tipo_voo'] == 'Econômica' ? 'selected' : '' ?>>Econômica</option>
                        <option value="Executiva" <?= $destino['tipo_voo'] == 'Executiva' ? 'selected' : '' ?>>Executiva</option>
                        <option value="Primeira Classe" <?= $destino['tipo_voo'] == 'Primeira Classe' ? 'selected' : '' ?>>Primeira Classe</option>
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
