 <?php
    include '../header.php';
    include_once "../database/db.class.php";

    $db = new db('usuario'); 

    if(!empty($_POST)){
        $db->store($_POST);
        

    }
    ?>


   
        <form action="UsuarioForm.php" method="post">
            <h3>Formulário Usuario</h3>
            <div class="col-6">
                <label for="nome">Nome</label>
                <input type="text" name="nome" class="form-control">
            </div>
            <div class="col-6">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="col-6">
                <label for="nome">Telefone</label>
                <input type="text" name="telefone" class="form-control">
            </div>

            <div class="mt-2">
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
            


           
        </form>
   

    <?php
    include '../footer.php';
    ?>