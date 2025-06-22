<?php
session_start();
include('config.php'); // Arquivo de conexão com o banco

if(isset($_POST['idCurso'])){
    $idCurso = $_POST['idCurso'];
    $stmt = $pdo->prepare("SELECT DISTINCT t.idTurma, t.nomeSala, CONCAT(n.descNucleo, ' (Sala: ', c.nomeCurso, ')') AS nomeNucleo, c.nivelCurso 
    FROM tb_turma AS t 
    JOIN tb_nucleofatad AS n ON t.idNucleo = n.idNucleo 
    JOIN tb_cursos AS c ON t.idCursoCurriculo = c.idCurso
     WHERE idCurso = ?");
    $stmt->execute([$idCurso]);

    while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        echo "<option value='{$row['idTurma']}'>{$row['nomeTurma']}</option>";
    }
}
?>