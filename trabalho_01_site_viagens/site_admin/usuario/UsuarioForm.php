<?php
define('BASE_PATH', '..');
require_once '../header.php';
require_once '../db.class.php';

$database = new Database();
$db = $database->getConnection();

$id = $_GET['id'] ?? null;
$nome = '';
$telefone = '';
$email = '';
$login = '';
$senha = '';
$cargo = '';
$data_contratacao = '';
$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nome = trim($_POST['nome']);
    $telefone = trim($_POST['telefone']);
    $email = trim($_POST['email']);
    $login = trim($_POST['login']);
    $senha = trim($_POST['senha']);
    $cargo = trim($_POST['cargo']);
    $data_contratacao = trim($_POST['data_contratacao']);

    if (!empty($nome) && !empty($telefone) && !empty($email) && !empty($login) && !empty($cargo) && !empty($data_contratacao) && (!empty($senha) || $id)) {
        if ($id) {
            // Update
            if (!empty($senha)) {
                $query = "UPDATE usuarios SET nome = :nome, telefone = :telefone, email = :email, login = :login, senha = :senha, cargo = :cargo, data_contratacao = :data_contratacao WHERE id = :id";
            } else {
                $query = "UPDATE usuarios SET nome = :nome, telefone = :telefone, email = :email, login = :login, cargo = :cargo, data_contratacao = :data_contratacao WHERE id = :id";
            }
        } else {
            // Insert
            $query = "INSERT INTO usuarios (nome, telefone, email, login, senha, cargo, data_contratacao) VALUES (:nome, :telefone, :email, :login, :senha, :cargo, :data_contratacao)";
        }

        $stmt = $db->prepare($query);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':login', $login);
        $stmt->bindParam(':cargo', $cargo);
        $stmt->bindParam(':data_contratacao', $data_contratacao);
        if (!empty($senha) || !$id) {
            $stmt->bindParam(':senha', $senha);
        }
        if ($id) {
            $stmt->bindParam(':id', $id);
        }

        try {
            if ($stmt->execute()) {
                header("Location: UsuarioList.php");
                exit;
            } else {
                $mensagem = "<div class='alert alert-danger'>Erro ao salvar usuário.</div>";
            }
        } catch(PDOException $e) {
             $mensagem = "<div class='alert alert-danger'>Erro no banco de dados: " . $e->getMessage() . "</div>";
        }

    } else {
        $mensagem = "<div class='alert alert-warning'>Preencha todos os campos obrigatórios.</div>";
    }
} else if ($id) {
    // Carregar dados
    $stmt = $db->prepare("SELECT * FROM usuarios WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($usuario) {
        $nome = $usuario['nome'];
        $telefone = $usuario['telefone'];
        $email = $usuario['email'];
        $login = $usuario['login'];
        $cargo = $usuario['cargo'];
        $data_contratacao = $usuario['data_contratacao'];
    } else {
        $mensagem = "<div class='alert alert-danger'>Usuário não encontrado.</div>";
        $id = null;
    }
}
?>

<div class="mb-3">
    <h2><?php echo $id ? 'Editar Usuário' : 'Novo Usuário'; ?></h2>
</div>

<?php echo $mensagem; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" action="UsuarioForm.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars((string)$id); ?>">
            
            <div class="mb-3">
                <label for="nome" class="form-label">Nome Completo *</label>
                <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($nome); ?>" required>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="telefone" class="form-label">Telefone *</label>
                    <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo htmlspecialchars($telefone); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">E-mail *</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="login" class="form-label">Login *</label>
                    <input type="text" class="form-control" id="login" name="login" value="<?php echo htmlspecialchars($login); ?>" required>
                </div>
                <div class="col-md-6">
                    <label for="senha" class="form-label">Senha <?php echo $id ? '(Deixe em branco para manter)' : '*'; ?></label>
                    <input type="password" class="form-control" id="senha" name="senha" <?php echo $id ? '' : 'required'; ?>>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="cargo" class="form-label">Cargo *</label>
                    <select class="form-select" id="cargo" name="cargo" required>
                        <option value="">Selecione...</option>
                        <option value="Gerente" <?php echo $cargo === 'Gerente' ? 'selected' : ''; ?>>Gerente</option>
                        <option value="Vendedor" <?php echo $cargo === 'Vendedor' ? 'selected' : ''; ?>>Vendedor</option>
                        <option value="Suporte" <?php echo $cargo === 'Suporte' ? 'selected' : ''; ?>>Suporte</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="data_contratacao" class="form-label">Data de Contratação *</label>
                    <input type="date" class="form-control" id="data_contratacao" name="data_contratacao" value="<?php echo htmlspecialchars($data_contratacao); ?>" required>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
            <a href="UsuarioList.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require_once '../footer.php'; ?>
