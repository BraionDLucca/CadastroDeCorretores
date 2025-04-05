<?php
include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Cadastro de Corretor</title>
</head>
<body>
    <h1 id="titulo">Cadastro de Corretor</h1>
    
    <form action="index.php" method="post">
        <input type="text" class="input" name="cpf" id="cpf" placeholder="Digite o seu CPF" maxlength="11">
        <input type="text" class="input" name="creci" placeholder="Digite seu CRECI"> <br>
        <input type="text" class="input" name="nome" id="nome" placeholder="Digite o seu nome"> <br>

        <input type="submit" name="enviar" id="enviar_btn" value="Enviar"> <br>
    </form>

    <p id=msg_destacada></p>

    <table>
    <tr>
        <th>Id</th>
        <th>Nome</th>
        <th>CPF</th>
        <th>CRECI</th>
        <th>Ações</th>
    </tr>

<?php

$cpf = '';
$nome = '';
$creci = '';

$cpfduplicado = false;
$cpfinvalido = false;
$nomeduplicado = false;
$nomeinvalido = false;
$creciduplicado = false;
$creciinvalido = false;
$vazio = false;

$sql = "SELECT * FROM corretores";
$result = mysqli_query($conn, $sql);
imprimir_dados($result);


if (isset($_POST["enviar"])){
    $cpf = $_POST["cpf"];
    $nome = $_POST["nome"];
    $creci = $_POST["creci"];

    // Variáveis usadas posteriormente para validação.
    $cpfduplicado = false;
    $cpfinvalido = false;
    $nomeduplicado = false;
    $nomeinvalido = false;
    $creciduplicado = false;
    $creciinvalido = false;
    $vazio = false;

    // Checa se o valor informado já existe no banco de dados.
    $sql_cpf = "SELECT id FROM corretores WHERE cpf = '$cpf'";
    $sql_name = "SELECT id FROM corretores WHERE name = '$nome'";
    $sql_creci = "SELECT id FROM corretores WHERE creci = '$creci'";

    // Checa o CPF informado.
    if(mysqli_num_rows(mysqli_query($conn, $sql_cpf)) > 0) {
        ?>
        <script>
        const msg_destacada = document.getElementById("msg_destacada")

        msg_destacada.textContent += "CPF já cadastrado."
        </script>
        <?php
        $cpfduplicado = true;
    }

    // Checa o nome informado.
    if (mysqli_num_rows(mysqli_query($conn, $sql_name)) > 0) {
        ?>
        <script>
        msg_destacada.textContent += " Nome já cadastrado."
        </script>
        <?php
        $nomeduplicado = true;
    }

    // Checa o CRECI informado.
    if (mysqli_num_rows(mysqli_query($conn, $sql_creci)) > 0) {
        ?>
        <script>
        msg_destacada.textContent += " CRECI já cadastrado."
        </script>
        <?php
        $creciduplicado = true;
    }

    if(empty($cpf) || empty($nome) || empty($creci)) {
        ?>
        <script>
        msg_destacada.textContent += " Todos os campos são obrigatórios."
        </script>
        <?php
        $vazio = true;
    }
    
    // Verifica se os campos estão vazios antes de inserir dados no banco.
    if(!empty($cpf)) {
    
        // Verifica se o CPF informado é uma sequência de 11 números
        // através de Regex.
        if(!preg_match("/^\d{11}$/", $cpf)) {
            ?>
            <script>
            msg_destacada.textContent += " CPF Inválido."
            </script>
            <?php
            $cpfinvalido = true;
        }
    }
    
    if(!empty($nome)) {
    
        // Verifica se o nome informado é uma contém apenas letras e espaços
        // através de Regex.
        if(preg_match("/[^a-zA-Z\s]/", $nome)) {
            ?>
            <script>
            msg_destacada.textContent += " Nome Inválido."
            </script>
            <?php
            $nomeinvalido = true;
        }
    }
    
    
    if(!empty($creci)) {
    
        // Verifica se o Creci informado é uma sequência de duas letras maiúsculas,
        // "-", sequência de 4 a 6 números, "-", e as letras "F" ou "J", através de Regex.
        if(!preg_match("/^[A-Z]{2}-\d{4,6}-[FJ]$/", $creci)) {
            ?>
            <script>
            msg_destacada.textContent += " Creci Inválido."
            </script>
            <?php
            $creciinvalido = true;
        }
    }
       
    
        // Se estiver tudo certo, adiciona os dados no banco.
        $sql = "INSERT INTO corretores (name, cpf, creci) VALUES ('$nome', '$cpf', '$creci')";
    
        if(!$cpfduplicado && !$cpfinvalido && !$nomeduplicado && !$nomeinvalido && !$creciduplicado && !$creciduplicado && !$creciinvalido && !$vazio) {
            mysqli_query($conn, $sql);
            $sql = "SELECT * FROM corretores WHERE name ='$nome' AND cpf = '$cpf' AND creci = '$creci'";
            $result = mysqli_query($conn, $sql);
            imprimir_dados($result);
        }
        /*else{
            $sql = "SELECT * FROM corretores";
            $result = mysqli_query($conn, $sql);
            imprimir_dados($result);
        }*/
}



?>

<!-- Exibe os elementos da tabela corretores -->.
<?php
      $sql = "SELECT * FROM corretores";
      $result = mysqli_query($conn, $sql);
      
    // Cria linhas para a tabela com os dados da consulta sql.
    function imprimir_dados($result){
        while ($corretor = mysqli_fetch_assoc($result)) {
            echo "<tr>";
                echo "<td class='itens_tabela'>" . $corretor['id'] . "</td>";
                echo "<td class='itens_tabela'>" . $corretor['name'] . "</td>";
                echo "<td class='itens_tabela'>" . $corretor['cpf'] . "</td>";
                echo "<td class='itens_tabela'>" . $corretor['creci'] . "</td>";

                // Cria os botões Editar e Deletar em cada linha da tabela,
                // cada um enviando o id de sua linha por url para suas páginas.
                echo "<td class='itens_tabela'> 
                        <div class='edit_del'>
                            <a href=editar.php?id={$corretor['id']}>
                            <button>Editar</button></a>
            
                            <a href=deletar.php?id={$corretor['id']}>
                            <button>Deletar</button></a>
                        </div>
                    </td>";

            echo "</tr>";
        }
    }
mysqli_close($conn);
?>

</table>

</body>
</html>