<?php
session_start();
 include_once("config.php");
// var_dump($_POST);
// exit();

if (isset($_POST['valorEncadernacao']) || isset($_POST['valorFrete'])) {
    try {
        $pdo = new Config;
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Normaliza os valores (ou preserva null)
        $valorEncadernacao = isset($_POST['valorEncadernacao']) && $_POST['valorEncadernacao'] !== ''
            ? floatval(str_replace(',', '.', $_POST['valorEncadernacao']))
            : null;

        $valorFrete = isset($_POST['valorFrete']) && $_POST['valorFrete'] !== ''
            ? floatval(str_replace(',', '.', $_POST['valorFrete']))
            : null;

        // Monta a SQL condicionalmente
        if (isset($valorEncadernacao) && isset($valorFrete)) {
            $sql = "UPDATE tb_matricula SET valorEncadernacao = :valorEncadernacao, frete = :frete WHERE opcao = 1";
        } elseif (isset($valorEncadernacao)) {
            $sql = "UPDATE tb_matricula SET valorEncadernacao = :valorEncadernacao WHERE opcao = 1";
        } elseif (isset($valorFrete)) {
            $sql = "UPDATE tb_matricula SET frete = :frete WHERE opcao = 1";
        } else {
            $_SESSION['mensagem'] = 'Nenhum valor foi informado.';
            header('Location: iniciar.php');
            exit;
        }

        // Monta os parâmetros da query
        $params = [];
        if (isset($valorEncadernacao)) $params[':valorEncadernacao'] = $valorEncadernacao;
        if (isset($valorFrete)) $params[':frete'] = $valorFrete;

        // Executa a query
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $_SESSION['mensagem'] = $stmt->rowCount() > 0
            ? 'Valores atualizados com sucesso.'
            : 'Nenhuma linha foi alterada.';

        header('Location: iniciar.php');
        exit;

    } catch (Exception $e) {
        $_SESSION['mensagem'] = 'Erro ao atualizar: ' . $e->getMessage();
        header('Location: iniciar.php');
        exit;
    }
}
?>