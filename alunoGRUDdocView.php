<?php
session_start();
// Incluir a conexão com o banco de dados
include_once './config.php';
$pdo = new Config();

// Receber o id do registro
$idArquivo = filter_input(INPUT_GET, 'idArquivo', FILTER_SANITIZE_NUMBER_INT);

if ($idArquivo === false || $idArquivo === null) {
    echo "<p style='color: #f00;'>Erro: ID do documento inválido!</p>";
    exit;
}

// Query para recuperar o caminho do arquivo no banco de dados
$query_arquivo = "SELECT caminhoNomeArq FROM tb_docalunos WHERE idArquivo = :idArquivo";
// Preparar a QUERY
$result_arquivo = $pdo->prepare($query_arquivo);
// Substituir o link da QUERY pelo valor
$result_arquivo->bindParam(':idArquivo', $idArquivo, PDO::PARAM_INT);
// Executar a QUERY com PDO
$result_arquivo->execute();

// Verificar se encontrou algum registro no banco de dados com PDO
if ($result_arquivo && $result_arquivo->rowCount() != 0) {
    // Ler o registro retornado do banco de dados com PDO
    $row_arquivo = $result_arquivo->fetch(PDO::FETCH_ASSOC);
    // Extrair o caminho do arquivo do array de dados
    $caminhoNomeArq = $row_arquivo['caminhoNomeArq'];
    // Verificar se o arquivo existe no caminho especificado
    if (file_exists($caminhoNomeArq)) {
        // Obter a extensão do arquivo
        $fileInfo = pathinfo($caminhoNomeArq);
        $fileExtension = strtolower($fileInfo['extension']);

        // Definir o cabeçalho apropriado com base no tipo de arquivo
        switch ($fileExtension) {
            case 'pdf':
                header("Content-Type: application/pdf");
                header("Content-Disposition: inline; filename=\"" . basename($caminhoNomeArq) . "\"");
                break;
            case 'jpeg':
            case 'jpg':
                header("Content-Type: image/jpeg");
                header("Content-Disposition: inline; filename=\"" . basename($caminhoNomeArq) . "\"");
                break;
            case 'bmp':
                header("Content-Type: image/bmp");
                header("Content-Disposition: inline; filename=\"" . basename($caminhoNomeArq) . "\"");
                break;
            case 'doc':
            case 'docx':
                header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
                header("Content-Disposition: inline; filename=\"" . basename($caminhoNomeArq) . "\"");
                break;
            case 'ppt':
            case 'pptx':
                header("Content-Type: application/vnd.openxmlformats-officedocument.presentationml.presentation");
                header("Content-Disposition: inline; filename=\"" . basename($caminhoNomeArq) . "\"");
                break;
            case 'csv':
                header("Content-Type: text/csv");
                header("Content-Disposition: inline; filename=\"" . basename($caminhoNomeArq) . "\"");
                break;
            default:
                echo "<p style='color: #f00;'>Erro: Tipo de arquivo não suportado!</p>";
                exit;
        }
        // Ler o arquivo e enviar para o navegador
        readfile($caminhoNomeArq);
    } else {
        echo "<p style='color: #f00;'>Erro: Arquivo não encontrado no servidor!</p>";
    }
} else {
    // Acessa o ELSE quando não encontrar o registro no banco de dados
    echo "<p style='color: #f00;'>Erro: Nenhum arquivo encontrado!</p>";
}
?>
