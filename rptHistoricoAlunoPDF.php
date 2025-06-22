<?php


include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;

$nomeAluno="";
$idAluno=$_POST['idAluno'];
$idTurma=$_POST['idTurma'];

//var_dump($_POST);
//var_dump($_GET);

$creditoDisciplina=0;
$cargaHoraria=0;
$eBranco5="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$eBranco10=$eBranco5.$eBranco5;
$eBranco20=$eBranco10.$eBranco10;
$eBranco30=$eBranco20.$eBranco10;
$eBranco50=$eBranco30.$eBranco20;
$eBranco100=$eBranco50.$eBranco50;

$res=$fg->findFichaHistorico($idAluno, $idTurma);
$rsc=$fg->findCabecalhoFichaHistorico($idAluno, $idTurma);
 
    $html = "<!DOCTYPE html>";
    $html .= "<html lang='pt-br'>";

    $html .= "<head>";
    $html .= "<meta charset='UTF-8'>";
    $html .= "<link rel='stylesheet' href='./css/custom.css'";
    $html .= "<title>FATAD - Histórico</title>";
    
   
    
    $html.= "<table>";
    $html.=  "<tr>";
    $html.=  "<td><img src='http://localhost/fatad/imagens/LogoFATAD.jpeg'"
            . " style='width: 60px; height: 60px;'></td>";
    $html.=  "<td>";
    $html.="<center>&nbsp;&nbsp;&nbsp;&nbsp;Centro Educacional Social Evangélico FATAD-ME</center>";
    $html.="<center>&nbsp;&nbsp;&nbsp;&nbsp;Rua 5 - (Pólo de Modas), Guará II, Brasilia - DF - CEP 71070-505.  Fone (61)3042-1213</center>";
    $html.="<center>&nbsp;&nbsp;&nbsp;&nbsp;CNPJ 26497883/0001-80</center>";
    $html.=  "<td>";
    $html.=  "</tr>";
    $html.= "</table>";
    $html.="<center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>HISTÓRICO ESCOLAR</em></center>";
    //Cabeçalho do pdf
    
    $html .= "</head>";
    
    $html .= "<body>";
    if ($rsc){
       foreach ($rsc as $rowc) {
        if($rowc->dtNascAluno){ $dtNasc=date('d/m/Y', strtotime($rowc->dtNascAluno)); }else{ $dtNasc=""; }
        if($rowc->dtIniCurso){ $dtIni=date('d/m/Y', strtotime($rowc->dtIniCurso)); }else{ $dtIni=""; }
        if($rowc->dtTermCurso){ $dtTer=date('d/m/Y', strtotime($rowc->dtTermCurso)); }else{ $dtTer=""; }
        $nomeAluno=$rowc->nomeAluno;
        $html.= "<table>";
        $html.= "<tr>";
        $html.= "<td><em>Curso:</em></td>";
        $html.= "<td>&nbsp;$rowc->nomeCurso</td>";
        $html.= "<td></td>";
        $html.= "/<tr>";
        $html.= "<tr>";
        $html.= "<td><em>Aluno:</em></td>";
        $html.= "<td>&nbsp;".$rowc->nomeAluno."</td>";
        $html.= "<td>Matrícula:&nbsp;".$rowc->idMatricula."</td>";
        $html.= "/<tr>";
        $html.= "<tr>";
        $html.= "<td><em>Filiação:</em>&nbsp;&nbsp;".$eBranco5."</td>";
        $html.= "<td>&nbsp;".$rowc->nomePaiAluno." e ".$rowc->nomeMaeAluno."</td>";
        $html.= "<td></td>";
        $html.= "/<tr>";
        $html.= "</table>";
        
        $html.= "<table>";
        $html.= "<tr>";
        $html.= "<td><em>Naturalidade:</em>&nbsp;".$rowc->cidadeNatAluno."</td>";
        $html.= "<td></td>";
        $html.= "<td><em>Data de Nascimento:</em>&nbsp;".$dtNasc."</td>";
        $html.= "/<tr>";
        $html.= "<tr>";
        $html.= "<td><em>Data de Início do Curso:</em>&nbsp;".$dtIni."</td>";
        $html.= "<td>$eBranco50</td>";
        $html.= "<td><em>Data Concluão do Curso:&nbsp;".$dtTer."</td>";
        $html.= "</tr>";
        $html.= "</table>";

        $html .= "<br>";
        break;
       }
    }

    
    //Linha de título da tabela
    $html.= "<table border=1 style='border-collapse: collapse'>";
    $html.= "<thead>";
    $html.=  "<tr>";
    $html.= "<th scope='col'>&nbsp;&nbsp;&nbsp;COD&nbsp;&nbsp;&nbsp;</th>";
    $html.= " <th scope='col'>".$eBranco5.$eBranco30."Disciplina".$eBranco30.$eBranco5."</th>";
    $html.= " <th scope='col'>Período</th>";
