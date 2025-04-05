<?php

$server_name = "localhost";
$username = "root";
$password = "";
$db_name = "meu_banco5";

$conn = mysqli_connect($server_name, $username, $password);

if(!$conn) {
    die("Falha na conexão: " . mysqli_connect_error());
}

if (!$conn) {
    echo "Falha na conexão.";
}

$sql = "CREATE DATABASE IF NOT EXISTS $db_name";
if (!mysqli_query($conn, $sql)) {
    die("Erro ao criar banco de dados: " . mysqli_error($conn));
}

mysqli_select_db($conn, $db_name);

$sql = "CREATE TABLE IF NOT EXISTS corretores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    cpf VARCHAR(11) NOT NULL UNIQUE,
    creci VARCHAR(25) NOT NULL UNIQUE
)";

if (!mysqli_query($conn, $sql)) {
    die("Erro ao criar tabela: " . mysqli_error($conn));
}

?>