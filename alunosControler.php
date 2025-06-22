<?php
include_once './fatadgestaoControler.php';
include_once './alunosModelClass.php';
$fg = new fatadgestaoControler;
$am=new alunosModelClass;

//$da= new dadosDeAlunos;

//if (isset($_REQUEST['btn'])) {
//    var_dump($_REQUEST);
//    $aluno=$_REQUEST['idAluno'];
//$idAluno= $da->setIdAluno($aluno);
//}


//var_dump($idAluno);

//if (isset($_REQUEST['btn'])) {
//    $action = $_REQUEST['btn'];
//    var_dump($action);
//    
//    if ($action == 'Nota') {
//        $a=new abrirFormularios;
//        $a->atribuirNota($idAluno);
//    }
//    
//     if ($action == 'Apostila') {
//        $a=new abrirFormularios;
//        $a->atribuirApostila($idAluno);
//}
//}

class alunosController {
    
 function atribuirNota($idAluno) {
        session_start();
        $id=$idAluno;
        header("Location: atribuirNota.php");
    
    } 
    
    function atribuirApostila($idAluno) {
        session_start();
        header("Location: atribuirApostila.php?$idAluno");
    }
    
}