//    $html.= " <th scope='col'>Término</th>";
    $html.= " <th scope='col'>&nbsp;&nbsp;Créditos&nbsp;&nbsp;</th>";
    $html.= " <th scope='col'>&nbsp;&nbsp;&nbsp;Horas&nbsp;&nbsp;&nbsp;</th>";
    $html.= " <th scope='col'>&nbsp;&nbsp;Nota&nbsp;&nbsp;</th>";
    $html.= "</tr>";
    $html.= "</thead>";
    
    $html.= "<tbody>"; 
    //Preenchimento da tabela
    $acumulaNota=0;
    $contaNota=0;
    $media=0;
    if ($res){
       foreach ($res as $row) {
            $creditoDisciplina=$creditoDisciplina+$row->creditoDisciplina;
            $cargaHoraria=$cargaHoraria+$row->cargaHoraria;
            
          
            $html.= "<tr>";
            $html.= "<td>" . $row->codigoDisciplina . "</td>";
            $html.= "<td>" . $row->nomeDisciplina . "</td>";
            $html.= "<td>" . $row->dtIniEstudo . "</td>";
//            $html.= "<td>" . $row->dtTerEstudo . "</td>";
            $html.= "<td><center>" . $row->creditoDisciplina . "</center></td>";
            $html.= "<td><center>" . $row->cargaHoraria . "</center></td>";
            $html.= "<td style='text-align: right'>" . $row->nota . "&nbsp;</td>";
//            $html.= "<td>" . $row->situacao . "</td>";
            $html.= "</tr>";  
//            
            $acumulaNota=$acumulaNota+$row->nota;
            if($row->nota>0){$contaNota=$contaNota+1;}
    }
    if ($contaNota>0 and $acumulaNota>0){
        $media=number_format($acumulaNota/$contaNota, 2, '.', ',');
//        $media=number_format($media, 2, '.', ',');
    }else{
       $media=""; 
    }
    $html.= "<tr>";
    $html.= "<td></td>";
    $html.= "<td></td>";
    $html.= "<td>Total/Média</td>";
//            $html.= "<td>" . $row->dtTerEstudo . "</td>";
    $html.= "<td><center>" . $creditoDisciplina . "</center></td>";
    $html.= "<td><center>" . $cargaHoraria . "</center></td>";
    $html.= "<td style='text-align: right'>" . $media . "&nbsp;</td>";
//            $html.= "<td>" . $row->situacao . "</td>";
    $html.= "</tr>";  
    
    
    $html.= "</tbody>"; 
    $html.= "</table>";
    
//    $html.= "Total de Créditos das Disciplinas:  ".$creditoDisciplina.$eBranco50.$eBranco5
//            ."Carga Horária do Curso:  ".$cargaHoraria."hs";

    
}else{
    $html = "Nenhum dado recuperado";
}

//configurações básicas do pdf


require './dompdf/vendor/autoload.php';
use Dompdf\Dompdf;
use Dompdf\Options;

// Criação da instância do Dompdf
$options = new Options();
$options->set('defaultFont', 'sans'); // Configura a fonte padrão como 'sans'

$dompdf = new Dompdf($options);
//require_once 'dompdf/autoload.inc.php';
$dompdf=new Dompdf(['enable_remote' => true]);
$dompdf->loadHtml($html);

$dompdf->setPaper('A4','portrait');
$dompdf->render();
//$dompdf->stream();
$dompdf->stream(
    "Hist ".$nomeAluno.".pdf",  
    array(
	"Attachment" => true //Para realizar o download somente alterar para true
        )
);


$html .= "</body>";
?>