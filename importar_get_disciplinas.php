<?php
include_once("conexao.php");
include_once './fatadgestaoControler.php';
$fg=new fatadgestaoControler;

if (isset($_POST['idCurso'])) {
    $idCurso = $_POST['idCurso'];
    $fg = new fatadgestaoControler;
    $rsDisciplinas = $fg->findDisciplinasPorCurso($idCurso);
    if ($rsDisciplinas) {
        echo '<option value="">Escolha Disciplina</option>';
        foreach ($rsDisciplinas as $row) {
            echo "<option value='{$row->idDisciplina}'>{$row->nomeDisciplina}</option>";
        }
    } else {
        echo '<option value="">Nenhuma Disciplina Encontrada</option>';
    }
}
?>
