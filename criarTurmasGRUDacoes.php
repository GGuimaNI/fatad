<?php
session_start();
require './conexao.php';

include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;

// var_dump($_POST);

if (isset($_POST['cadastro_curso'])) {
    $idCurso = mysqli_real_escape_string($conn, trim($_POST['idCurso']));
    $idNucleo = mysqli_real_escape_string($conn, trim($_POST['idNucleo']));
    $dtIni = mysqli_real_escape_string($conn, trim($_POST['dtIni']));
    $dtTer= mysqli_real_escape_string($conn, trim($_POST['dtTer']));
    
    
    $nmCurso=$fg->findCursoNucleo($idNucleo,$idCurso);
    $nome="";
    if($nmCurso){
        foreach ($nmCurso as $nm) {
           $nome=$nm->nomeCurso;
        }
        $_SESSION['mensagem'] = 'Já existe o curso '.$nome.' no Núcleo.';
        header('Location: criarTurmasGRUD.php');
	
    }else{   
         $sql = "INSERT INTO tb_turma (idNucleo, idCursoCurriculo, dtInicioCurso, dtTerminoCurso,ativo) "
                     ."VALUES ('$idNucleo','$idCurso','$dtIni','$dtTer',0)" ;
        
        var_dump($sql);
        mysqli_query($conn, $sql);
        // var_dump(mysqli_affected_rows($conn));
	if (mysqli_affected_rows($conn) > 0) {
	
		$_SESSION['mensagem'] = 'Turma cadastrada com sucesso';
		header('Location: criarTurmasGRUD.php');
		
	} else {
		$_SESSION['mensagem'] = 'Turma não foi cadastrada';
		header('Location: criarTurmasGRUD.php');
		
	}
    }
}
if (isset($_POST['update_curso'])) {
    $idTurma = mysqli_real_escape_string($conn, trim($_POST['idTurma']));
    $dtIni = mysqli_real_escape_string($conn, trim($_POST['dtIni']));
    $dtTer= mysqli_real_escape_string($conn, trim($_POST['dtTer']));
        
	 $sql = "UPDATE tb_turma SET dtInicioCurso='$dtIni',dtTerminoCurso='$dtTer' "
                 . "WHERE idTurma = '$idTurma'" ;     

	mysqli_query($conn, $sql);
	if (mysqli_affected_rows($conn) > 0) {
		$_SESSION['mensagem'] = 'Alteração foi bem sucedida.';
		header('Location: criarTurmasGRUD.php');
		
	} else {
		$_SESSION['mensagem'] = 'Falhou a atualização.';
		header('Location: criarTurmasGRUD.php');
		
	}
}
// Fechar a conexão 
mysqli_close($conn);
?>

