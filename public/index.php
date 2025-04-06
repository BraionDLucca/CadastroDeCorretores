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

$erros = array_fill(0, 7, '');
    
// Função para criar linhas para a tabela exibida no site com os dados da consulta sql.
function imprimir_dados($conn, $sql) {

    // Armazena os dados da consulta sql em result.
    $result = mysqli_query($conn, $sql);
    
    while ($corretor = mysqli_fetch_assoc($result)) { // Percorre cada linha da tabela armazendas em
                                                      // um array associativo a cada iteração.
        if (!is_null($corretor)) {    
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
}

// Consulta os dados do banco e imprime na tabela do site.
$sql = "SELECT * FROM corretores";
imprimir_dados($conn, $sql);

// Começo dos eventos ao clicar em "Enviar".
if (isset($_POST["enviar"])){

    // Importando valores informados no html para php através de POST. 
    $cpf = $_POST["cpf"];
    $nome = $_POST["nome"];
    $creci = $_POST["creci"];

    // Verifica se todos os campos estão vazios.
    if(empty($cpf) || empty($nome) || empty($creci)) {
        $erros[0] = true;
    }

    // Verifica se o CPF informado não é válido (uma sequência de 11 números)
    // através de Regex.
    if(!preg_match("/^\d{11}$/", $cpf)) {
        $erros[1] = true;
    } else {

        // Se válido, verifica se o CPF já existe no banco de dados.
        $sql = "SELECT id FROM corretores WHERE cpf = '$cpf'";

        if(mysqli_num_rows(mysqli_query($conn, $sql)) > 0) {
            $erros[2] = true;
        }
    }
    
    // Verifica se o CRECI informado não é uma sequência de duas letras maiúsculas,
    // "-", sequência de 4 a 6 números, "-", e as letras "F" ou "J", através de Regex.
    if(!preg_match("/^[A-Z]{2}-\d{4,6}-[FJ]$/", $creci)) {
        $erros[3] = true;
    } else {

        // Se válido, verifica se o CRECI já existe no banco de dados.
        $sql_creci = "SELECT id FROM corretores WHERE creci = '$creci'";
        
        if (mysqli_num_rows(mysqli_query($conn, $sql_creci)) > 0) {
            $erros[4] = true;
        }
    }

    // Verifica se o nome informado contém apenas letras e espaços através de Regex.
    if(preg_match("/[^a-zA-Z\s]/", $nome) || strlen($nome) < 4) {
        $erros[5] = true;
    } else {

        // Se válido, verifica se o nome já existe no banco de dados.
        $sql_name = "SELECT id FROM corretores WHERE name = '$nome'";

        if (mysqli_num_rows(mysqli_query($conn, $sql_name)) > 0 && $erros[2]) {
            $erros[6] = true;   // O nome só é considerado já cadastrado se exitir no
        }                       // banco de dados junto do CPF informado.
    }

    ?>

    <!-- Importa o elemento "msg_destacada" do html para que sejam adicionadas mensagens de erro. --> 
    <script>const msg_destacada = document.getElementById("msg_destacada")</script>

    <?php

    // Impressão de erros.
    if ($erros[0]) {
        ?>
        <script>msg_destacada.textContent += " Todos os campos são obrigatórios."</script>
        <?php
    }
    
    // Se CPF não está vazio, verifica se é válido.
    if (!empty($cpf) && $erros[1]) {
        ?>
        <script>msg_destacada.textContent += " CPF inválido."</script>
        <?php

    } else if ($erros[2]) {
        ?>
        <script>msg_destacada.textContent += "CPF já cadastrado."</script>
        <?php
    }

    // Se CRECI não está vazio, verifica se é válido.
    if ($erros[3] && !empty($creci)) {
        ?>
        <script>msg_destacada.textContent += " Creci Inválido."</script>
        <?php
    
    } elseif ($erros[4]) {
        ?>
        <script>msg_destacada.textContent += " CRECI já cadastrado."</script>
        <?php
    }
    
    // Se nome não está vazio, verifica se é válido.
    if ($erros[5] && !empty($nome)) {
        ?>
        <script>msg_destacada.textContent += " Nome Inválido."</script>
        <?php
        
    } elseif ($erros[6]) { // Se válido, verifica se 
        ?>
        <script>msg_destacada.textContent += " Nome já cadastrado."</script>
        <?php
    }

    // Verifica se não há erros.
    if(!in_array(true, $erros, true)) {

        // Adiciona os dados informados no banco de dados.
        $sql = "INSERT INTO corretores (name, cpf, creci) VALUES ('$nome', '$cpf', '$creci')";
        mysqli_query($conn, $sql);

        // Imprime os dados recém adicionados na tabela do site.
        $sql = "SELECT * FROM corretores WHERE name ='$nome' AND cpf = '$cpf' AND creci = '$creci'";
        imprimir_dados($conn, $sql);
    }
} // Fim dos eventos ao clicar em "Enviar".

mysqli_close($conn);
?>

</table>

</body>
</html>