<?php

session_start(); // Iniciar a sessão

// Limpar o buffer de saída
ob_start();

// Incluir a conexão com BD
include_once './includes/conn.php';
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;

// Receber o arquivo do formulário
$arquivo = $_FILES['arquivo'];
$dtProva=$_POST['dtProva'];

// var_dump($arquivo);
// var_dump($dtProva);

// Variáveis de validação
$primeira_linha = true;
$linhas_importadas = 0;
$linhas_nao_importadas = 0;
$usuarios_nao_importado = "";

// Verificar se é arquivo csv
if($arquivo['type'] == "text/csv"){

    // Ler os dados do arquivo
    $dados_arquivo = fopen($arquivo['tmp_name'], "r");

    // Percorrer os dados do arquivo
    while($linha = fgetcsv($dados_arquivo, 1000, ";")){

        // Como ignorar a primeira linha do Excel
        if($primeira_linha){
            $primeira_linha = false;
            continue;
        }

        // Usar array_walk_recursive para criar função recursiva com PHP
        array_walk_recursive($linha, 'converter');
        var_dump($linha);

        // Troca a vírgula por ponto, se houver
        $gFormatado=str_replace(',', '.', $linha[7]);
        
        //Tratando a nota
        if($gFormatado>10) {
            $gFormatado=10; 
        } else if($gFormatado<0) {
          $gFormatado=0;  
        }
        
        //Tratamento para o status do aluno, em face da nota recebida      
        //Para os cursos médios e básicos, a nota e a média para aprovação é maior ou igual a 5 (cinco).
        //Para o curso avançado, a média para aprovação é maior ou igual a 7 (sete).
        $nivel= substr($linha[4],0,4);
//        $nivelCurso=$fg->findNivelCurso($idHistorico);
//        foreach ($nivelCurso as $rowNivel) {
//          $nivel=$rowNivel->nivelCurso;
//          break;
//        }
        if($gFormatado<5){
          $situacao="Reprovado"; 
        }else{
            if($nivel=="Avan"){
              if($gFormatado>6.99){
                $situacao="Aprovado";  
              }
              if($gFormatado>4.99 and $gFormatado<7){
                 $situacao="Aprovado(*)"; 
              }
            }else{
              if($gFormatado>4.99){
                $situacao="Aprovado";  
              }
            }
        }
       
        
//        // Criar a QUERY para salvar o usuário no banco de dados
        $query_aluno = "UPDATE tb_historico_aluno SET "
                ."dtTerEstudo= :dtTerEstudo, "
                ."nota=:nota, "
                ."situacao=:situacao "
                ."WHERE idHistorico=:idHistorico";
        // Preparar a QUERY
        $query_aluno = $conn->prepare($query_aluno);

        // Substituir os links da QUERY pelos valores
        $query_aluno->bindValue(':idHistorico', ($linha[0] ?? "NULL"));
        $query_aluno->bindValue(':dtTerEstudo', ($dtProva ?? "NULL"));
        $query_aluno->bindValue(':situacao', ($situacao ?? "NULL"));
        $query_aluno->bindValue(':nota', ($gFormatado ?? "NULL"));

        // Executar a QUERY
        $query_aluno->execute();

        // Verificar se cadastrou corretamente no banco de dados
        if($query_aluno->rowCount()){
            $linhas_importadas++;
        }else{
            $linhas_nao_importadas++;
            if(empty($linha[0])){
                $usuarios_nao_importado = $usuarios_nao_importado . ", " . "'sem idHistorico'";
            }else{
                $usuarios_nao_importado = $usuarios_nao_importado . ", " . ($linha[0] ?? "NULL");
            }
        }
    }

    // Criar a mensagem com os CPF dos usuários não cadastrados no banco de dados
    if(!empty($usuarios_nao_importado)){
        $usuarios_nao_importado = "IdHistorico não importados: $usuarios_nao_importado.";
    }

    // Mensagem de sucesso
    $_SESSION['msg'] = "<p style='color: green;'>$linhas_importadas linha(s) importa(s), $linhas_nao_importadas linha(s) não importada(s). $usuarios_nao_importado</p>";

    // Redirecionar o usuário
    header("Location: pesquisarAlunoCurso.php");
}else{

    // Mensagem de erro
    $_SESSION['msg'] = "<p style='color: #f00;'>Necessário enviar arquivo csv!</p>";

    // Redirecionar o usuário
    header("Location: pesquisarAlunoCurso.php");
}

// Criar função valor por referência, isto é, quando alter o valor dentro da função, vale para a variável fora da função.
function converter(&$dados_arquivo)
{
    // Converter dados de ISO-8859-1 para UTF-8
    $dados_arquivo = mb_convert_encoding($dados_arquivo, "UTF-8", "ISO-8859-1");
}