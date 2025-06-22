<?php
require './conexao.php';

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

$idNucleo = $_GET['idNucleo'];

// Consulta ao banco de dados para obter o total de linhas para o idNucleo selecionado
$sql = "SELECT COUNT(DISTINCT idAluno) AS total_linhas FROM tb_historico_aluno WHERE idNucleo=$idNucleo";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo $row['total_linhas'];
} else {
    echo "0";
}

$conn->close();
?>
