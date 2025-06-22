




<?php
 // Iniciar a sessão
session_start();
//******************************************************************************* */
//Este script faz o mesmo que downLoad.php faz.  
//A diferença é que este baixa direto o arquivo, e o o outro, abre em uma nova guia.
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
 $idDowndocs=$_GET['idArquivo'];


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
// Verifica se o arquivo existe
if (file_exists($file)) {
    // Define os headers para o download
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($file) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
} else {
    $_SESSION['mensagem'] = "Arquivo não encontrado";
header("Location: matEscolarNucleos.php");
exit(); // É uma boa prática terminar o script após um redirecionamento

}
?>
