<?php

function validar_info($cpf, $creci, $nome, $erros, $conn) {
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
    return $erros;
}

function imprimir_erros($cpf, $creci, $nome, $erros) {
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

    } else if ($erros[2]) {// Se válido, verifica se o CPF já está cadastrado.
        ?>
        <script>msg_destacada.textContent += "CPF já cadastrado."</script>
        <?php
    }

    // Se CRECI não está vazio, verifica se é válido.
    if ($erros[3] && !empty($creci)) {
        ?>
        <script>msg_destacada.textContent += " CRECI Inválido."</script>
        <?php

    } elseif ($erros[4]) { // Se válido, verifica se o CRECI já está cadastrado.
        ?>
        <script>msg_destacada.textContent += " CRECI já cadastrado."</script>
        <?php
    }

    // Se nome não está vazio, verifica se é válido.
    if ($erros[5] && !empty($nome)) {
        ?>
        <script>msg_destacada.textContent += " Nome Inválido."</script>
        <?php
        
    } elseif ($erros[6]) { // Se válido, verifica se o nome já está cadastrado.
        ?>
        <script>msg_destacada.textContent += " Nome já cadastrado."</script>
        <?php
    }
}
?>