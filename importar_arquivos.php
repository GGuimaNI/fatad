<?php
// Configurações do banco de dados
session_start();
require './config.php';
$pdo = new Config();

// Conexão com o banco de dados
try {
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}


// Defina a pasta de upload base (pode ser uma configuração do seu servidor) 
$uploadBaseDir = 'C:/ArquivosFatad/downdocs/';
// Verifica se os arquivos foram enviados
if (isset($_FILES['folder'])) {
    foreach ($_FILES['folder']['name'] as $key => $name) {
        // Verifica se é um arquivo
        if (is_file($_FILES['folder']['tmp_name'][$key])) {
            // Caminho completo do arquivo
            $relativePath = $_FILES['folder']['full_path'][$key];
            // Caminho absoluto do arquivo 
            $caminhoArq = realpath($uploadBaseDir . $relativePath);
            // Nome do arquivo
            $nomeArq = basename($name);
            // var_dump($uploadBaseDir);
            // var_dump($relativePath);
            // var_dump($caminhoArq);
            // exit();

            // Preparar e executar a inserção no banco de dados
            $idCurso = filter_input(INPUT_POST, 'idCurso', FILTER_SANITIZE_NUMBER_INT);
            $idDisciplina = filter_input(INPUT_POST, 'idDisciplina', FILTER_SANITIZE_NUMBER_INT);


            
            $stmt = $pdo->prepare("INSERT INTO tb_downdocs (idCurso, idDisciplina, caminhoNomeArq, nomeArq) VALUES (:idCurso, :idDisciplina, :caminhoArq, :nomeArq)");
            $stmt->bindParam(':idCurso', $idCurso); 
            $stmt->bindParam(':idDisciplina', $idDisciplina);
            $stmt->bindParam(':caminhoArq', $caminhoArq);
            $stmt->bindParam(':nomeArq', $nomeArq);
            $stmt->execute();
        }
    }
    $_SESSION['message'] = $contar. " arquivos importados com sucesso";
    $_SESSION['message_type'] = 'success'; // Alerta de sucesso
        header('Location: importar_arquivos_seleciona.php');
} else {
    $_SESSION['message'] = "Nenhuma pasta foi selecionada.";
    $_SESSION['message_type'] = 'danger'; // Alerta de erro
    header('Location: importar_arquivos_seleciona.php');
}
?>
