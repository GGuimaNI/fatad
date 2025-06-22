<?php

session_start(); // Iniciar a sessão

// Limpar o buffer
ob_start();

// Incluir a conexão com BD
include_once './includes/conn.php';
require_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;

$idTurma = rtrim($_GET['idTurma']); 
$idCurso = rtrim($_GET['idCurso']);
$idNucleo = rtrim($_GET['idNucleo']);
$idDisciplina = rtrim($_GET['idDisciplina']);
$descNucleo=$fg->findDescNucleoEspecifico($idNucleo);
   foreach ($descNucleo as $row) {
      $nmNucleo=$row['descNucleo'];
      break;
   }
$disciplina=$fg->findDisciplinaEspecifica($idDisciplina);
   foreach ($disciplina as $row) {
      $nomeArq=$row['codigoDisciplina']."_".$row['nivelCurso'];
      break;
   }

// QUERY para recuperar os registros do banco de dados

$query_alunos =  "SELECT idHistorico,idNucleo, idTurma, idAluno, "
."(SELECT nivelCurso FROM tb_cursos WHERE idCurso=h.idCurso) as nivelCurso, "
."(SELECT nomeAluno FROM tb_aluno WHERE idAluno = h.idAluno ) as nomeAluno, "
."(SELECT codigoDisciplina FROM tb_disciplinas WHERE idDisciplina = h.idDisciplina ) as codigoDisciplina, nota "
."FROM tb_historico_aluno AS h "
."WHERE h.idTurma=$idTurma and h.idCurso=$idCurso and h.idNucleo=$idNucleo and idDisciplina=$idDisciplina";

// Preparar a QUERY
$result_alunos = $conn->prepare($query_alunos);

// Executar a QUERY
$result_alunos->execute();

// Acessa o IF quando encontrar registro no banco de dados
if(($result_alunos) and ($result_alunos->rowCount() != 0)){
    // Aceitar csv ou texto 
    header('Content-Type: text/csv; charset=utf-8');

    // Nome arquivo
    $nomeArq=$nomeArq."_".$nmNucleo;
    header('Content-Disposition: attachment; filename='.$nomeArq.'.csv');

    // Gravar no buffer
    $resultado = fopen("php://output", 'w');
  
    // Criar o cabeçalho do Excel - Usar a função mb_convert_encoding para converter carateres especiais  mb_convert_encoding('nomeAluno', 'ISO-8859-1', 'UTF-8')
    $cabecalho = ['idHistorico', 'idNucleo','idTurma','idAluno',mb_convert_encoding('nivelCurso', 'ISO-8859-1', 'UTF-8'), mb_convert_encoding('nomeAluno', 'ISO-8859-1', 'UTF-8'),'codigoDisciplina','nota' ];

    fputcsv($resultado, $cabecalho, ';');

    // Ler os registros retornado do banco de dados
    while($row_usuario = $result_alunos->fetch(PDO::FETCH_ASSOC)){
        // Escrever o conteúdo no arquivo
        fputcsv($resultado, $row_usuario, ';');
    }

    // Fechar arquivo
    fclose($resultado);
}else{ // Acessa O ELSE quando não encontrar nenhum registro no BD
    
    $_SESSION['msg'] = "<p style='color: #f00;'>Erro: Nenhum aluno encontrado!</p>";
    header("Location: matEscolarDistribuir.php");
}