<?php


include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;

 $idOp = $_GET['idOp'];
 $perfil = $_GET['perfil'];

//var_dump($_POST);
//var_dump($_GET);

$creditoDisciplina=0;
$cargaHoraria=0;
$eBranco5="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$eBranco10=$eBranco5.$eBranco5;
$eBranco20=$eBranco10.$eBranco10;
$eBranco30=$eBranco20.$eBranco10;
$eBranco50=$eBranco30.$eBranco20;


   $rsc=$fg->findOpFinanceiraReciboEspecifico($idOp,$perfil);
   if($perfil=='Núcleo'){
      $funcaoResp="coordenador do curso ";
   }else{
      $funcaoResp="aluno do curso ";
   }

// var_dump($rsc);
// exit();
   
   
$nomeResponsavel=""; 
if ($rsc){
       foreach ($rsc as $rowc) {
        $descOp=$rowc->descOp;
        $dtContrato=$rowc->dtContrato;
        $dtPagamento=$rowc->dtPagamento;
        $qtdMat=$rowc->qtdMat;
        $valorUnitario=$rowc->valorUnitario;
        $valorEncadernacao=$rowc->valorEncadernacao;
        $frete=$rowc->frete;
        $valorTotal=$rowc->valorTotal;
        $nomeResponsavel=$rowc->nomeResponsavel;
        $descNucleo=$rowc->descNucleo;
        $nomeCurso=$rowc->nomeCurso;
        $apostila="";
        if($qtdMat>1){$apostila=" exemplares";}else{$apostila="exemplar";}
        $ext=$fg->numberToText($valorTotal);
        $dtPag=$fg->data_extenso($dtPagamento,false);
        $dtAtual=$fg->data_extenso($dtContrato);
    $html = "<!DOCTYPE html>";
    $html .= "<html lang='pt-br'>";

    $html .= "<head>";
    $html .= "<meta charset='UTF-8'>";
    $html .= "<link rel='stylesheet' href='./css/custom.css'";
    $html .= "<title>FATAD - RECIBO</title>";
    
//    $html .= "<style> p{ text-align: justify; } </style>";
    
    $html.= "<table>";
    $html.=  "<tr>";
    $html.=  "<td><img src='http://localhost/fatad/imagens/LogoFATAD.jpeg'"
            . " style='width: 60px; heigth: 60px;'></td>";
    $html.=  "<td>";
    $html.="<center>&nbsp;&nbsp;&nbsp;&nbsp;Centro Educacional Social Evangélico FATAD-ME</center>";
    $html.="<center>&nbsp;&nbsp;&nbsp;&nbsp;Rua 5 - (Pólo de Modas), Guará II, Brasilia - DF - CEP 71070505.  Fone (61)3042-1213</center>";
    $html.="<center>&nbsp;&nbsp;&nbsp;&nbsp;CNPJ 26497883/0001-80</center>";
    $html.=  "<td>";
    $html.=  "</tr>";
    $html.= "</table>";
    $html.=  "<br><br>";
    $html.="<center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>R E C I B O</em></center>";
    $html.=  "<br>";
    //Cabeçalho do pdf
    
        
        $html.=  $eBranco10."   RECEBI, em ".$dtPag.", de ".$nomeResponsavel.", ".$funcaoResp.$nomeCurso
                . ", em funcionamento no ".$descNucleo.", a importância de R$".$valorTotal." (".$ext.") "." conforme especificado abaixo:<br><br>";
        
        $html.= "<table>";
        $html.=  "<tr>";
        $html.=  "<td>Descrição Material:&nbsp;&nbsp;</td>";
        $html.=  "<td>$descOp</td>";
        $html.=  "</tr>";
        $html.=  "<tr>";
        $html.=  "<td>Quantidade:</td>";
        $html.=  "<td>$qtdMat</td>";
        $html.=  "</tr>";
        $html.=  "<tr>";
        $html.=  "<td>Valor unitário:$eBranco10</td>";
        $html.=  "<td>$valorUnitario</td>";
        $html.=  "</tr>";
        $html.=  "<tr>";
        $html.=  "<td>Encadernação:</td>";
        $html.=  "<td>$valorEncadernacao</td>";
        $html.=  "</tr>";
        $html.=  "<tr>";
        $html.=  "<td>Frete:</td>";
        $html.=  "<td>$frete</td>";
        $html.=  "</tr>";
        $html.=  "<tr>";
        $html.=  "<td>Total:</td>";
        $html.=  "<td>$valorTotal</td>";
        $html.=  "</tr>";
        $html.= "</table>";
        $html.=  "<br>";
        $html.=  "<center>Brasília-DF, em ".$dtAtual."</center>";
        $html.=  "<br><br>";
        $html.=  "<center>____________________________</center>";
        $html.=  "<center>Tesouraria FATAD</center>";
        $html.= "<br><br>";
        
         $html.=  "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - "
                 . "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - "
                 . "- - - - - - - - - - - - - ";
        $html.= "<br><br>"; 
         
           $html.= "<table>";
    $html.=  "<tr>";
    $html.=  "<td><img src='http://localhost/fatad/imagens/LogoFATAD.jpeg'"
            . " style='width: 60px; heigth: 60px;'></td>";
    $html.=  "<td>";
    $html.="<center>&nbsp;&nbsp;&nbsp;&nbsp;Centro Educacional Social Evangélico FATAD-ME</center>";
    $html.="<center>&nbsp;&nbsp;&nbsp;&nbsp;Rua 5 - (Pólo de Modas), Guará II, Brasilia - DF - CEP 71070505.  Fone (61)3042-1213</center>";
    $html.="<center>&nbsp;&nbsp;&nbsp;&nbsp;CNPJ 26497883/0001-80</center>";
    $html.=  "<td>";
    $html.=  "</tr>";
    $html.= "</table>";
    $html.=  "<br><br>";
    $html.="<center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>R E C I B O</em></center>";
    $html.=  "<br>";
    //Cabeçalho do pdf
    
        
         $html.=  $eBranco10."   RECEBI, em ".$dtPag.", de ".$nomeResponsavel.", ".$funcaoResp.$nomeCurso
                . ", em funcionamento no ".$descNucleo.", a importância de R$".$valorTotal." (".$ext.") "." conforme especificado abaixo:<br><br>";
        
        $html.= "<table>";
        $html.=  "<tr>";
        $html.=  "<td>Descrição Material:&nbsp;&nbsp;</td>";
        $html.=  "<td>$descOp</td>";
        $html.=  "</tr>";
        $html.=  "<tr>";
        $html.=  "<td>Quantidade:</td>";
        $html.=  "<td>$qtdMat</td>";
        $html.=  "</tr>";
        $html.=  "<tr>";
        $html.=  "<td>Valor unitário:$eBranco10</td>";
        $html.=  "<td>$valorUnitario</td>";
        $html.=  "</tr>";
        $html.=  "<tr>";
        $html.=  "<td>Encadernação:</td>";
        $html.=  "<td>$valorEncadernacao</td>";
        $html.=  "</tr>";
        $html.=  "<tr>";
        $html.=  "<td>Frete:</td>";
        $html.=  "<td>$frete</td>";
        $html.=  "</tr>";
        $html.=  "<tr>";
        $html.=  "<td>Total:</td>";
        $html.=  "<td>$valorTotal</td>";
        $html.=  "</tr>";
        $html.= "</table>";
        $html.=  "<br>";
        $html.=  "<center>Brasília-DF, em ".$dtAtual."</center>";
        $html.=  "<br><br>";
        $html.=  "<center>____________________________</center>";
        $html.=  "<center>Tesouraria FATAD</center>";
        $html.= "<br><br>";
        
       }
    }

//configurações básicas do pdf
require './dompdf/vendor/autoload.php';
use Dompdf\Dompdf;
//require_once 'dompdf/autoload.inc.php';
$dompdf=new Dompdf(['enable_remote' => true]);
$dompdf->loadHtml($html);
$dompdf->set_option('defaltFont', 'sans');
$dompdf->setPaper('A4','portrait');
$dompdf->render();
//$dompdf->stream();
$dompdf->stream(
    "Recibo ".$nomeResponsavel.".pdf",  
    array(
	"Attachment" => true //Para realizar o download somente alterar para true
        )
);

?>

