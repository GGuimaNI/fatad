<?php
session_start();
require './conexao.php';
include_once './config.php';
$pdo = new Config();
require_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
if (isset($_SESSION['usuario_autenticado'])) {
  $privilegio = $_SESSION['privilegio'];
  $usuario=$_SESSION['usuario'];
} else {
  echo 'Sessão não iniciada ou privilégio não definido.';
  // Redirecionar para a página de login ou mostrar uma mensagem de erro
  header('Location: logout.php'); exit();
}
// var_dump($_SESSION);
// var_dump($_POST);
// var_dump($_GET);
// exit();
 
if (isset($_POST['OpFinanNucleo'])) {
    $descOp = mysqli_real_escape_string($conn, trim($_POST['descOp']));
    $tipoOp = mysqli_real_escape_string($conn, trim($_POST['tipoOp']));
    $dtContrato = mysqli_real_escape_string($conn, trim($_POST['dtContrato']));
    $dtPagamento = mysqli_real_escape_string($conn, trim($_POST['dtPagamento']));
    $qtdMat = mysqli_real_escape_string($conn, trim($_POST['qtdMat']));
    $valorUnit = mysqli_real_escape_string($conn, trim($_POST['valorUnit']));
    $idResponsavel = mysqli_real_escape_string($conn, trim($_POST['idResponsavel']));
    $perfil = mysqli_real_escape_string($conn, trim($_POST['perfil']));
    $idDisciplina = mysqli_real_escape_string($conn, trim($_POST['idDisciplina']));
    $idTurma = mysqli_real_escape_string($conn, trim($_POST['idTurma']));
    $idNucleo = mysqli_real_escape_string($conn, trim($_POST['idNucleo']));
    $idCurso = mysqli_real_escape_string($conn, trim($_POST['idCurso']));
    
    $recebeMat=$fg->findAlunoRecebeMaterial($idAluno,$idTurma);
    if ($recebeMat->opcao == 1) {
        $frete = mysqli_real_escape_string($conn, trim($_POST['frete']));
    }else{
    $frete = 0;
    }
    $valorTotal=$valorUnit*$qtdMat+$frete;

    if(empty($dtPagamento)){
      $sql = "INSERT INTO tb_op_financeira "
                ."(descOp,idMaterial,idTurma, tipoOp, dtContrato, qtdMat, valorUnitario, frete,valorTotal, idResp, perfil) "
                ."VALUES "
                ."('$descOp','$idDisciplina','$idTurma','$tipoOp','$dtContrato','$qtdMat','$valorUnit','$frete','$valorTotal','$idResponsavel','$perfil')"; 
    }else{
      $sql = "INSERT INTO tb_op_financeira "
                ."(descOp,idMaterial,idTurma, tipoOp, dtContrato, dtPagamento, qtdMat, valorUnitario, frete,valorTotal, idResp, perfil) "
                ."VALUES "
                ."('$descOp','$idDisciplina','$idTurma','$tipoOp','$dtContrato','$dtPagamento','$qtdMat','$valorUnit','$frete','$valorTotal','$idResponsavel','$perfil')";  
    }
    
//    $imp=$fg->printEnderecamento($perfil,$id);
	
//        var_dump($sql);
        mysqli_query($conn, $sql);

	if (mysqli_affected_rows($conn) > 0) {
            $sql="UPDATE tb_historico_aluno  SET dtIniEstudo='$dtContrato' "
                  ."where idTurma=$idTurma and idCurso=$idCurso and idNucleo=$idNucleo and idDisciplina=$idDisciplina";

            
            mysqli_query($conn, $sql);

            if (mysqli_affected_rows($conn) > 0) 
            {
                $conn->close();
                $imp=$fg->printEnderecamento($perfil,$idNucleo);
//                if($imp){sleep(5);}
            $_SESSION['mensagem'] = 'Op Financeira e Registro no Histórico realizados com sucesso.';
            header('Location: matEscolarDistribuir.php?idNucleo='.$idNucleo.'&idTurma='.$idTurma.'&idCursoCurriculo='.$idCurso.'&qtdAlunos='.$qtdMat);
            exit();    

            } else {
                $conn->close();
                $_SESSION['mensagem'] = 'Op Financeira = OK, mas FALHOU a inclusão no Histórico.';
                header('Location: matEscolarDistribuir.php?idNucleo='.$idNucleo.'&idTurma='.$idTurma.'&idCursoCurriculo='.$idCurso.'&qtdAlunos='.$qtdMat);
                exit();
            }
    
        }else{
            $conn->close();
            $_SESSION['mensagem'] = 'FALHOU a inclusão em Op Financeira e no Histórico.';
		    header('Location: matEscolarDistribuir.php?idNucleo='.$idNucleo.'&idTurma='.$idTurma.'&idCursoCurriculo='.$idCurso.'&qtdAlunos='.$qtdMat);
            exit();
        }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['OpFinanAluno'])) {
//  var_dump($_POST);
//  var_dump($privilegio);
//  exit();
    try {

        $conn = new Config;
        $conn->beginTransaction();

        // Filtro e validação de dados
        function filtrarTexto($campo) {
            return isset($_POST[$campo]) ? trim(htmlspecialchars($_POST[$campo], ENT_QUOTES, 'UTF-8')) : '';
        }

        $descOp = filtrarTexto('descOp');
        $tipoOp = filtrarTexto('tipoOp');
        $qtdMat = filter_input(INPUT_POST, 'qtdMat', FILTER_VALIDATE_INT) ?: 0;
        $valorUnitario = filter_input(INPUT_POST, 'valorUnit', FILTER_VALIDATE_FLOAT) ?: 0;
        $valorEncadernacao = filter_input(INPUT_POST, 'valorEncadernacao', FILTER_VALIDATE_FLOAT) ?: 0;
        $frete = filter_input(INPUT_POST, 'frete', FILTER_VALIDATE_FLOAT) ?: 0;
        $idResp = filter_input(INPUT_POST, 'idResponsavel', FILTER_VALIDATE_INT);
        $perfil = filtrarTexto('perfil');
        $idMaterial = filter_input(INPUT_POST, 'idDisciplina', FILTER_VALIDATE_INT);
        $idTurma = filter_input(INPUT_POST, 'idTurma', FILTER_VALIDATE_INT);
        $idAluno = filter_input(INPUT_POST, 'idAluno', FILTER_VALIDATE_INT);
        $idCurso = filter_input(INPUT_POST, 'idCurso', FILTER_VALIDATE_INT);
        
        date_default_timezone_set('America/Sao_Paulo');
        $dtPagamento = null;
        if ($privilegio !== "opAluno") {
            $pagamento = trim($_POST['pagamento'] ?? '');
            if ($pagamento === 'sim') {
                $dtPagamento = date('Y-m-d');
            }
        }
        $dtContrato = date('Y-m-d');
       
        $cpf = preg_replace('/[^0-9]/', '', $idResp);

        $recebeMat = $fg->findAlunoRecebeMaterial($idAluno, $idTurma);
        $frete = ($recebeMat && $recebeMat->opcao === 1) ? $frete : 0;

        $valorTotal = $valorUnitario + $valorEncadernacao + $frete;
        $msg = '';
        $idOp = 0;

        if (!empty($_FILES['documento']['name'])) {

            $arquivo = $_FILES['documento'];
            $tiposPermitidos = ['pdf', 'jpg', 'jpeg', 'png']; // extensões, não MIME types
            $caminhoBase = "C:/ArquivosFatad/uploads/recibos/{$cpf}";

            // Usa a função utilitária
            $novoNome = validarUpload($arquivo, $caminhoBase, $tiposPermitidos);

            // Caminho completo, se quiser usar depois
            $caminhoCompleto = $caminhoBase . DIRECTORY_SEPARATOR . $novoNome;
        }

        // Insere dados financeiros
        $sql = "INSERT INTO tb_op_financeira 
                    (descOp, idMaterial, idTurma, tipoOp, dtContrato, dtPagamento, qtdMat, valorUnitario, valorEncadernacao, frete, valorTotal, idResp, perfil) 
                VALUES 
                    (:descOp, :idMaterial, :idTurma, :tipoOp, :dtContrato, :dtPagamento,:qtdMat, :valorUnitario,:valorEncadernacao, :frete, :valorTotal, :idResp, :perfil)";
        $stmt = $conn->prepare($sql);
        // Monta array explícito para o bind de parâmetros
        $dadosFinanceiros = [
            'descOp'            => $descOp ?? '',
            'idMaterial'        => $idMaterial ?? null,
            'idTurma'           => $idTurma ?? null,
            'tipoOp'            => $tipoOp ?? '',
            'dtContrato'        => $dtContrato ?? date('Y-m-d'),
            'dtPagamento'       => $dtPagamento ?? null,
            'qtdMat'            => $qtdMat ?? 0,
            'valorUnitario'     => $valorUnitario ?? 0,
            'valorEncadernacao' => $valorEncadernacao ?? 0,
            'frete'             => $frete ?? 0,
            'valorTotal'        => $valorTotal ?? 0,
            'idResp'            => $idResp ?? null,
            'perfil'            => $perfil ?? ''
        ];

        // Checagem básica para debug — remove depois de testar
        foreach ($dadosFinanceiros as $param => $valor) {
            if (!array_key_exists($param, $dadosFinanceiros)) {
                throw new Exception("Parâmetro ausente: $param");
            }
        }

        // Agora sim, executa de boa
        $stmt = $conn->prepare($sql);
        $stmt->execute($dadosFinanceiros);

        $idOp = $conn->lastInsertId();

        // Atualiza histórico
        if ($idOp) {
            $sql = "UPDATE tb_historico_aluno SET dtIniEstudo = :dtContrato
                    WHERE idTurma = :idTurma AND idCurso = :idCurso AND idAluno = :idAluno AND idDisciplina = :idDisciplina";
            $paramsUpdate = [
                'dtContrato'   => $dtContrato ?? null,
                'idTurma'      => $idTurma ?? null,
                'idCurso'      => $idCurso ?? null,
                'idAluno'      => $idAluno ?? null,
                'idDisciplina' => $idMaterial ?? null
            ];

            $stmt = $conn->prepare($sql);
            $stmt->execute($paramsUpdate);

            if (!$stmt->rowCount()) {
                $msg .= " Alerta: histórico não foi atualizado.";
            }
        }

        if($privilegio=="opAluno"){
        // Grava recibo
            if (isset($novoNome)) {
                $caminhoCompleto = $caminhoNome . $novoNome;
                $sql = "INSERT INTO tb_recibos (cpf, idOp, caminhoNomeArq, nomeArq)
                        VALUES (:cpf, :idOp, :caminhoNomeArq, :nomeArq)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
                $stmt->bindParam(':idOp', $idOp, PDO::PARAM_INT);
                $stmt->bindParam(':caminhoNomeArq', $caminhoCompleto, PDO::PARAM_STR);
                $stmt->bindParam(':nomeArq', $novoNome, PDO::PARAM_STR);

                if (!$stmt->execute()) {
                    throw new Exception("Erro ao registrar o recibo.");
                }
            }
        }
        $conn->commit();
        $_SESSION['mensagem'] = "Material solicitado com sucesso. Liberado em até 24h.";
    } catch (Exception $e) {
        if ($conn->inTransaction()) {
            $conn->rollback();
        }
        error_log("[ERRO] " . $e->getMessage(), 3, __DIR__ . '/logs/financeiro.log');
        $_SESSION['mensagem'] = "Erro: " . $e->getMessage();
    }

    // Redireciona
    if($privilegio=="opAluno"){
        header("Location: matEscolarDistribuirIndiv.php?idAluno={$idAluno}&cpf={$cpf}&idTurma={$idTurma}&idCurso={$idCurso}");
        exit();
    }else{
        header("Location: matEscolarAlunoDistribuir.php?idAluno={$idAluno}&cpf={$cpf}&idTurma={$idTurma}&idCurso={$idCurso}");
        exit(); 
    }
}


