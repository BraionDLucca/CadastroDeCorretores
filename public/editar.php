<?php
include_once("database.php");
include_once("../includes/utils.php");

// Verifica se um id foi passado por GET.
if (isset($_GET["id"])) {

    $id = (int) $_GET["id"];

    // Consulta os dados do corretor com o id informado para editar.
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

        <!-- Permite que o id seja passado pelo POST para o php. -->
        <input type="hidden" name="id" value="<?php echo $corretor['id']; ?>">


        <!-- Preenche os campos com as informações do corretor a serem editadas -->
        <input type="text" class="input" name="cpf" placeholder="Informe o CPF" maxlength="11"
        value= "<?php echo $corretor["cpf"];?>">

        <input type="text" class="input" name="creci" placeholder="Informe o CRECI"
        value= "<?php echo $corretor["creci"];?>"> <br>
        
        <input type="text" class="input" name="nome" id="nome" placeholder="Informe o nome"
        value= "<?php echo $corretor["name"];?>"> <br>

        <input type="submit" name="cancelar" class="canc_alt" id="cancelar" value="Cancelar">
        <input type="submit" name="alterar" class="canc_alt" id="alterar" value="Alterar">
    </form>

    <p id=msg_destacada></p>

<?php

$erros = array_fill(0, 7, '');

// Ao clicar em "Cancelar".
if (isset($_POST["cancelar"])) {
    mysqli_close($conn);
    header("Location: index.php");
    exit;
    
} else if (isset($_POST["alterar"])) { // Ao clicar em "Alterar".

    // Importa os dados informados no html para php através de POST. 
    $id = (int) $_POST["id"];
    $cpf = $_POST["cpf"];
    $nome = $_POST["nome"];
    $creci = $_POST["creci"];

    $erros = validar_info($cpf, $creci, $nome, $erros, $conn);

    ?>

    <!-- Importa o elemento "msg_destacada" do html para que sejam adicionadas mensagens de erro. --> 
    <script>const msg_destacada = document.getElementById("msg_destacada")</script>

    <?php

    imprimir_erros($cpf, $creci, $nome, $erros);

    // Verifica se não há erros.
    if(!in_array(true, $erros, true)) {

        // Altera os dados do banco de dados pelos dados informados.
        $sql = "UPDATE corretores SET name = '$nome', cpf = '$cpf', creci = '$creci' WHERE id = $id";
    
        if(!mysqli_query($conn, $sql)) {
            die("Erro ao editar dados.");
        }
        
        // Fecha a conexão com o banco e redireciona para index.php
        mysqli_close($conn);
        header("Location: index.php");
        exit();
    }
}
?>

</body>
</html>