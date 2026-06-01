<?php
include '../header.php';
include_once "../database/db.class.php";

$db = new db('usuario');

if (!empty($_GET['id'])) {
    $db->destroy($_GET['id']);
}

if (!empty($_POST)) {
    // var_dump($_POST);
    //exit;
    $dados = $db->search($_POST);
} else {
    $dados = $db->all();
}

?>
<div class="row">

    <h3>Listagem de Usuário</h3>
    <form action="UsuarioList.php" method="post">
        <div class="row">
            <div class="col-2">
                <label for="nome">Tipo</label>
                <select name="tipo" class="form-select">
                    <option value="nome">Nome</option>
                    <option value="telefone">Telefone</option>
                    <option value="email">Email</option>
                </select>
            </div>
            <div class="col-5">
                <label for="valor">Valor</label>
                <input type="text" name="valor" placeholder="Pesquisar..." class="form-control">
            </div>
            <div class="col-5">
                <button type="submit" class="btn btn-primary">Buscar</button>
                <a href="./UsuarioForm.php" class="btn btn-success"> Novo</a>
            </div>
        </div>
    </form>

</div>


<div class="row mt-4">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nome</th>
                <th scope="col">Telefone</th>
                <th scope="col">Email</th>
                <th scope="col">Ação</th>
                <th scope="col">Ação</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($dados as $item) {
                echo "<tr>
                <th scope='row'>$item->id</th>
                <td>$item->nome</td>
                <td>$item->telefone</td>
                <td>$item->email</td>
                <td>
                    <a class='btn btn-warning' title='Editar'
                        href='./UsuarioForm.php?id=$item->id'>Editar</a>
                </td>
                <td>
                    <a class='btn btn-danger' title='Exclur'
                        onclick='return confirm(\"Deseja Excluir?\")'
                        href='./UsuarioList.php?id=$item->id'>Deletar</a>
                </td>
            </tr>";
            }
            ?>
        </tbody>
    </table>
</div>



<?php
include '../footer.php';
?>