if (isset($_POST['OpFinanNucleoReceber'])){
    
    $dtPagamento = mysqli_real_escape_string($conn, trim($_POST['dtPagamento']));
    $idOp = mysqli_real_escape_string($conn, trim($_POST['idOp']));
    $qtdMat = mysqli_real_escape_string($conn, trim($_POST['qtdMat']));
    $idTurma = mysqli_real_escape_string($conn, trim($_POST['idTurma']));
    $idNucleo = mysqli_real_escape_string($conn, trim($_POST['idNucleo']));
    $idCurso = mysqli_real_escape_string($conn, trim($_POST['idCurso']));
    $valorTotal=$valorUnit*$qtdMat+$frete;
  
    $sql="UPDATE tb_op_financeira SET dtPagamento='$dtPagamento' WHERE idOp=$idOp";
//          var_dump($sql);
    mysqli_query($conn, $sql);
    if (mysqli_affected_rows($conn) > 0) {
        $conn->close();
        $_SESSION['mensagem'] = 'Pagamento registrado com sucesso';
        header('Location: matEscolarDistribuir.php?idNucleo='.$idNucleo.'&idTurma='.$idTurma.'&idCursoCurriculo='.$idCurso.'&qtdAlunos='.$qtdMat);
    } else {
        $conn->close();
        $_SESSION['mensagem'] = 'FALHOU registro do pagamento.';
        header('Location: matEscolarDistribuir.php?idNucleo='.$idNucleo.'&idTurma='.$idTurma.'&idCursoCurriculo='.$idCurso.'&qtdAlunos='.$qtdMat);
    }
}   
    
