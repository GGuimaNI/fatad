<?php

include_once './fatadgestaoModel.class.php';

//if (isset($_REQUEST['action'])) {
//    $action = $_REQUEST['action'];
////var_dump($_SESSION);
//    if ($action == 'logarFatadGestao') {
//        $usuario = new fatadgestaoControler();
//        $usuario->loginUsuario();
//    }
//    
//    if ($action == 'Cadastrar') {
//        $usuario = new fatadgestaoControler();
//        $usuario->insertUsuario();
//    }
//    
//    
//    
//    
//    
//}
 

/**
 * Description of fatadgestaoControler
 *
 * @author Usuário
 */
 class fatadgestaoControler {

function criarDateTimeOuNull($valor) {
    return !empty($valor) ? new DateTime($valor) : null;
}

 function debugLog($mensagem, $arquivo = 'logs/debug.log') {
    $data = date('[Y-m-d H:i:s]');
    $linha = $data . ' ' . $mensagem . PHP_EOL;

    // Cria diretório se não existir
    $pasta = dirname($arquivo);
    if (!file_exists($pasta)) {
        mkdir($pasta, 0755, true);
    }

    file_put_contents($arquivo, $linha, FILE_APPEND);
}
    

function normalizarValor($entrada) {
    $numero = filter_var($entrada, FILTER_VALIDATE_FLOAT);
    return ($numero !== false && $numero > 0) ? $numero : 0;
}
function getAlunoId() {
    // Verifica se o valor foi enviado por POST
    $idAluno = filter_input(INPUT_POST, 'idAluno', FILTER_SANITIZE_NUMBER_INT);

    // Se não foi enviado por POST, tenta capturar via GET
    if ($idAluno === null || $idAluno === false) {
        $idAluno = filter_input(INPUT_GET, 'idAluno', FILTER_SANITIZE_NUMBER_INT);
    }

    return $idAluno;
}
function getCpfId() {
    // Verifica se o valor foi enviado por POST
    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_SPECIAL_CHARS);

    // Se não foi enviado por POST, tenta capturar via GET
    if ($cpf === null || $cpf === false) {
        $cpf = filter_input(INPUT_GET, 'cpf', FILTER_SANITIZE_SPECIAL_CHARS);
    }
    if ($cpf !== null) {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
    }
    return $cpf;
}

function getIdTurma() {
    // Verifica se o valor foi enviado por POST
    $idTurma = filter_input(INPUT_POST, 'idTurma', FILTER_SANITIZE_NUMBER_INT);

    // Se não foi enviado por POST, tenta capturar via GET
    if ($idTurma === null || $idTurma === false) {
        $idTurma = filter_input(INPUT_GET, 'idTurma', FILTER_SANITIZE_NUMBER_INT);
    }
    if ($idTurma !== null) {
        $idTurma = preg_replace('/[^0-9]/', '', $idTurma);
    }
    return $idTurma;
}
function getNucleoId() {
    // Verifica se o valor foi enviado por POST
    $idNucleo = filter_input(INPUT_POST, 'idNucleo', FILTER_SANITIZE_NUMBER_INT);

    // Se não foi enviado por POST, tenta capturar via GET
    if ($idNucleo === null || $idNucleo === false) {
        $idNucleo = filter_input(INPUT_GET, 'idNucleo', FILTER_SANITIZE_NUMBER_INT);
    }

    return $idNucleo;
}
function deleteFolderIterative($folder) {
    if (!is_dir($folder)) {
        echo "Pasta não encontrada.";
        return;
    }

    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($folder, RecursiveDirectoryIterator::SKIP_DOTS),
        RecursiveIteratorIterator::CHILD_FIRST
    );

    foreach ($iterator as $file) {
        if ($file->isDir()) {
            rmdir($file->getRealPath());
        } else {
            unlink($file->getRealPath());
        }
    }

    rmdir($folder);
    echo "Pasta excluída com sucesso: $folder";
}
    
function dia_semana_extenso($data){
    $dias_semana = array(
        'Domingo',
        'Segunda-feira',
        'Terça-feira',
        'Quarta-feira',
        'Quinta-feira',
        'Sexta-feira',
        'Sábado'
    );
    
    $data = date('Y-m-d');
    $dia_semana = $dias_semana[date('w', strtotime($data))];
    
   
    $extenso = $dia_semana;
    return $extenso;
}


