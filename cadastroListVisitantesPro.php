<?php
session_start();
include_once 'fatadgestaoControler.php';
$fg = new fatadgestaoControler;
require_once 'config.php'; // Arquivo de conexão com PDO]]

if (isset($_POST['liberarApostila']) && $_POST['liberarApostila'] == "liberarApostila") {

$cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$cpf = preg_replace('/[^0-9]/', '', $cpf);

$idHistorico = filter_input(INPUT_POST, 'idHistorico', FILTER_SANITIZE_NUMBER_INT);
$idHistorico = preg_replace('/[^0-9]/', '', $idHistorico);

//$idOp é o id da operação.
$idOp = filter_input(INPUT_POST, 'idOp', FILTER_SANITIZE_NUMBER_INT);
$idOp = preg_replace('/[^0-9]/', '', $idOp);

//$opcao é se o aluno optou por receber o material de estudo em casa, ou não.(1=sim, 0=não)
$opcao = filter_input(INPUT_POST, 'opcao', FILTER_SANITIZE_NUMBER_INT);
$opcao = preg_replace('/[^0-9]/', '', $opcao);

$liberarApostila = filter_input(INPUT_POST, 'liberarApostila', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$liberarApostila = html_entity_decode($liberarApostila, ENT_QUOTES, 'UTF-8');

$dtPagamento = date("Y-m-d");
$dtContrato = date("Y-m-d");
$dtIniEstudo = date("Y-m-d");
$situacao = "Cursando";

if($opcao==1){
    $tipoOp="E";//E de envio Desta forma não vai desaparecer da página inicial do administrador.
}else{
    $tipoOp="R";
}

    try {
        $pdo = new Config;
        $pdo->beginTransaction(); // Início da transação

        // Atualização na tabela financeira
        $sql = "UPDATE tb_op_financeira 
                SET dtPagamento = :dtPagamento, 
                    dtContrato = :dtContrato, 
                    tipoOp = :tipoOp
                WHERE idResp = :cpf  AND idOp= :idOp";
        $stmt = $pdo->prepare($sql);
        $params = [
            'cpf' => $cpf,
            'idOp' => $idOp,
            'dtPagamento' => $dtPagamento,
            'dtContrato' => $dtContrato,
            'tipoOp' => $tipoOp 
        ];


        if (!$stmt->execute($params)) {
            $_SESSION['message'] =  "Erro ao atualizar tb_op_financeira.";
            $_SESSION['message_type'] = 'alert-danger';  
        }

        // Atualização no histórico do aluno
        $sql = "UPDATE tb_historico_aluno 
                SET dtIniEstudo = :dtIniEstudo, 
                    situacao = :situacao
                WHERE idHistorico = :idHistorico";
        $stmt = $pdo->prepare($sql);
        $params = [
            'idHistorico' => $idHistorico,
            'dtIniEstudo' => $dtIniEstudo,
            'situacao' => $situacao,
        ];

        if (!$stmt->execute($params)) {
            $_SESSION['message'] =  "Erro ao atualizar tb_historico_aluno.";
            $_SESSION['message_type'] = 'alert-danger';  
        }

         //Apagando a referência no BD de tb_recibos e transferindo para tb_docalunos
        // Busca os dados
        $sqlSelect = "SELECT cpf, caminhoNomeArq, nomeArq FROM tb_recibos WHERE idOp = :idOp";
        $stmt = $pdo->prepare($sqlSelect);
        $stmt->execute(['idOp' => $idOp]);
        $linha = $stmt->fetch(PDO::FETCH_ASSOC);

        // Prepara a query de inserção
        if ($linha) {
            $pastaParaRemover='recibos';
            $novoCaminho = trocarCaminhoNome($linha['caminhoNomeArq'],$pastaParaRemover);

            $sqlInsert = "INSERT INTO tb_docalunos (idOp, cpf, caminhoNomeArq, nomeArq)
                        VALUES (:idOp, :cpf, :caminhoNomeArq, :nomeArq)";
            $stmtInsert = $pdo->prepare($sqlInsert);
            $stmtInsert->execute([
                'idOp' => $idOp,
                'cpf' => $linha['cpf'],
                'caminhoNomeArq' => $novoCaminho,
                'nomeArq' => $linha['nomeArq']
            ]);
        }

        $pdo->commit(); // Confirma a transação
        $_SESSION['message'] = "Material distribuído a partir desta data. Nenhuma pendência.";
        $_SESSION['message_type'] = 'alert-success';
        header('Location: cadastroListVisitantes.php');
        exit();
    } catch (Exception $e) {
        $pdo->rollback(); // Reverte mudanças em caso de erro
            $_SESSION['message'] =  "Falhou tudo.  A transação foi desfeita.";
            $_SESSION['message_type'] = 'alert-danger';  
        header('Location: cadastroListVisitantes.php');
        exit();
    }
}

//Enviar apostilas para os correios. xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx

if (isset($_GET['acao']) && $_GET['acao'] == "enviarCorreios") {
//     var_dump($_POST);
//   var_dump($_GET);
//   exit();

        $cpf = filter_input(INPUT_GET, 'cpf', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        $idHistorico = filter_input(INPUT_GET, 'idHistorico', FILTER_SANITIZE_NUMBER_INT);
        $idHistorico = preg_replace('/[^0-9]/', '', $idHistorico);

        //$idOp é o id da operação.
        $idOp = filter_input(INPUT_GET, 'idOp', FILTER_SANITIZE_NUMBER_INT);
        $idOp = preg_replace('/[^0-9]/', '', $idOp);

        //$opcao é se o aluno optou por receber o material de estudo em casa, ou não.(1=sim, 0=não)
        $opcao = filter_input(INPUT_GET, 'opcao', FILTER_SANITIZE_NUMBER_INT);
        $opcao = preg_replace('/[^0-9]/', '', $opcao);

        $dtPagamento = date("Y-m-d");
        $dtContrato = date("Y-m-d");
        $dtIniEstudo = date("Y-m-d");
        $situacao = "Cursando";

        $tipoOp="R";

    try {
        $pdo = new Config;
        $pdo->beginTransaction(); // Início da transação

        // Atualização na tabela financeira
        $sql = "UPDATE tb_op_financeira 
                SET dtPagamento = :dtPagamento, 
                    dtContrato = :dtContrato, 
                    tipoOp = :tipoOp
                WHERE idResp = :cpf  AND idOp= :idOp";
        $stmt = $pdo->prepare($sql);
        $params = [
            'cpf' => $cpf,
            'idOp' => $idOp,
            'dtPagamento' => $dtPagamento,
            'dtContrato' => $dtContrato,
            'tipoOp' => $tipoOp 
        ];


        if (!$stmt->execute($params)) {
            $_SESSION['message'] =  "Erro ao atualizar tb_op_financeira.";
            $_SESSION['message_type'] = 'alert-danger';  
        }

        // Atualização no histórico do aluno
        $sql = "UPDATE tb_historico_aluno 
                SET dtIniEstudo = :dtIniEstudo, 
                    situacao = :situacao
                WHERE idHistorico = :idHistorico";
        $stmt = $pdo->prepare($sql);
        $params = [
            'idHistorico' => $idHistorico,
            'dtIniEstudo' => $dtIniEstudo,
            'situacao' => $situacao,
        ];

        if (!$stmt->execute($params)) {
            $_SESSION['message'] =  "Erro ao atualizar tb_historico_aluno.";
            $_SESSION['message_type'] = 'alert-danger';  
        }

        $pdo->commit(); // Confirma a transação
        $_SESSION['message'] = "Material distribuído a partir desta data. Nenhuma pendência.";
        $_SESSION['message_type'] = 'alert-success';
        header('Location: cadastroListVisitantes.php');
        exit();
    } catch (Exception $e) {
        $pdo->rollback(); // Reverte mudanças em caso de erro
            $_SESSION['message'] =  "Falhou tudo.  A transação foi desfeita.";
            $_SESSION['message_type'] = 'alert-danger';  
        header('Location: cadastroListVisitantes.php');
        exit();
    }
}


// Função para tratar o caminho
function trocarCaminhoNome($caminho, $pastaParaRemover) {
    // Garante que as contrabarras não sejam interpretadas como escapes
    $caminho = str_replace('\\', '/', $caminho); // converte para barras normais
    $pastaParaRemover = str_replace('\\', '/', $pastaParaRemover); 

    // Remove a pasta desejada
    $novoCaminho = str_replace("/$pastaParaRemover", '', $caminho);

    // Converte de volta para contrabarra, se necessário
    return str_replace('/', '\\', $novoCaminho);
}


?>