if (isset($_POST['OpFinanAlunoReceber'])){
    
    $dtPagamento = mysqli_real_escape_string($conn, trim($_POST['dtPagamento']));
    $idOp = mysqli_real_escape_string($conn, trim($_POST['idOp']));
    $qtdMat = mysqli_real_escape_string($conn, trim($_POST['qtdMat']));
    $idTurma = mysqli_real_escape_string($conn, trim($_POST['idTurma']));
    $idAluno = mysqli_real_escape_string($conn, trim($_POST['idAluno']));
    $cpf = mysqli_real_escape_string($conn, trim($_POST['cpf']));
    $idCurso = mysqli_real_escape_string($conn, trim($_POST['idCurso']));
    // $valorTotal=$valorUnit*$qtdMat+$frete;
    $sql="UPDATE tb_op_financeira SET dtPagamento='$dtPagamento' WHERE idOp=$idOp";
//          var_dump($sql);
    mysqli_query($conn, $sql);
    if (mysqli_affected_rows($conn) > 0) {
        $conn->close();
        $_SESSION['mensagem'] = 'Pagamento registrado com sucesso';
        header('Location: matEscolarAlunoDistribuir.php?idAluno='.$idAluno.'&cpf='.$cpf.'&idTurma='.$idTurma.'&idCurso='.$idCurso);
    } else {
        $conn->close();
        $_SESSION['mensagem'] = 'FALHOU registro do pagamento.';
        header('Location: matEscolarAlunoDistribuir.php?idAluno='.$idAluno.'&cpf='.$cpf.'&idTurma='.$idTurma.'&idCurso='.$idCurso);
    }
}   
    
