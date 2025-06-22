<?php
session_start();
include_once 'fatadgestaoControler.php';
$fg=new fatadgestaoControler;
require_once 'config.php'; // Arquivo de conexão com PDO

$cpf="";


if (isset($_POST['excluirDocAluno'])) {

//     var_dump($_POST);
// exit();

    // Obtenha o ID do documento do POST
    $idArquivo = filter_input(INPUT_POST, 'idArquivo', FILTER_SANITIZE_NUMBER_INT);
    $idAluno = filter_input(INPUT_POST, 'idAluno', FILTER_SANITIZE_NUMBER_INT);
    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $msg="";

    if ($idArquivo === false || $idArquivo === null) {
        $_SESSION['message'] = 'ID do documento inválido';
        header('Location: cadastroListDocAluno.php?cpf=' . $cpf);
        exit;
    }

    try {
        $pdo = new Config();

        //pegar o caminho e nome do arquivo para excluir fisicamente
        $sql = "Select caminhoNomeArq,nomeArq FROM tb_recibos WHERE idArquivo = :idArquivo";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idArquivo', $idArquivo, PDO::PARAM_INT);
        // Executa a consulta

        $stmt->execute();
        // Obtém o resultado
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {

            //excluir fisicamente o arquivo
            $file  = $result['caminhoNomeArq'];

            if (file_exists($file)) {

                if (unlink($file)) {
                    $msg = "Arquivo físico excluído!";
                } else {
                    $msg= "Erro ao excluir o arquivo físico. Verifique as permissões.";
                }
            } else {
                $msg= "Arquivo físico não encontrado.";
            }
        } else {
            $msg=  "Nenhum arquivo físico encontrado para o ID informado.";
        }

        // excluir no BD
        $sql = "DELETE FROM tb_recibos WHERE idArquivo = :idArquivo";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idArquivo', $idArquivo, PDO::PARAM_INT);

        // Executar a consulta
        if ($stmt->execute()) {
            $_SESSION['message'] = $msg.' Referência no BD excluída.';
            $_SESSION['message_type'] = 'success'; // Alerta de sucesso

        } else {
            $errorInfo = $stmt->errorInfo();
            $_SESSION['message'] = $msg.' FALHOU a exclusão no BD do arquivo: ' . $errorInfo[2].' no BD.';
            $_SESSION['message_type'] = 'danger';

        }

         // Redirecionamento

        header('Location: cadastroListDocAluno.php?cpf=' . $cpf);

    } catch (PDOException $e) {
        // Debug - Verifique o valor de $idAluno
        $_SESSION['message'] = 'Erro ao redirecionar para cadastroListDocAluno.php?, cpf=" . $cpf';
        $_SESSION['message_type'] = 'error';   
    }
    exit;
}

