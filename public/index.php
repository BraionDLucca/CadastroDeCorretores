<?php
include_once("database.php");
include_once("../includes/utils.php");
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

    // Valida os dados informados e retorna erros caso existir.
    $erros = validar_info($cpf, $creci, $nome, $erros, $conn);

    ?>

    <!-- Importa o elemento "msg_destacada" do html para que sejam adicionadas mensagens de erro. --> 
    <script>const msg_destacada = document.getElementById("msg_destacada")</script>

    <?php

    imprimir_erros($cpf, $creci, $nome, $erros);
    
    // Verifica se não há erros.
    if(!in_array(true, $erros, true)) {

        // Adiciona os dados informados no banco de dados.
        $sql = "INSERT INTO corretores (name, cpf, creci) VALUES ('$nome', '$cpf', '$creci')";
        
        if(!mysqli_query($conn, $sql)) {
            die("Erro ao deletar dados.");
        }

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