<?php
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
require './dompdf/vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;
$situacao="";
$idTurma = rtrim( $_GET['idTurma']); 
$idCurso = rtrim( $_GET['idCurso']);
$idNucleo = rtrim($_GET['idNucleo']);
$idDisciplina = rtrim($_GET['idDisciplina']);

$eBranco5="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$eBranco10=$eBranco5.$eBranco5;
$eBranco20=$eBranco10.$eBranco10;
$eBranco30=$eBranco20.$eBranco10;
$eBranco50=$eBranco30.$eBranco20;
$eBranco100=$eBranco50.$eBranco50;

$descNucleo=$fg->findDescNucleoEspecifico($idNucleo);
   foreach ($descNucleo as $row) {
      $nmNucleo=$row['descNucleo'];
      break;
   }
$disciplina=$fg->findDisciplinaEspecifica($idDisciplina);
   foreach ($disciplina as $row) {
      $nomeArq="Disciplina ".$row['codigoDisciplina']." (".$row['nivelCurso'].")";
      break;
   }
    // Obter os dados da função findBoletimNucleo
    $dados = $fg->findBoletimNucleo($idTurma, $idCurso, $idNucleo, $idDisciplina);

    // Se não houver dados, exibe uma mensagem de erro
    if (empty($dados)) {
        echo 'Nenhum registro encontrado.';
        return;
    }

    // Configuração do Dompdf
    $options = new Options();
    $options->set('defaultFont', 'sans');
    $dompdf = new Dompdf($options);

    // Início da criação do HTML
    $html = '
    <html>
    <head>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
                margin: 20px 0;
            }
            table, th, td {
                border: 1px solid black;
            }
            th, td {
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
        </style>
    </head>
    <body>';

    $html.= '<table style="border: none;">';
    $html.=  '<tr>';
    $html .= "<td><img src='http://localhost/fatad/imagens/LogoFATAD.jpeg' style='width: 60px; height: 60px;'></td>";

    $html.=  '<td>';
    $html.='<center>&nbsp;&nbsp;&nbsp;&nbsp;Centro Educacional Social Evangélico FATAD-ME</center>';
    $html.='<center>&nbsp;&nbsp;&nbsp;&nbsp;Rua 5 - (Pólo de Modas), Guará II, Brasilia - DF - CEP 71070-505.  Fone (61)3042-1213</center>';
    $html.='<center>&nbsp;&nbsp;&nbsp;&nbsp;CNPJ 26497883/0001-80</center>';
    $html.=  '</td>';
    $html.=  '</tr>';
    $html.= '</table>';


     $html .= '
        <h4><center>Boletim '. $nmNucleo.' - '.$nomeArq.'</center></h4>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome Aluno</th>                 
                    <th>Nota</th>
                    <th>Situação</th>
                    <th>Ciente'.$eBranco30.'</th>
                </tr>
            </thead>
            <tbody>';

    // Preencher a tabela com os dados
    foreach ($dados as $registro) {

        $html .= '
            <tr>
                <td>' . $registro->idHistorico . '</td>
                <td>' . $registro->nomeAluno . '</td>
                <td>' . $registro->nota . '</td>
                <td>' . $registro->situacao . '</td>
                <td></td>
            </tr>';
    }
    

    // Fechar a tabela e o HTML
    $html .= '
            </tbody>
        </table> 
       <h6> Legenda da coluna Situação, se for o caso:<br>
        Aprovado(*): Dependente de média geral.<br>
        Reprovado: Precisa ser submetido a nova prova.</h6>
    </body>
    </html>';

    // Carregar o HTML no Dompdf
    $dompdf = new Dompdf($options);
    $dompdf=new Dompdf(['enable_remote' => true]);
    $dompdf->loadHtml($html);

    // (Opcional) Definir o tamanho do papel e a orientação
    $dompdf->setPaper('A4', 'portrait');  // Use 'landscape'=Horizontal ou 'portrait' para orientação vertical

    // Renderizar o PDF
    $dompdf->render();

    // Enviar o PDF para o navegador
    $dompdf->stream('Boletim '. $nmNucleo.' - '.$nomeArq.'.pdf');

