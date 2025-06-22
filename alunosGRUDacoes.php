<?php
session_start();

require './conexao.php';
include_once './config.php';

include_once './fatadgestaoControler.php';
$fg=new fatadgestaoControler;

if (isset($_SESSION['usuario_autenticado'])) { 
    $privilegio = $_SESSION['privilegio'];
    $usuario=$_SESSION['usuario']; 
}

// var_dump($privilegio);
// var_dump($_POST);
// exit();

if (isset($_POST['cadastro_aluno'])) {

    // Obtenha os dados do formulário
    $cpfAluno = filter_input(INPUT_POST, 'cpfAluno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $nomeAluno = filter_input(INPUT_POST, 'nomeAluno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nomeAluno = html_entity_decode($nomeAluno, ENT_QUOTES, 'UTF-8');

    $cep = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $numero = filter_input(INPUT_POST, 'numero', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $rua = filter_input(INPUT_POST, 'rua', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $complemento = filter_input(INPUT_POST, 'complemento', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $bairro = filter_input(INPUT_POST, 'bairro', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $cidade = filter_input(INPUT_POST, 'cidade', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cidade = html_entity_decode($cidade, ENT_QUOTES, 'UTF-8');

    $estado = filter_input(INPUT_POST, 'estado', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $cidadeNatAluno = filter_input(INPUT_POST, 'cidadeNatal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cidadeNatAluno = html_entity_decode($cidadeNatAluno, ENT_QUOTES, 'UTF-8');

    $dtNascAluno = filter_input(INPUT_POST, 'dtNasc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
 
    $nomePaiAluno = filter_input(INPUT_POST, 'nomePai', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nomePaiAluno = html_entity_decode($nomePaiAluno, ENT_QUOTES, 'UTF-8');

    $nomeMaeAluno = filter_input(INPUT_POST, 'nomeMae', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nomeMaeAluno = html_entity_decode($nomeMaeAluno, ENT_QUOTES, 'UTF-8');

    $idtAluno = filter_input(INPUT_POST, 'idtAluno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $emailAluno = filter_input(INPUT_POST, 'emailAluno', FILTER_SANITIZE_EMAIL);
    $telZapAluno = filter_input(INPUT_POST, 'telZap', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $dtIniEstudo = filter_input(INPUT_POST, 'dtIniEstudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $dtTerEstudo = filter_input(INPUT_POST, 'dtTerEstudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $estadoCivil = filter_input(INPUT_POST, 'estadoCivil', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $estadoCivil = html_entity_decode($estadoCivil, ENT_QUOTES, 'UTF-8');

    $nomeConjuge = filter_input(INPUT_POST, 'nomeConjuge', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nomeConjuge = html_entity_decode($nomeConjuge, ENT_QUOTES, 'UTF-8');

    $escolaridade = filter_input(INPUT_POST, 'escolaridade', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $escolaridade = html_entity_decode($escolaridade, ENT_QUOTES, 'UTF-8');

    $instEnsino = filter_input(INPUT_POST, 'instEnsino', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $instEnsino = html_entity_decode($instEnsino, ENT_QUOTES, 'UTF-8');

    $instIgreja = filter_input(INPUT_POST, 'instIgreja', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $instIgreja = html_entity_decode($instIgreja, ENT_QUOTES, 'UTF-8');

    $endIgreja = filter_input(INPUT_POST, 'endIgreja', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $endIgreja = html_entity_decode($endIgreja, ENT_QUOTES, 'UTF-8');

    $nomePastor = filter_input(INPUT_POST, 'nomePastor', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nomePastor = html_entity_decode($nomePastor, ENT_QUOTES, 'UTF-8');

    $cargoFuncao = filter_input(INPUT_POST, 'cargoFuncao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cargoFuncao = html_entity_decode($cargoFuncao, ENT_QUOTES, 'UTF-8');

    $obs = filter_input(INPUT_POST, 'obs', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $obs = html_entity_decode($obs, ENT_QUOTES, 'UTF-8');

    
    $txtEndereco = $rua . ", nº " . $numero . " " . $complemento . " - " . $bairro;
    $txtEndereco = html_entity_decode($txtEndereco, ENT_QUOTES, 'UTF-8');

  //Se operador for admFatad ou opFatad, virá do formulário senão, será buscado
    if($privilegio=="admFatad" OR $privilegio=="opFatad"){
        $idNucleo=filter_input(INPUT_POST,'nucleo', FILTER_SANITIZE_NUMBER_INT);
        
    }elseif($privilegio=="opNuc"){

        $nucleo = $fg->findNucleoCpf($_SESSION['usuario']);
        foreach ($nucleo as $row) {
             $idNucleo = $row->idNucleo;
             break;
        }
    }

    try {

        $pdo = new Config();
        // Verifique se o CPF já existe
        $query = "SELECT * FROM tb_aluno WHERE cpfAluno = :cpfAluno";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':cpfAluno', $cpfAluno, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {

            $_SESSION['message'] = "CPF: " . $cpfAluno . ' já existe. Aluno NÃO FOI cadastrado';
            $_SESSION['message_type'] = 'danger'; // Alerta de fracasso
            
                if($privilegio=="opFatad"  ||  $privilegio=="admFatad" || $privilegio=="opNuc"){ 
                    header('Location: alunosGRUD.php');
                    exit();
                }
                if($privilegio=="Visitante"){ 
                    header('Location: iniciar.php');
                    exit();
                }

        } else {
            // Insira os dados do aluno
            $sql = "INSERT INTO tb_aluno (cpfAluno, nomeAluno, enderecoAluno, cidadeMoradia, cep, 
                     cidadeNatAluno, dtNascAluno, dtIniEstudo,dtTerEstudo,
                     nomePaiAluno, nomeMaeAluno, idtAluno, emailAluno, telZapAluno, idCadastro,
                     estadoCivil,nomeConjuge, escolaridade, instEnsino, instIgreja, endIgreja, nomePastor, cargoFuncao, obs)
                    VALUES (:cpfAluno, :nomeAluno, :enderecoAluno, :cidadeMoradia, :cep, 
                    :cidadeNatAluno, :dtNascAluno, :dtIniEstudo,:dtTerEstudo,
                    :nomePaiAluno, :nomeMaeAluno, :idtAluno, :emailAluno, :telZapAluno, :idCadastro,
                    :estadoCivil, :nomeConjuge, :escolaridade, :instEnsino, :instIgreja, :endIgreja, :nomePastor, :cargoFuncao, :obs)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':cpfAluno', $cpfAluno, PDO::PARAM_STR);
            $stmt->bindParam(':nomeAluno', $nomeAluno, PDO::PARAM_STR);
            $stmt->bindParam(':enderecoAluno', $txtEndereco, PDO::PARAM_STR);
            $stmt->bindParam(':cidadeMoradia', $cidade, PDO::PARAM_STR);
            $stmt->bindParam(':cep', $cep, PDO::PARAM_STR);
            $stmt->bindParam(':cidadeNatAluno', $cidadeNatAluno, PDO::PARAM_STR);
            $stmt->bindParam(':dtNascAluno', $dtNascAluno, PDO::PARAM_STR);
            
            $stmt->bindParam(':nomePaiAluno', $nomePaiAluno, PDO::PARAM_STR);
            $stmt->bindParam(':nomeMaeAluno', $nomeMaeAluno, PDO::PARAM_STR);
            $stmt->bindParam(':idtAluno', $idtAluno, PDO::PARAM_STR);
            $stmt->bindParam(':emailAluno', $emailAluno, PDO::PARAM_STR);
            $stmt->bindParam(':telZapAluno', $telZapAluno, PDO::PARAM_STR);
            $stmt->bindParam(':idCadastro', $idNucleo, PDO::PARAM_INT);
            
            $stmt->bindParam(':dtIniEstudo', $dtIniEstudo, PDO::PARAM_STR);
            $stmt->bindParam(':dtTerEstudo', $dtTerEstudo, PDO::PARAM_STR);
            $stmt->bindParam(':estadoCivil', $estadoCivil, PDO::PARAM_STR);
            $stmt->bindParam(':nomeConjuge', $nomeConjuge, PDO::PARAM_STR);
            $stmt->bindParam(':escolaridade', $escolaridade, PDO::PARAM_STR);
            $stmt->bindParam(':instEnsino', $instEnsino, PDO::PARAM_STR);
            $stmt->bindParam(':instIgreja', $instIgreja, PDO::PARAM_STR);
            $stmt->bindParam(':endIgreja', $endIgreja, PDO::PARAM_STR);
            $stmt->bindParam(':nomePastor', $nomePastor, PDO::PARAM_STR);
            $stmt->bindParam(':cargoFuncao', $cargoFuncao, PDO::PARAM_STR);
            $stmt->bindParam(':obs', $obs, PDO::PARAM_STR);
             
            try {
                $result = $stmt->execute();


                if ($result) {
                    $_SESSION['message'] = 'Aluno ' . $nomeAluno . ' foi INCLUIDO.';
                } else {
                    $_SESSION['message'] = 'Aluno ' . $nomeAluno . ' NÃO FOI INCLUIDO.';
                }

                if($privilegio=="opFatad"  ||  $privilegio=="admFatad" || $privilegio=="opNuc"){ 
                    header('Location: alunosGRUD.php');
                    exit;
                }
                if($privilegio=="Visitante"){ 
                    header('Location: iniciar.php');
                    exit;
                }

                
                
            } catch (PDOException $e) {
                error_log("Erro ao atualizar aluno: " . $e->getMessage());
                
                $_SESSION['message'] = 'Erro ao incluir o aluno. Tente novamente mais tarde.';
                if($privilegio=="opFatad" || $privilegio=="admFatad" || $privilegio=="opNuc"){ 
                    header('Location: alunosGRUD.php');
                    exit;
                }
                if($privilegio=="Visitante"){ 
                    header('Location: iniciar.php');
                    exit;
                }
            }
        }

    } 
    catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
        exit;
    }
}

if (isset($_POST['update_aluno']) || isset($_POST['updateAlunoEditFatad'])) {   
    
    // Obtenha os dados do formulário
    $aluno_id = filter_input(INPUT_POST, 'idAluno', FILTER_SANITIZE_NUMBER_INT);

    $cpfAluno = filter_input(INPUT_POST, 'cpfAluno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $nomeAluno = filter_input(INPUT_POST, 'nomeAluno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nomeAluno = html_entity_decode($nomeAluno, ENT_QUOTES, 'UTF-8');

    $cep = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    $txtEndereco = filter_input(INPUT_POST, 'enderecoAluno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $txtEndereco = html_entity_decode($txtEndereco, ENT_QUOTES, 'UTF-8');
    
    $cidadeMoradia = filter_input(INPUT_POST, 'cidadeUF', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cidadeMoradia = html_entity_decode($cidadeMoradia, ENT_QUOTES, 'UTF-8');

    $cidadeNatAluno = filter_input(INPUT_POST, 'cidadeNatal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cidadeNatAluno = html_entity_decode($cidadeNatAluno, ENT_QUOTES, 'UTF-8');

    $dtNascAluno = filter_input(INPUT_POST, 'dtNasc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $dtIniEstudo = filter_input(INPUT_POST, 'dtIniEstudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $dtTerEstudo = filter_input(INPUT_POST, 'dtTerEstudo', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $nomePaiAluno = filter_input(INPUT_POST, 'nomePai', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nomePaiAluno = html_entity_decode($nomePaiAluno, ENT_QUOTES, 'UTF-8');

    $nomeMaeAluno = filter_input(INPUT_POST, 'nomeMae', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nomeMaeAluno = html_entity_decode($nomeMaeAluno, ENT_QUOTES, 'UTF-8');

    $idtAluno = filter_input(INPUT_POST, 'idtAluno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $emailAluno = filter_input(INPUT_POST, 'emailAluno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $telZapAluno = filter_input(INPUT_POST, 'telZap', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $estadoCivil = filter_input(INPUT_POST, 'estadoCivil', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $estadoCivil = html_entity_decode($estadoCivil, ENT_QUOTES, 'UTF-8');

    $nomeConjuge = filter_input(INPUT_POST, 'nomeConjuge', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nomeConjuge = html_entity_decode($nomeConjuge, ENT_QUOTES, 'UTF-8');

    $escolaridade = filter_input(INPUT_POST, 'escolaridade', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $escolaridade = html_entity_decode($escolaridade, ENT_QUOTES, 'UTF-8');

    $instEnsino = filter_input(INPUT_POST, 'instEnsino', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $instEnsino = html_entity_decode($instEnsino, ENT_QUOTES, 'UTF-8');

    $instIgreja = filter_input(INPUT_POST, 'instIgreja', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $instIgreja = html_entity_decode($instIgreja, ENT_QUOTES, 'UTF-8');

    $endIgreja = filter_input(INPUT_POST, 'endIgreja', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $endIgreja = html_entity_decode($endIgreja, ENT_QUOTES, 'UTF-8');

    $nomePastor = filter_input(INPUT_POST, 'nomePastor', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nomePastor = html_entity_decode($nomePastor, ENT_QUOTES, 'UTF-8');

    $cargoFuncao = filter_input(INPUT_POST, 'cargoFuncao', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $cargoFuncao = html_entity_decode($cargoFuncao, ENT_QUOTES, 'UTF-8');

    $obs = filter_input(INPUT_POST, 'obs', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $obs = html_entity_decode($obs, ENT_QUOTES, 'UTF-8');


    if (empty($aluno_id) || !is_numeric($aluno_id)) {
        $_SESSION['message'] = 'ID do aluno inválido.';
        if($privilegio=="opFatad" OR $privilegio=="admFatad"){ 
            header('Location: alunosGRUD.php');
        }
        if($privilegio=="Visitante"){ 
            header('Location: iniciar.php');
        }
        exit();
    }
    
    if (!filter_var($emailAluno, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = 'E-mail inválido.';
        if($privilegio=="opFatad" OR $privilegio=="admFatad"){ 
            header('Location: alunosGRUD.php');
        }
        if($privilegio=="Visitante"){ 
            header('Location: iniciar.php');
        }
        exit();
    }

    try {
        $pdo = new Config();

        // Atualizar dados do aluno
        $sql = "UPDATE tb_aluno SET 
                cpfAluno = :cpfAluno,
                nomeAluno = :nomeAluno,
                enderecoAluno = :enderecoAluno,
                cidadeMoradia = :cidadeMoradia,
                cep = :cep,
                cidadeNatAluno = :cidadeNatAluno,
                dtNascAluno = :dtNascAluno,
                nomePaiAluno = :nomePaiAluno,
                nomeMaeAluno = :nomeMaeAluno,
                idtAluno = :idtAluno,
                emailAluno = :emailAluno,
                telZapAluno = :telZapAluno,
                dtIniEstudo = :dtIniEstudo,
                dtTerEstudo = :dtTerEstudo,
                estadoCivil = :estadoCivil,
                nomeConjuge = :nomeConjuge,
                escolaridade = :escolaridade,
                instEnsino = :instEnsino,
                instIgreja = :instIgreja,
                endIgreja = :endIgreja,
                nomePastor = :nomePastor,
                cargoFuncao = :cargoFuncao,
                obs = :obs
                WHERE idAluno = :idAluno";

        $stmt = $pdo->prepare($sql);

        $stmt->bindParam(':cpfAluno', $cpfAluno, PDO::PARAM_STR);
        $stmt->bindParam(':nomeAluno', $nomeAluno, PDO::PARAM_STR);
        $stmt->bindParam(':enderecoAluno', $txtEndereco, PDO::PARAM_STR);
        $stmt->bindParam(':cidadeMoradia', $cidadeMoradia, PDO::PARAM_STR);
        $stmt->bindParam(':cep', $cep, PDO::PARAM_STR);
        $stmt->bindParam(':cidadeNatAluno', $cidadeNatAluno, PDO::PARAM_STR);
        $stmt->bindParam(':dtNascAluno', $dtNascAluno, PDO::PARAM_STR);
        $stmt->bindParam(':nomePaiAluno', $nomePaiAluno, PDO::PARAM_STR);
        $stmt->bindParam(':nomeMaeAluno', $nomeMaeAluno, PDO::PARAM_STR);
        $stmt->bindParam(':idtAluno', $idtAluno, PDO::PARAM_STR);
        $stmt->bindParam(':emailAluno', $emailAluno, PDO::PARAM_STR);
        $stmt->bindParam(':telZapAluno', $telZapAluno, PDO::PARAM_STR);
        $stmt->bindParam(':idAluno', $aluno_id, PDO::PARAM_INT);

        $stmt->bindParam(':dtIniEstudo', $dtIniEstudo, PDO::PARAM_STR);
        $stmt->bindParam(':dtTerEstudo', $dtTerEstudo, PDO::PARAM_STR);
        $stmt->bindParam(':estadoCivil', $estadoCivil, PDO::PARAM_STR);
        $stmt->bindParam(':nomeConjuge', $nomeConjuge, PDO::PARAM_STR);
        $stmt->bindParam(':escolaridade', $escolaridade, PDO::PARAM_STR);
        $stmt->bindParam(':instEnsino', $instEnsino, PDO::PARAM_STR);
        $stmt->bindParam(':instIgreja', $instIgreja, PDO::PARAM_STR);
        $stmt->bindParam(':nomePastor', $nomePastor, PDO::PARAM_STR);
        $stmt->bindParam(':cargoFuncao', $cargoFuncao, PDO::PARAM_STR);
        $stmt->bindParam(':obs', $obs, PDO::PARAM_STR);
        $stmt->bindParam(':endIgreja', $endIgreja, PDO::PARAM_STR);


        if ($stmt->execute()) {
            $_SESSION['message'] = 'Aluno ' . $nomeAluno . ' atualizado com sucesso';
        } else {
            $_SESSION['message'] = 'Aluno ' . $nomeAluno . ' não foi atualizado';
        }

        //Se a edição vier do opFatad de aluno com perfil "Visitante"
        if (isset($_POST['updateAlunoEditFatad'])) {   
            header('Location: cadastroListVisitantes.php');
            exit();
        }elseif(isset($_POST['update_aluno'])){
            header('Location: alunosGRUD.php');
            exit();
        }else{
            header('Location: iniciar.php');
            exit();
        }

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}


if (isset($_POST['CadArquivo'])) {
    // var_dump($_POST);
    // exit();
    // Diretório base onde as pastas serão criadas
    $baseDir = 'C:/ArquivosFatad/uploads/';
    $cpfAluno = filter_input(INPUT_POST, 'cpfAluno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $idAluno = filter_input(INPUT_POST, 'idAluno', FILTER_SANITIZE_NUMBER_INT);

    $incluidos=0;
    $nincluidos=0;
    $msg3="";
    $msg4="";

    // Crie a pasta com base no cpfAluno
    $uploadDir = $baseDir . $cpfAluno . '/';

    // Verifique se o diretório existe, caso contrário, crie-o
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Processar múltiplos arquivos
    $files = $_FILES['files'];
    $numFiles = count($files['name']);

    $maxFileSize = 5 * 1024 * 1024; // 5MB

    for ($i = 0; $i < $numFiles; $i++) {

        if (!empty($files['name'][$i]) && $files['error'][$i] === UPLOAD_ERR_OK) {
            if ($files['size'][$i] > $maxFileSize) {
                $nincluidos++;
                $msg4 .= " " . $files['name'][$i] . ". ";
                continue; // Pula para o próximo arquivo
            }
            if ($files['size'][$i] == 0) {
                $msg4 .= " Arquivo muito grande e foi ignorado pelo servidor: " . $files['name'][$i];
                $nincluidos++;
                continue;
            }

            $fileName = basename($files['name'][$i]);
            $targetFilePath = $uploadDir . $fileName;

            // Movendo o arquivo para o diretório de destino
            if (move_uploaded_file($files['tmp_name'][$i], $targetFilePath)) {
                try {
                    $pdo = new Config();
                    $sql = "INSERT INTO tb_docalunos (idAluno, caminhoNomeArq, nomeArq) VALUES (:idAluno, :caminhoNomeArq, :nomeArq)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':idAluno', $idAluno, PDO::PARAM_INT);
                    $stmt->bindParam(':caminhoNomeArq', $targetFilePath, PDO::PARAM_STR);
                    $stmt->bindParam(':nomeArq', $fileName, PDO::PARAM_STR);

                    if ($stmt->execute()) {
                        $msg3 = "Tamanho até 5MB.";
                        $incluidos++;
                    } else {
                        $errorInfo = $stmt->errorInfo();
                        $msg4 .= " Erro ao inserir no Banco: " . $errorInfo[2];
                        $nincluidos++;
                    }

                    // Fechar a conexão
                    $pdo = null;

                } catch (PDOException $e) {
                    $msg4 .= " Erro: " . $e->getMessage();
                    $nincluidos++;
                }
            } else {
                $nincluidos++;
            }
        }
    }

// Mensagens de status
$msg=
$_SESSION['message'] = $incluidos . ' arquivo(s) copiado(s). ' . $msg3 . ' ' . ($nincluidos > 0 ? $nincluidos . ' arquivo(s) rejeitado(s) por tamanho (5MB máximo): ' . $msg4 : '');        
		    header('Location: alunoGRUDlistDoc.php?idAluno='. $idAluno);
       
}

if (isset($_POST['excluir_documento'])) {
    // Obtenha o ID do documento do POST
    $idArquivo = filter_input(INPUT_POST, 'idArquivo', FILTER_SANITIZE_NUMBER_INT);
    $idAluno = filter_input(INPUT_POST, 'idAluno', FILTER_SANITIZE_NUMBER_INT);


    if ($idArquivo === false || $idArquivo === null) {
        $_SESSION['message'] = 'ID do documento inválido';
        header('Location: alunoGRUDlistDoc.php?idAluno=' . $idAluno);
        exit;
    }

    try {
        $pdo = new Config();

        // Preparar a consulta de exclusão
        $sql = "DELETE FROM tb_docalunos WHERE idArquivo = :idArquivo";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idArquivo', $idArquivo, PDO::PARAM_INT);

        // Executar a consulta
        if ($stmt->execute()) {
            $_SESSION['message'] = 'Arquivo excluído com sucesso';
            $_SESSION['message_type'] = 'success'; // Alerta de sucesso

        } else {
            $errorInfo = $stmt->errorInfo();
            $_SESSION['message'] = 'FALHOU a exclusão do arquivo: ' . $errorInfo[2];
            $_SESSION['message_type'] = 'danger'; // Alerta de sucesso

        }

         // Redirecionamento
         header('Location: alunoGRUDlistDoc.php?idAluno=' . $idAluno);
         exit;

    } catch (PDOException $e) {


        // Debug - Verifique o valor de $idAluno
        echo "Erro ao redirecionar para alunoGRUDlistDoc.php?idAluno=" . $idAluno;
        exit;


    }
}
?>