if (isset($_POST['OpFinanGestaoReceber'])){
    
    $dtPagamento = mysqli_real_escape_string($conn, trim($_POST['dtPagamento']));
    $idOp = mysqli_real_escape_string($conn, trim($_POST['idOp']));
    $qtdMat = mysqli_real_escape_string($conn, trim($_POST['qtdMat']));
    $idTurma = mysqli_real_escape_string($conn, trim($_POST['idTurma']));
//    $idNucleo = mysqli_real_escape_string($conn, trim($_POST['idNucleo']));
    $idCurso = mysqli_real_escape_string($conn, trim($_POST['idCurso']));
    $valorTotal=$valorUnit*$qtdMat+$frete;
  
    $sql="UPDATE tb_op_financeira SET dtPagamento='$dtPagamento' WHERE idOp=$idOp";
//          var_dump($sql);
    mysqli_query($conn, $sql);
    if (mysqli_affected_rows($conn) > 0) {
        $conn->close();
        $_SESSION['mensagem'] = 'Pagamento registrado com sucesso';
        header('Location: opFinanceiraGestao.php');
    } else {
        $conn->close();
        $_SESSION['mensagem'] = 'FALHOU registro do pagamento.';
        header('Location: opFinanceiraGestao.php');
    }
}   
function salvarArquivo($arquivo, $diretorioUpload)
{
    try {
        if (!is_dir($diretorioUpload)) {
            mkdir($diretorioUpload, 0777, true);
        }

        if (!isset($arquivo) || $arquivo['error'] !== UPLOAD_ERR_OK) {
            return false;
        }
        
        $nomeOriginal = $arquivo['name'];
        $novoNome = time() . "_" . basename($nomeOriginal);
        $caminhoCompleto = $diretorioUpload . $novoNome;

        if (!move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
            return false;
        }

        // Aqui, verifique explicitamente se o arquivo existe no destino
        if (!file_exists($caminhoCompleto)) {
            return false; // Se o arquivo não está onde deveria estar, algo deu errado
        }

        // Se chegou até aqui, significa que deu tudo certo
        return $novoNome;
    } catch (Exception $e) {
        return false;
    }
}
function validarUpload($arquivo, $destino, $permitidos = ['pdf', 'jpg', 'jpeg', 'png'], $limiteBytes = 5 * 1024 * 1024) {
    if (
        !isset($arquivo['tmp_name']) ||
        $arquivo['error'] !== UPLOAD_ERR_OK ||
        !is_uploaded_file($arquivo['tmp_name'])
    ) {
        throw new Exception("Erro no envio do arquivo.");
    }

    // Verifica tamanho do arquivo
    if ($arquivo['size'] > $limiteBytes) {
        $tamanhoMB = round($arquivo['size'] / 1048576, 2);
        throw new Exception("O arquivo excede o limite de 5MB (atual: {$tamanhoMB}MB).");
    }

    // Verifica extensão
    $extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));
    if (!in_array($extensao, $permitidos)) {
        throw new Exception("Extensão .$extensao não permitida.");
    }

    // MIME type real (com fallback)
    $mime = mime_content_type($arquivo['tmp_name']);
    $mapaMime = [
        'pdf'  => 'application/pdf',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'png'  => 'image/png'
    ];

    if ($mime !== 'application/octet-stream') {
        if (!in_array($mime, $mapaMime)) {
            throw new Exception("Tipo MIME $mime não aceito.");
        }

        if ($mapaMime[$extensao] !== $mime) {
            throw new Exception("Extensão .$extensao não corresponde ao tipo MIME real.");
        }
    } else {
        error_log("⚠️ MIME indefinido ('octet-stream') para arquivo: {$arquivo['name']}\n", 3, __DIR__ . '/logs/upload.log');
    }

    $nomeUnico = uniqid('arq_') . '.' . $extensao;
    if (!file_exists($destino)) {
        if (!mkdir($destino, 0755, true)) {
            throw new Exception("Falha ao criar diretório de destino.");
        }
    }

    $caminhoCompleto = $destino . DIRECTORY_SEPARATOR . $nomeUnico;
    if (!move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
        throw new Exception("Erro ao mover o arquivo para destino.");
    }

    return $nomeUnico;
}