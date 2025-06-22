<?php


include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;

$nomeAluno="";
$idAluno=$_POST['idAluno'];
$dtNasc="";
$dtIniEstudo="";
$dtTerEstudo="";


$creditoDisciplina=0;
$cargaHoraria=0;
$eBranco5="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$eBranco10=$eBranco5.$eBranco5;
$eBranco20=$eBranco10.$eBranco10;
$eBranco30=$eBranco20.$eBranco10;
$eBranco50=$eBranco30.$eBranco20;
$eBranco100=$eBranco50.$eBranco50;

$res=$fg->findFichaMatricula($idAluno);
    $html = "<!DOCTYPE html>";
    $html .= "<html lang='pt-br'>";

    $html .= "<head>";
    $html .= "<meta charset='UTF-8'>";
    $html .= "<link rel='stylesheet' href='./css/custom.css'";
    $html .= "<title>Ficha de Inscrição</title>";
    
   
    
    $html.= "<table style='width: 100%; text-align: center;'>";
    $html.=  "<tr>";
    $html.=  "<td style='width: 60px;'><img src='http://localhost/fatad/imagens/LogoFATAD.jpeg' style='width: 65px; height: 65px;'></td>";
    $html.=  "<td style='vertical-align: middle; text-align: center;'>";
    $html.=  "Centro Educacional Social Evangélico <br> FATAD-ME <br> CNPJ 26497883/0001-80";
    $html.=  "</td>";
    $html.=  "</tr>";
    $html.= "</table>";
    $html.="<center>$eBranco10<em>FORMULÁRIO DE INSCRIÇÃO - CURSO MODULAR</em></center><br>";
    //Cabeçalho do pdf
    
    $html .= "</head>";
    
    $html .= "<body>";
  

     if ($res){
        if($res->dtNascAluno){ $dtNasc=date('d/m/Y', strtotime($res->dtNascAluno)); }else{ $dtNasc=""; }
        
        $timestamp = strtotime($res->dtIniEstudo ?? '');
        if ($timestamp && $timestamp > 0) {
            $dtIniEstudo = date('d/m/Y', $timestamp);
        } else {
            $dtIniEstudo = "";
        }

        $timestamp = strtotime($res->dtTerEstudo ?? '');
        if ($timestamp && $timestamp > 0) {
            $dtTerEstudo = date('d/m/Y', $timestamp);
        } else {
            $dtTerEstudo = "";
        }
       // if($res->dtTerEstudo){ $dtTerEstudo=date('d/m/Y', strtotime($res->dtTerEstudo)); }else{ $dtTerEstudo=""; }
        
        $html.=  "<strong>I. INFORMAÇÕES PESSOAIS</strong><br>";

        $html.=  "<strong>Nome:</strong> $eBranco10 &nbsp;&nbsp;&nbsp;&nbsp; $res->nomeAluno <br>";
        $html.=  "<strong>Naturalidade:</strong> &nbsp;&nbsp;&nbsp; $res->cidadeNatAluno <br>";
        $html.=  "<strong>Data Nasc:</strong>   $eBranco5 &nbsp;&nbsp;&nbsp;$dtNasc <br>";
        $html.=  "<strong>Estado Civil:</strong> &nbsp;&nbsp;&nbsp;&nbsp; $res->estadoCivil <br>";
        $html.=  "<strong>Nome Cônjuge:</strong>  &nbsp;$res->nomeConjuge <br>";
        $html.=  "<strong>Filiação:</strong>  $eBranco10 &nbsp;&nbsp;$res->nomePaiAluno e $res->nomeMaeAluno <br>";
        $html.=  "<strong>Endereço:</strong>  $eBranco5 &nbsp;&nbsp;&nbsp;$res->enderecoAluno<br>";
        $html.=  " $eBranco20 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; CEP: $res->cep - $res->cidadeMoradia <br>";

        $html.=  "<strong>Telefone:</strong> $eBranco10 $res->telZapAluno <br>";
        $html.=  "<strong>E-mail:</strong> &nbsp;&nbsp;&nbsp;$eBranco10 $res->emailAluno <br><br>";

        $html.=  "<strong>II. DOCUMENTOS DE IDENTIFICAÇÃO</strong><br>";
        $html.=  "<strong>Identidade:</strong> $eBranco5 $res->idtAluno <br>";
        $cpfFormatado=$fg->formatCnpjCpf($res->cpfAluno);
        $html.=  "<strong>CPF:</strong>  $eBranco10 $eBranco5 $cpfFormatado <br><br><br>";

        

        $html.=  "<strong>III. ESCOLARIDADE</strong> <br>";
        $html.=  "<strong>Nível:</strong> $eBranco10 &nbsp;&nbsp;&nbsp;&nbsp;$res->escolaridade <br>";
        $html.=  "<strong>Instituição:</strong>   $eBranco5 $res->instEnsino <br>";
        $html.=  "<strong>Início:</strong>    $eBranco10 &nbsp;&nbsp;&nbsp;$dtIniEstudo <br>";
        $html.=  "<strong>Conclusão:</strong>   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; $dtTerEstudo <br><br><br>";

        $html.=  "<strong>IV. EXPERIÊNCIA CRISTÃ</strong> <br>";
        $html.=  "<strong>Igreja:</strong> $eBranco10 &nbsp;&nbsp;&nbsp;&nbsp;$res->instIgreja <br>";
        $html.=  "<strong>Endereço:</strong>   $eBranco5 &nbsp;&nbsp; $res->endIgreja <br>";
        $html.=  "<strong>Nome Pastor:</strong>    &nbsp;&nbsp; $res->nomePastor<br>";
        $html.=  "<strong>Cargo/Função:</strong>     $res->cargoFuncao <br><br>";
       
        $html.=  "<strong>V. OBSERVAÇÕES JULGADAS NECESSÁRIAS</strong> <br>";
        $html.=  " $eBranco5 $res->obs <br><br>";
        
        $html.=  "<center> $res->cidadeMoradia, ____ de ____________ de _____</center><br><br><br>";

        $html.=  "<center>_____________________________________</center> <br>";

        $html.=  "<center> $res->nomeAluno </center> <br>";
        $html.=  "<center>(CPF $res->cpfAluno)</center>";


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
    "FInscrição-".$res->nomeAluno.".pdf",  
    array(
	"Attachment" => true //Para realizar o download somente alterar para true
        )
);


$html .= "</body>";
?>