<?php
session_start();
require './config.php';
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$nomeAluno="";
$idNucleo="";
//   var_dump($_POST);
//   var_dump($_GET);
//   exit();

if (isset($_POST['matricula_aluno'])) {
    try {
        $pdo = new Config;
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();

        // Sanitize & Validate inputs
        $idAluno = filter_input(INPUT_POST, 'idAluno', FILTER_VALIDATE_INT);
        $idCurso = filter_input(INPUT_POST, 'idCurso', FILTER_VALIDATE_INT);
        $idTurma = filter_input(INPUT_POST, 'idTurma', FILTER_VALIDATE_INT);
        $nrMatricula = filter_input(INPUT_POST, 'nrMatricula', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dtIniEstudo = trim($_POST['dtMatricula']);
        $opcao     = trim($_POST['opcao']);
        $opcao  = ($opcao === 'sim') ? 1 : 0;
        $valorMaterial     = filter_var(str_replace(',', '.', $_POST['valorMaterial']), FILTER_VALIDATE_FLOAT);
                $situacao = 'Matriculado';
        if($opcao==1){
            $valorEncadernacao = filter_var(str_replace(',', '.', $_POST['encadernacao']), FILTER_VALIDATE_FLOAT);
            $frete             = filter_var(str_replace(',', '.', $_POST['frete']), FILTER_VALIDATE_FLOAT);
        }
        
        if (!$idAluno || !$idCurso || !$idTurma || !$nrMatricula || !$dtIniEstudo) {
            throw new Exception("Dados obrigatórios ausentes ou inválidos.");
        }

        // Verifica se aluno já está matriculado
        $stmt = $pdo->prepare("SELECT 1 FROM tb_matricula WHERE idAluno = :idAluno AND idTurma = :idTurma");
        $stmt->execute(['idAluno' => $idAluno, 'idTurma' => $idTurma]);
        if ($stmt->fetch()) {
            $_SESSION['message'] = "Esse aluno já está na turma selecionada. Nada foi feito.";
            header('Location: cadastroListVisitantes.php');
            exit;
        }

        // Busca currículo
        $stmt = $pdo->prepare("SELECT idDisciplinaCurriculo AS idd FROM tb_curriculo_Disciplinar WHERE idCursoCurriculo = :idCurso");
        $stmt->execute(['idCurso' => $idCurso]);
        $curriculo = $stmt->fetchAll(PDO::FETCH_COLUMN);

        // Busca núcleo
        $stmt = $pdo->prepare("SELECT idNucleo FROM fatadgestao.tb_turma WHERE idTurma = :idTurma");
        $stmt->execute(['idTurma' => $idTurma]);
        $idNucleo = $stmt->fetchColumn();

        // Busca nome do aluno
        $stmt = $pdo->prepare("SELECT nomeAluno FROM tb_aluno WHERE idAluno = :idAluno");
        $stmt->execute(['idAluno' => $idAluno]);
        $nomeAluno = $stmt->fetchColumn();

        // Inserir matrícula com campos adicionais
        $stmt = $pdo->prepare("
            INSERT INTO tb_matricula (nrMatricula, idAluno, idTurma, dtMatricula, opcao, valorMaterial, valorEncadernacao, frete)
            VALUES (:nrMatricula, :idAluno, :idTurma, :dtMatricula, :opcao, :valorMaterial, :valorEncadernacao, :frete)
        ");
        $stmt->execute([
            'nrMatricula'     => $nrMatricula,
            'idAluno'         => $idAluno,
            'idTurma'         => $idTurma,
            'dtMatricula'     => $dtIniEstudo,
            'opcao'           => $opcao,
            'valorMaterial'   => $valorMaterial,
            'valorEncadernacao'    => $valorEncadernacao,
            'frete'           => $frete
        ]);

        // Inserir histórico do aluno
        foreach ($curriculo as $idDisciplina) {
            $stmt = $pdo->prepare("
                INSERT INTO tb_historico_aluno (idAluno, idCurso, idNucleo, idTurma, idDisciplina, situacao)
                VALUES (:idAluno, :idCurso, :idNucleo, :idTurma, :idDisciplina, :situacao)
            ");
            $stmt->execute([
                'idAluno'     => $idAluno,
                'idCurso'     => $idCurso,
                'idNucleo'    => $idNucleo,
                'idTurma'     => $idTurma,
                'idDisciplina'=> $idDisciplina,
                'situacao'    => $situacao
            ]);
        }

        $pdo->commit();
        $_SESSION['message'] = "Tudo certo com a matrícula de $nomeAluno.";
        header('Location: cadastroListVisitantes.php');
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $_SESSION['message'] = "Erro: " . $e->getMessage();
        header('Location: cadastroListVisitantes.php');
        exit;
    }
}