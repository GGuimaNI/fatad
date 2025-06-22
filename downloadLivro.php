<?php
 // Iniciar a sessão
session_start();

//******************************************************************************* */
//Este script faz o mesmo que downLoadLivro_baixar.php faz.  
//A diferença é que este abre em uma nova guia, e o o outro, baixa para a pasta download.
//Para trocar, basta copiar e colar o conteúdo de um no outro.
//Este é o original.
//******************************************************************************** */


// Limpar o buffer de saída
ob_start();

// Incluir a conexão com BD
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$caminhoNomeArq="";
 //$idDisciplina=$_GET['idDisciplina'];
 $idDowndocs=$_GET['idDowndocs'];


// $idNucleo=$_GET['idNucleo'];
// $idTurma=$_GET['idTurma'];
//$idCurso=$_GET['idCurso'];
//$qtdAlunos=$_GET['qtdAlunos'];

$caminho=$fg->findCaminhoNomeDisciplinaEspecifica($idDowndocs);
foreach($caminho as $row){
    $caminhoNomeArq =$row['caminhoNomeArq'];
    break;
}

// Caminho para o arquivo que você deseja baixar
//$file = 'caminho/para/o/seu/arquivo/nome_do_arquivo.ext';
$file = $caminhoNomeArq;
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
?>