function data_extenso($data, $hoje=true) {
//    $dias_semana = array(
//        'Domingo',
//        'Segunda-feira',
//        'Terça-feira',
//        'Quarta-feira',
//        'Quinta-feira',
//        'Sexta-feira',
//        'Sábado'
//    );

    $meses_ano = array(
        '',
        'Janeiro',
        'Fevereiro',
        'Março',
        'Abril',
        'Maio',
        'Junho',
        'Julho',
        'Agosto',
        'Setembro',
        'Outubro',
        'Novembro',
        'Dezembro'
    );
    if($hoje){
        $data = date('Y-m-d');//vai retornar a data atual, senão, será a data informada
    }
    
    $partes = explode('-', $data);
    $dia = $partes[2];
    $mes = $meses_ano[(int)$partes[1]];
    $ano = $partes[0];
//    $dia_semana = $dias_semana[date('w', strtotime($data))];
    
//    $extenso = $dia_semana . ', ' . $dia . ' de ' . $mes . ' de ' . $ano;
    $extenso =  $dia . ' de ' . $mes . ' de ' . $ano;
    return $extenso;
}
 



    function listUsuario(){
        $user = new fatadgestaoModel();
        return  $user->listUsuario(); 
    }

    function exibirAlunos($query){
        $user = new fatadgestaoModel();
        return  $user->carregarAlunos($query);     
    }

    
    function findCurriculo($curso) {
        $user = new fatadgestaoModel();
        return $user->findCurriculo($curso);   
    }
    
    function findInicioFimCurso($idTurma) {
        $user = new fatadgestaoModel();
        return $user->findInicioFimCurso($idTurma);   
    }
    
    function findAlunoEspecifico($idAluno) {
        $user = new fatadgestaoModel();
        return $user->findAlunoEspecifico($idAluno);
    }
    
    function findAlunoComTurma($cpf) {
        $user = new fatadgestaoModel();
        return $user->findAlunoComTurma($cpf);
    }

    function findUsuarioEspecifico($cpfUsuario) {
        $user = new fatadgestaoModel();
        return $user->findUsuarioEspecifico($cpfUsuario);
    }
    
    function findMatrAlunoEspecifico($idAluno, $idTurma) {
        $user = new fatadgestaoModel();
        return $user->findMatrAlunoEspecifico($idAluno, $idTurma);
    }
    
    function findAluno() {
        $user = new fatadgestaoModel();
        return $user->findAluno();
    }
    function findAlunosNucleo($idNucleo) {
        $user = new fatadgestaoModel();
        return $user->findAlunosNucleo($idNucleo);
    }
    function findAlunoCpf($cpf) {
        $user = new fatadgestaoModel();
        return $user->findAlunoCpf($cpf);
    }

    function findUsuarioCpf($cpf) {
        $user = new fatadgestaoModel();
        return $user->findUsuarioCpf($cpf);
    }

    function findAlunoCurso() {
        $user = new fatadgestaoModel();
        return $user->findAlunoCurso();
    }

    function findAlunoRecebeMaterial($idAluno,$idTurma) {
        $user = new fatadgestaoModel();
        return $user->findAlunoRecebeMaterial($idAluno,$idTurma);
    }
    
    function findCursoTurma() {
            $user = new fatadgestaoModel();
            return $user->findCursoTurma();
    }
    
    function findCursosNivelCpf($cpfResp) {
        $user = new fatadgestaoModel();
        return $user->findCursosNivelCpf($cpfResp);
    }
    function findCursosNivel() {
        $user = new fatadgestaoModel();
        return $user->findCursosNivel();
    }
    function findDisciplinasPorCurso($idCurso){ 
        $user = new fatadgestaoModel();
        return $user->findDisciplinasPorCurso($idCurso);
    }


    function findNivelCurso($idHistorico) {
        $user = new fatadgestaoModel();
        return $user->findNivelCurso($idHistorico);
    }

    function findCursoEspecifico($idCurso) {
        $user = new fatadgestaoModel();
        return $user->findCursoEspecifico($idCurso);
    }
    
    function findDisciplinas($nivelDisciplina) {
        $user = new fatadgestaoModel();
        return $user->findDisciplinas($nivelDisciplina);
    }
    function findDisciplinaEspecifica($idDisciplina) {
        $user = new fatadgestaoModel();
        return $user->findDisciplinaEspecifica($idDisciplina);
    }
    function findCaminhoDisciplinaEspecifica($idCurso,$idDisciplina) {
        $user = new fatadgestaoModel();
        return $user->findCaminhoDisciplinaEspecifica($idCurso,$idDisciplina);
    }
    function findCaminhoNomeDisciplinaEspecifica($idDowndocs) {
        $user = new fatadgestaoModel();
        return $user->findCaminhoNomeDisciplinaEspecifica($idDowndocs); 
    }

    function findMediaDisciplina($idTurma,$idCurso,$idNucleo,$idDisciplina) {
        $user = new fatadgestaoModel();
        return $user->findMediaDisciplina($idTurma,$idCurso,$idNucleo,$idDisciplina);
    }
    function findMediaAluno($cpfAluno){

        $user = new fatadgestaoModel();
        return $user->findMediaAluno($cpfAluno);
    }
    function findNotaAluno($cpfAluno,$idCurso,$idDisciplina){

        $user = new fatadgestaoModel();
        return $user->findNotaAluno($cpfAluno,$idCurso,$idDisciplina);
    }
    function findInadimplencia($idNucleo) {
        $user = new fatadgestaoModel();
        return $user->findInadimplencia($idNucleo);
    }

    function findNucleo($idNucleo=0) {
        $user = new fatadgestaoModel();
        return $user->findNucleo($idNucleo=0);
    }
    function findCPFusuario($idUsuario) {
        $user = new fatadgestaoModel();
        return $user->findCPFusuario($idUsuario);
    }
    function findNrNucleo($idNucleo) {
        $user = new fatadgestaoModel();
        return $user->findNrNucleo($idNucleo);
    }

    function findNucleoCpf($cpfResp) {
        $user = new fatadgestaoModel();
        return $user->findNucleoCpf($cpfResp);
    }

    function findListVisitantes() {
        $user = new fatadgestaoModel();
        return $user->findListVisitantes();
    }
    function findListOpFinanceira($tipoOp) {
        $user = new fatadgestaoModel();
        return $user->findListOpFinanceira($tipoOp); 
    }
    function findQtdAlunosNucleo($idNucleo) {
        $user = new fatadgestaoModel();
        return $user->findQtdAlunosNucleo($idNucleo);
    }
    
    function findNucleoEspecifico($idTurma) {
        $user = new fatadgestaoModel();
        return $user->findNucleoEspecifico($idTurma);
    }
    function findDescNucleoEspecifico($idNucleo) {
        $user = new fatadgestaoModel();
        return $user->findDescNucleoEspecifico($idNucleo);
    }
    function findSalaTurma($idCurso) {
        $user = new fatadgestaoModel();
        return $user->findSalaTurma($idCurso);
    }
    
    function findSalaEspecifica($idNucleo, $nmSala) {
        $user = new fatadgestaoModel();
        return $user->findSalaEspecifica($idNucleo, $nmSala);
    } 
    function findCursoNucleo($idNucleo, $idCurso) {
        $user = new fatadgestaoModel();
        return $user->findCursoNucleo($idNucleo, $idCurso);
    } 
    // function findCidadeUF() {
    //     $user = new fatadgestaoModel();
    //     return $user->findCidadeUF();
    // }
    
    function printEnderecamento($perfil, $idNucleo){
        header('Location: opfinanceiraEnderecamento.php?perfil='.$perfil.'&id='.$idNucleo);
        return;
    }
    
    function getEndereco($cep){
//        var_dump($cep);
        //tirando o que não for número
        if(isset($cep)){
        $cep= preg_replace("/[^0-9]/", "", $cep);
        $url="http://viacep.com.br/ws/$cep/xml/";
        $xml= simplexml_load_file($url);
        return $xml;  
        }
    }
    
    function formatCnpjCpf($value){
      $CPF_LENGTH = 11;
      $cnpj_cpf = preg_replace("/\D/", '', $value);

      if (strlen($cnpj_cpf) === $CPF_LENGTH) {
        return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
      } 

      return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
      var_dump(preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf));
    }
    
    function brl2decimal($brl, $casasDecimais = 2) {
        // Se já estiver no formato USD, retorna como float e formatado
        if(preg_match('/^\d+\.{1}\d+$/', $brl))
            return (float) number_format($brl, $casasDecimais, '.', '');
        // Tira tudo que não for número, ponto ou vírgula
        $brl = preg_replace('/[^\d\.\,]+/', '', $brl);
        // Tira o ponto
        $decimal = str_replace('.', '', $brl);
        // Troca a vírgula por ponto
        $decimal = str_replace(',', '.', $decimal);
        return (float) number_format($decimal, $casasDecimais, '.', '');
    }
    
    function excluirCurriculoCurso($idCurso){
        $user = new fatadgestaoModel();
        return $user->excluirCurriculoCurso($idCurso);   
    }
    
    function transferirHisoricoAluno($idAluno,$idTurma,$idCurso){
        $user = new fatadgestaoModel();
        return $user->transferirHisoricoAluno($idAluno,$idTurma,$idCurso);   
    }
    
    function excluirHisoricoAluno($idAluno,$idTurma,$idCurso){
        $user = new fatadgestaoModel();
        return $user->excluirHisoricoAluno($idAluno,$idTurma,$idCurso);   
    }
    
    function criarCurriculoCurso($idCurso){
        $user = new fatadgestaoModel();
        return $user->criarCurriculoCurso($idCurso);   
    }
    
    function findFichaHistorico($idAluno,$idTurma){
        $user = new fatadgestaoModel();
        return $user->findFichaHistorico($idAluno,$idTurma);   
    }

    function findFichaMatricula($idAluno){
        $user = new fatadgestaoModel();
        return $user->findFichaMatricula($idAluno);   
    }

    function findBoletimNucleo($idTurma,$idCurso,$idNucleo,$idDisciplina){
        $user = new fatadgestaoModel();
        return $user->findBoletimNucleo($idTurma,$idCurso,$idNucleo,$idDisciplina); 
    }
    
     function findCabecalhoFichaHistorico($idAluno,$idTurma){
        $user = new fatadgestaoModel();
        return $user->findCabecalhoFichaHistorico($idAluno,$idTurma);   
    }
    function findOpFinanceiraRecibo($idMaterial, $idTurma,$idCurso) {
        $user = new fatadgestaoModel();
        return $user->findOpFinanceiraRecibo($idMaterial, $idTurma,$idCurso);   
    }
    function findOpFinanceiraReciboEspecifico($idOp,$perfil) {
        $user = new fatadgestaoModel();
        if($perfil=='Núcleo'){
            return $user->findOpFinReciboNucleo($idOp); 
        }else{
            return $user->findOpFinReciboAluno($idOp);
        }   
    }
    
    function findOpFinanceiraEnderecamento($idOp,$perfil) {
        $user = new fatadgestaoModel();
        if($perfil=='Núcleo'){
            return $user->findOpFinReciboNucleo($idOp); 
        }else{
            return $user->findOpFinReciboAluno($idOp);
        }   
    }
    function findidOpMax($perfil) {
        $user = new fatadgestaoModel();
            return $user->findidOpMax($perfil);   
    }
    
    function findTudoDeNucleoEspecifico($idNucleo) {
        $user = new fatadgestaoModel();
            return $user->findTudoDeNucleoEspecifico($idNucleo);
    }
    
    function findTudoDeAlunoEspecifico($idAluno) {
        $user = new fatadgestaoModel();
            return $user->findTudoDeAlunoEspecifico($idAluno);
    }
    
    function numberToText($value, $uppercase = 0) {
    if (strpos($value, ",") > 0) {
        $value = str_replace(".", "", $value);
        $value = str_replace(",", ".", $value);
    }
 
    $singular = ["centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão"];
    $plural = ["centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões"];
 
    $c = ["", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos"];
    $d = ["", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa"];
    $d10 = ["dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove"];
    $u = ["", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove"];
 
    $z = 0;
 
    $value = number_format($value, 2, ".", ".");
    $integer = explode(".", $value);
    $cont = count($integer);
 
    for ($i = 0; $i < $cont; $i++)
        for ($ii = strlen($integer[$i]); $ii < 3; $ii++)
            $integer[$i] = "0" . $integer[$i];
 
    $fim = $cont - ($integer[$cont - 1] > 0 ? 1 : 2);
    $rt = '';
    for ($i = 0; $i < $cont; $i++) {
        $value = $integer[$i];
        $rc = (($value > 100) && ($value < 200)) ? "cento" : $c[$value[0]];
        $rd = ($value[1] < 2) ? "" : $d[$value[1]];
        $ru = ($value > 0) ? (($value[1] == 1) ? $d10[$value[2]] : $u[$value[2]]) : "";
 
        $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd &&
                $ru) ? " e " : "") . $ru;
        $t = $cont - 1 - $i;
        $r .= $r ? " " . ($value > 1 ? $plural[$t] : $singular[$t]) : "";
        if ($value == "000"
        )
            $z++;
        elseif ($z > 0)
            $z--;
        if (($t == 1) && ($z > 0) && ($integer[0] > 0))
            $r .= ( ($z > 1) ? " de " : "") . $plural[$t];
        if ($r)
            $rt = $rt . ((($i > 0) && ($i <= $fim) &&
                    ($integer[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
    }
 
 return trim($rt ? $rt : "zero");
}

}

