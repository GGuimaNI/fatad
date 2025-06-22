<?php
session_start();

require './config.php';

if (isset($_POST['excluir_documento'])) {
    // Obtenha o ID do documento do POST
    $idDocumento = filter_input(INPUT_POST, 'idDocumento', FILTER_SANITIZE_NUMBER_INT);

    if ($idDocumento === false || $idDocumento === null) {
        $_SESSION['message'] = 'ID do documento inválido';
        header('Location: importar_arquivos_listar.php');
        exit;
    }

    try {
        $pdo = new Config();

        // Preparar a consulta de exclusão
        $sql = "DELETE FROM tb_downdocs WHERE idDowndocs = :idDocumento";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idDocumento', $idDocumento, PDO::PARAM_INT);

        // Executar a consulta
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Arquivo excluído com sucesso';
            $_SESSION['message_type'] = 'success'; // Alerta de sucesso

        } else {
            $errorInfo = $stmt->errorInfo();
            $_SESSION['message'] = 'FALHOU a exclusão do arquivo: ' . $errorInfo[2];
            $_SESSION['message_type'] = 'danger'; // Alerta de fracasso

        }

        // Redirecionar após a operação
        header('Location: importar_arquivos_listar.php');
        exit;

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}
?>

    

 
    