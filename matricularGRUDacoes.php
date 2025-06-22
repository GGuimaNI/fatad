<?php
session_start();
require './config.php';
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$nomeAluno="";
$idNucleo="";
//   var_dump($_POST);joao

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
        $opcaoInt  = ($opcao === 'sim') ? 1 : 0;
        $valorMaterial     = filter_var(str_replace(',', '.', $_POST['valorMaterial']), FILTER_VALIDATE_FLOAT);
        $valorEncadernacao = filter_var(str_replace(',', '.', $_POST['encadernacao']), FILTER_VALIDATE_FLOAT);
        $frete             = filter_var(str_replace(',', '.', $_POST['frete']), FILTER_VALIDATE_FLOAT);
        $situacao = 'Matriculado';

        if (!$idAluno || !$idCurso || !$idTurma || !$nrMatricula || !$dtIniEstudo) {
            throw new Exception("Dados obrigatórios ausentes ou inválidos.");
        }

        // Verifica se aluno já está matriculado
        $stmt = $pdo->prepare("SELECT 1 FROM tb_matricula WHERE idAluno = :idAluno AND idTurma = :idTurma");
        $stmt->execute(['idAluno' => $idAluno, 'idTurma' => $idTurma]);
        if ($stmt->fetch()) {
            $_SESSION['mensagem'] = "Esse aluno já está na turma selecionada. Nada foi feito.";
            header('Location: matricularGRUD.php');
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
            'opcao'           => $opcaoInt,
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
        $_SESSION['mensagem'] = "Tudo certo com a matrícula de $nomeAluno.";
        header('Location: matricularGRUD.php');
        exit;

    } catch (Exception $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        $_SESSION['mensagem'] = "Erro: " . $e->getMessage();
        header('Location: matricularGRUD.php');
        exit;
    }
}

if (isset($_POST['update_aluno'])) {
    //var_dump($_POST);
    // exit();
    try {
        $pdo = new Config;
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Sanitização e validação
        $idAluno        = filter_input(INPUT_POST, 'idAluno', FILTER_VALIDATE_INT);
        $nrMatricula    = filter_input(INPUT_POST, 'nrMatricula', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $dtMatricula    = trim($_POST['dtMatricula']);
        $opcao = isset($_POST['opcao']) && $_POST['opcao'] === 'sim' ? 1 : 0;
       // Prepara os campos (se nulos, vira string vazia)
        $valorMaterial     = isset($_POST['valorMaterial']) ? htmlspecialchars($_POST['valorMaterial']) : '';
       
        $valorEncadernacao      = isset($_POST['encadernacao']) ? htmlspecialchars($_POST['encadernacao']) : '';
        $frete             = isset($_POST['frete']) ? htmlspecialchars($_POST['frete']) : '';

        $valorMaterial=str_replace(',', '.', $valorMaterial);
        $valorEncadernacao = isset($_POST['encadernacao']) && $_POST['encadernacao'] !== ''
            ? str_replace(',', '.', $_POST['encadernacao'])
            : null;   
        $valorEncadernacao = is_numeric($valorEncadernacao) ? floatval($valorEncadernacao) : null;     
        
        $frete = isset($_POST['frete']) && $_POST['frete'] !== ''
            ? str_replace(',', '.', $_POST['frete'])
            : null;  
        $frete = is_numeric($frete) ? floatval($frete) : null;

        // Validação mínima
        if (!$idAluno || !$dtMatricula || !$nrMatricula) {
            throw new Exception("Dados obrigatórios ausentes ou inválidos.");
        }

        // Atualização com campos adicionais
        $sql = "UPDATE tb_matricula SET  
                    nrMatricula = :nrMatricula, 
                    dtMatricula = :dtMatricula, 
                    opcao = :opcao, 
                    valorMaterial = :valorMaterial,
                    valorEncadernacao = :valorEncadernacao,
                    frete = :frete
                WHERE idAluno = :idAluno";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nrMatricula'    => $nrMatricula,
            'dtMatricula'    => $dtMatricula,
            'opcao'          => $opcao,
            'valorMaterial'  => $valorMaterial,
            'valorEncadernacao'   => $valorEncadernacao,
            'frete'          => $frete,
            'idAluno'        => $idAluno
        ]);

        $_SESSION['mensagem'] = $stmt->rowCount() > 0 
            ? 'Matrícula atualizada com sucesso.' 
            : 'Nenhuma mudança foi feita na matrícula.';

        header('Location: matricularGRUD.php');
        exit;

    } catch (Exception $e) {
        $_SESSION['mensagem'] = 'Erro na atualização: ' . $e->getMessage();
        header('Location: matricularGRUD.php');
        exit;
    }
}

