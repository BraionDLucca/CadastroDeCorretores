<?php
include("database.php");

// Verifica se um id foi passado por GET.
if (isset($_GET["id"])) {

    $id = (int) $_GET["id"];
    $sql = "SELECT * FROM corretores WHERE id = '$id'";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $corretor = mysqli_fetch_assoc($result);
    } else {
        die("Corretor não encontrado.");
    }
} else {
    die("Nenhum id informado.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Editar Cadastro</title>
</head>
<body>
<h1 id="titulo">Edição de Corretor</h1>
    
    <form action="editar.php?id=<?php echo $id;?>" method="post">

        <!--Permite que o id seja passado pelo POST para o php.-->
        <input type="hidden" name="id" value="<?php echo $corretor['id']; ?>">

        <input type="text" class="input" name="cpf" placeholder="Informe o CPF" maxlength="11"
        value= "<?php echo $corretor["cpf"];?>">

        <input type="text" class="input" name="creci" placeholder="Informe o CRECI"
        value= "<?php echo $corretor["creci"];?>"> <br>
        
        <input type="text" class="input" name="nome" id="nome" placeholder="Informe o nome"
        value= "<?php echo $corretor["name"];?>"> <br>

        <input type="submit" name="cancelar" class="canc_alt" id="cancelar" value="Cancelar">
        <input type="submit" name="alterar" class="canc_alt" id="alterar" value="Alterar">
    </form>

<?php
if (isset($_POST["cancelar"])) {
    mysqli_close($conn);
    header("Location: index.php");
    exit;
    
} else if (isset($_POST["alterar"])) {
    $id = (int) $_POST["id"];
    $cpf = $_POST["cpf"];
    $nome = $_POST["nome"];
    $creci = $_POST["creci"];

    // Verifica se os campos estão vazios antes de alterar os dados.
    if(!empty($cpf) && !empty($nome) && !empty($creci)) {
        $sql = "UPDATE corretores SET name = '$nome', cpf = '$cpf', creci = '$creci' WHERE id = $id";
    
        if(!mysqli_query($conn, $sql)) {
            die("Erro ao editar dados.");
        }

        mysqli_close($conn);
        header("Location: index.php");
    } else {
        echo "Preencha todos os campos.";
    }
}
?>

</body>
</html>