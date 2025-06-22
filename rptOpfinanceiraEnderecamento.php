<?php
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;

$perfil = $_POST['perfil'];
$idNucleo = $_POST['idNucleo'];
$id=$idNucleo;
   if($perfil=='Núcleo'){
      $destinatario=$fg->findTudoDeNucleoEspecifico($id);
   }else{
      $destinatario=$fg->findTudoDeAlunoEspecifico($id);
   }

$nomeResponsavel=""; 

if($perfil=='Núcleo'){
    if ($destinatario){
        foreach ($destinatario as $rdest) {
          $nomeDest=$rdest->descNucleo;
          $nomeDest=$rdest->nomeRespNucleo;
          $enderecoDest=$rdest->enderecoNucleo;
          $cidadeDest=$rdest->cidadeUF;
          $cepDest=$rdest->cep;
          $nomeResponsavel=$nomeDest;}
    }
}else{
    if ($destinatario){
        foreach ($destinatario as $rdest) {
        $nomeDest=$rdest->nomeAluno;
        $enderecoDest=$rdest->enderecoAluno;
        $cidadeDest=$rdest->cidadeMoradia;
        $cepDest=$rdest->cep;
        $nomeResponsavel=$nomeDest;}
    }
}
    
$html = "<!DOCTYPE html> ";
$html .="<html>";
$html .="<head>";
$html .= "<meta charset='UTF-8'>";
$html .= "<html lang='pt-br'>";
$html .="<style>";
$html .="body {";
$html .="font-size: 25px;"; /* Define o tamanho da fonte */
$html .="        }";
$html .="        h1 {";
$html .="            font-size: 35px;"; /* Tamanho da fonte maior para o título */
$html .="        }";
$html .= "<link rel='stylesheet' href='./css/custom.css'";
$html .="    </style>";
$html .= "<title>Endereçamento</title>";
$html .= "</head>";
    
    
$html.= "<table>";
$html.=  "<tr>";
$html.=  "<td>DESTINATÁRIO:</td>";
$html.=  "<td>&nbsp;$nomeDest</td>";
$html.=  "</tr>";
$html.=  "<td>&nbsp;</td>";
$html.=  "<td>&nbsp;$enderecoDest</td>";
$html.=  "</tr>";
$html.=  "<tr>";
$html.="<td>  </td>";
$html.="<td>&nbsp;CEP: ".$cepDest." - ".$cidadeDest."</td>";   
$html.=  "</td>";
$html.=  "</tr>";
$html.= "</table><br><br>";
$html.= "<center>(Fechamento autorizado.  Pode ser aberto pelos Correios)</center><br><br><br><br>";

$html.= "<table>";
$html.=  "<tr>";
$html.=  "<td>REMETENTE:</td>";
$html.=  "<td>&nbsp;Centro Educacional Social Evangélico FATAD-ME</td>";
$html.=  "</tr>";
$html.=  "<td>&nbsp;</td>";
$html.=  "<td>&nbsp;Rua 5 Lote 12 (Pólo de Modas), Guará II</td>";
$html.=  "</tr>";
$html.=  "<tr>";
$html.="<td>  </td>";
$html.="<td>&nbsp;CEP: &nbsp;CEP 71070-505  -  BRASÍLIA-DF</td>";   
$html.=  "</td>";
$html.=  "</tr>";
$html.= "</table><br><br><br>";

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
$dompdf->stream("End ".$nomeResponsavel.".pdf",  
    array("Attachment" => true)); //Para realizar o download somente alterar para true
        




