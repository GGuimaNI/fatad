<?php
session_start();
require './conexao.php';
require './config.php';
$pdo = new Config();
include_once './fatadgestaoControler.php';
$fg=new fatadgestaoControler;

// var_dump($_POST);
// exit();

$nomeDisciplina="";
$codigoDisciplina="";
$nivelCurso="";
$idCurso=0;

if (isset($_POST['excluir_documento'])) {
    // var_dump($_POST);
    // exit();
    $idDowndocs= filter_input(INPUT_POST, 'idDowndocs', FILTER_SANITIZE_NUMBER_INT);
    $idDisciplina= filter_input(INPUT_POST, 'idDisciplina', FILTER_SANITIZE_NUMBER_INT);

    $disciplina=$fg->findDisciplinaEspecifica($idDisciplina);
    foreach($disciplina as $row){
       $nomeDisciplina=$row['nomeDisciplina'];
       $codigoDisciplina=$row['codigoDisciplina'];
       $nivelCurso=$row['nivelCurso'];
       $idCurso=$row['idCurso'];
    }
    
        $sql = "DELETE from tb_downdocs where idDowndocs=$idDowndocs";
        mysqli_query($conn, $sql);
        if (mysqli_affected_rows($conn) > 0) {
            mysqli_close($conn);
            $_SESSION['message'] = 'Arquivo excluído com sucesso';
             ?>
                    <meta http-equiv="refresh" content="0;url=mntDocumentosListar.php
                    ?idCurso=<?=$idCurso?>
                    &idDisciplina=<?=$idDisciplina?>
                    &nivelCurso=<?=$nivelCurso?>
                    &nomeDisciplina=<?=$nomeDisciplina?>">
        <?php
            
        } else {
            mysqli_close($conn);
            $_SESSION['message'] = 'Arquivo não foi excluído';
            ?>
                <meta http-equiv="refresh" content="0;url=mntDocumentosListar.php
                    ?idCurso=<?=$idCurso?>
                    &idDisciplina=<?=$idDisciplina?>
                    &nivelCurso=<?=$nivelCurso?>
                    &nomeDisciplina=<?=$nomeDisciplina?>">
        <?php
            
        }
    }
