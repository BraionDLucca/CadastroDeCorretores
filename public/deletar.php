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
    <title>Deletar Cadastro</title>
</head>
<body>
<h1 id="titulo">Deletar Corretor</h1>

<div id=msg_container>
    <p>Tem certeza que deseja deletar este corretor?</p>
    <p id="msg_destacada">Esta ação não pode ser desfeita.</p>
</div>
    
    <form action="deletar.php?id=<?php echo $id;?>" method="post">
        
    <!--Permite que o id seja passado pelo POST para o php.-->
    <input type="hidden" name="id" value="<?php echo $corretor['id']; ?>">
        <table>
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>CPF</th>
                <th>CRECI</th>
            </tr>
            <tr>
                <td class='itens_tabela'><?php echo $corretor['id'];?></td>

                <td class='itens_tabela'><?php echo $corretor["name"];?></td>

                <td class='itens_tabela'><?php echo $corretor["cpf"];?></td>

                <td class='itens_tabela'><?php echo $corretor["creci"];?></td>
            </tr>
        </table>

        <input type="submit" name="cancelar" class="canc_del" id="cancelar" value="Cancelar">
        <input type="submit" name="deletar" class="canc_del" id="deletar"value="Deletar">
    </form>

<?php
if (isset($_POST["cancelar"])) {
    mysqli_close($conn);
    header("Location: index.php");
    exit;

} else if (isset($_POST["deletar"])) {
    $id = (int) $_POST["id"];

    $sql = "DELETE FROM corretores WHERE id = $id";

    if(!mysqli_query($conn, $sql)) {
        die("Erro ao deletar dados.");
    }
    
    mysqli_close($conn);
    header("Location: index.php");
    exit;
}
?>

</body>
</html>