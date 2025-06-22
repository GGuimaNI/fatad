<?php

/**
 * Description of alunosModelClass
 *
 * @author Usuário
 */   

class alunosModelClass {
    
private $idAlunoPrivate;
private $nomeAlunoPrivate;

    
    
    
 function atribuirNota($idAluno) {
        session_start();
        $id=$idAluno;
        header("Location: atribuirNota.php");
    
    } 
    
    function atribuirApostila($idAluno) {
        session_start();
        header("Location: atribuirApostila.php?$idAluno");
    }
    
    function getIdAluno() {
        return $this->idAlunoPrivate;
        var_dump($idAluno);
    }
    function getNomeAluno() {
        return $this->nomeAlunoPrivate;
    }
    function setIdAluno($idAluno) {
        $this->idAlunoPrivate = $idAluno;
        var_dump($idAluno);
    }
    function setNomeAluno($nomeAluno) {
        $this->nomeAlunoPrivate = $nomeAluno;
        var_dump($nomeAluno);
    } 
    
    
    function carregarAlunos($curso){
        $pdo = new Config();
        $select = $pdo->prepare("Select idAluno, nomeAluno from tb_aluno WHERE nomeAluno like '%$curso%'");
        $select->execute();
        return $select->fetchAll(PDO::FETCH_OBJ); 
    }
    
    function findCurriculo($curso) {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT idCursoCurriculo as idc,idDisciplinaCurriculo as idd " 
                       ." FROM tb_curriculo_Disciplinar"
                       ." WHERE idCursoCurriculo='$curso'");

            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    
    function findAlunoEspecifico($idAluno) {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT  nomeAluno FROM tb_aluno WHERE idAluno=$idAluno");

            $select->execute();
            return $select->fetchAll(PDO::FETCH_ASSOC); 
           
    }
    function findAluno() {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT  idAluno,nomeAluno FROM tb_aluno ORDER BY nomeAluno ");

            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ);            
    }
  
    
}
