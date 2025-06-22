<?php
session_start();
include_once 'fatadgestaoControler.php';
$fg=new fatadgestaoControler;
require_once 'config.php'; // Arquivo de conexão com PDO

$cpf="";

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

            header('Location: iniciar.php');
            exit();
                

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

                header('Location: iniciar.php');
                exit;
                
            } catch (PDOException $e) {
                error_log("Erro ao atualizar aluno: " . $e->getMessage());
                $_SESSION['message'] = 'Erro ao incluir o aluno. Tente novamente mais tarde.';
                header('Location: iniciar.php');
                exit;
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
            header('Location: iniciar.php');
        exit();
    }
    
    if (!filter_var($emailAluno, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['message'] = 'E-mail inválido.';
            header('Location: iniciar.php');
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

        header('Location: iniciar.php');
        exit();

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}

if (isset($_POST['updateAlunoCompl'])) {   
    
    // Obtenha os dados do formulário
    $aluno_id = filter_input(INPUT_POST, 'idAluno', FILTER_SANITIZE_NUMBER_INT);
    
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


    if (empty($aluno_id) || !is_numeric($aluno_id)) {
        $_SESSION['message'] = 'ID do aluno inválido.';
            header('Location: iniciar.php');
        exit();
    }

    try {
        $pdo = new Config();

        // Atualizar dados do aluno
        $sql = "UPDATE tb_aluno SET 
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

        header('Location: iniciar.php');
        exit();

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}

if (isset($_POST['btn']) && $_POST['btn'] === 'Cadastrar') {
    if (isset($_POST['cpf'])) {
        try {
            // Instanciando a conexão com PDO
            $pdo = new Config;

            // Formatação do CPF (garantindo que a função existe)
             
            
            $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $cpf = preg_replace('/[^0-9]/', '', $cpf);

            $nomeUsuario = filter_input(INPUT_POST, 'nomeUsuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $nomeUsuario = html_entity_decode($nomeUsuario, ENT_QUOTES, 'UTF-8');

            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

            $telZap = filter_input(INPUT_POST, 'telZap', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $nmAnimal = filter_input(INPUT_POST, 'nmAnimal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $nmAnimal = html_entity_decode($nmAnimal, ENT_QUOTES, 'UTF-8');

            $senha = filter_input(INPUT_POST, 'senha', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $senha = trim(html_entity_decode($senha, ENT_QUOTES, 'UTF-8'));

            $nmEscola = filter_input(INPUT_POST, 'nmEscola', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $nmEscola = html_entity_decode($nmEscola, ENT_QUOTES, 'UTF-8');
            
            $dataHoje = date("Y-m-d H:i:s");

            if (strlen($senha) < 8 || !preg_match('/[A-Za-z]/', $senha) || !preg_match('/[0-9]/', $senha)) {
                    $_SESSION['erro'] = "Senha inválida. Ela deve conter pelo menos 8 caracteres, incluindo letras e números.";
                    $_SESSION['cpf']=$cpf;
                    $_SESSION['nomeUsuario']=$nomeUsuario;
                    $_SESSION['email']=$email;
                    $_SESSION['telZap']=$telZap;
                    $_SESSION['nmAnimal']=$nmAnimal;
                    $_SESSION['nmEscola']=$nmEscola; 
                echo "<script>
                    alert('Senha inválida! Ela deve conter pelo menos 8 caracteres, incluindo letras e números.');
                    window.location.href = 'cadastro.php';
                </script>";
                exit();
            }
            // Verificar se o CPF já existe no banco      
            $stmt = $pdo->prepare("SELECT * FROM tb_usuarios WHERE cpfUsuario = :cpf");
            $stmt->execute(['cpf' => $cpf]);

            if ($stmt->rowCount() > 0) {                
            echo "<script>
                alert('CPF: " . htmlspecialchars($cpf, ENT_QUOTES, 'UTF-8'). " já existe.');
                window.location.href = 'logout.php';
            </script>";
                exit(); 

            } else {
                // Inserção segura com parâmetros preparados
                $privilegio="Visitante";
                //para criptografar a senha antes de envia ao BD
                $senha = password_hash($senha, PASSWORD_DEFAULT);

                $sql = "INSERT INTO tb_usuarios 
                        (cpfUsuario, nomeUsuario, emailUsuario, telZapUsuario, dtCriacaoUsuario, varPalavraBase, varPrivilegio, idSessao, nomeAnimalUsuario, nomeEscolaUsuario)
                        VALUES 
                        (:cpf, :nomeUsuario, :email, :telZap, :dtCriacaoUsuario, :senha, :privilegio, :idSessao, :nmAnimal, :nmEscola)";
                
                $stmt = $pdo->prepare($sql);
                $params = [
                    'cpf' => $cpf,
                    'nomeUsuario' => $nomeUsuario,
                    'email' => $email,
                    'telZap' => $telZap,
                    'dtCriacaoUsuario' => $dataHoje,
                    'senha' => $senha,
                    'privilegio' => $privilegio,
                    'idSessao' => $idSessao,
                    'nmAnimal' => $nmAnimal,
                    'nmEscola' => $nmEscola
                ];

                if ($stmt->execute($params)) {
                    session_start();
                    $_SESSION['usuario'] = $cpf;
                    $_SESSION['usuario_autenticado'] = True;
                    $_SESSION['privilegio'] = $privilegio;
                    $_SESSION['idSessao'] = $idSessao;

                    header('Location: iniciar.php');
                    echo "Usuário " . substr($nomeUsuario, 0, 20) . "... foi incluído.";
                    exit();
                } else {
                    echo "Erro ao inserir usuário.";
                }
            }
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}

if (isset($_POST['salvarEdicao']) && $_POST['salvarEdicao'] === 'Salvar') {
//  var_dump($_SESSION);
// var_dump($_POST);
// exit();   
    if (isset($_POST['cpf'])) {
        try {
            // Instanciando a conexão com PDO
            $pdo = new Config;

            // Formatação do CPF (garantindo que a função existe)
            $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $cpf = preg_replace('/[^0-9]/', '', $cpf);

            $nomeUsuario = filter_input(INPUT_POST, 'nomeUsuario', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $nomeUsuario = html_entity_decode($nomeUsuario, ENT_QUOTES, 'UTF-8');

            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);

            $telZap = filter_input(INPUT_POST, 'telZap', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            $nmAnimal = filter_input(INPUT_POST, 'nmAnimal', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $nmAnimal = html_entity_decode($nmAnimal, ENT_QUOTES, 'UTF-8');

            $nmEscola = filter_input(INPUT_POST, 'nmEscola', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $nmEscola = html_entity_decode($nmEscola, ENT_QUOTES, 'UTF-8');
            
            $dataHoje = date("Y-m-d H:i:s");
           
            // Inserção segura com parâmetros preparados

            $sql = "UPDATE tb_usuarios 
            SET cpfUsuario = :cpf, 
                nomeUsuario = :nomeUsuario, 
                emailUsuario = :email, 
                telZapUsuario = :telZap,  
                nomeAnimalUsuario = :nmAnimal, 
                nomeEscolaUsuario = :nmEscola
            WHERE cpfUsuario = :cpf";
            $stmt = $pdo->prepare($sql);
            $params = [
                'cpf' => $cpf,
                'nomeUsuario' => $nomeUsuario,
                'email' => $email,
                'telZap' => $telZap,
                'nmAnimal' => $nmAnimal,
                'nmEscola' => $nmEscola
            ];
                    
            if ($stmt->execute($params)) {
                $_SESSION['message'] = "Usuário " . substr($nomeUsuario, 0, 20) . "... foi alterado.";
                $_SESSION['message_type'] = 'success'; // Alerta de sucesso
                header('Location: iniciar.php');
                exit();
            } else {
                $_SESSION['cpf']=$cpf;
                $_SESSION['nomeUsuario']=$nomeUsuario;
                $_SESSION['email']=$email;
                $_SESSION['telZap']=$telZap;
                $_SESSION['nmAnimal']=$nmAnimal;
                $_SESSION['nmEscola']=$nmEscola; 
                echo "<script>
                    alert('Aconteceu erro ao alterar o usuário.');
                    window.location.href = 'cadastro.php';
                </script>";
                exit();
            }
            
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
        }
    }
}

if (isset($_POST['CadArquivo'])) {
    // var_dump($_POST);
    // exit();

    //Diretório base onde as pastas serão criadas
    $baseDir = 'C:/ArquivosFatad/uploads/';
    $idAluno = filter_input(INPUT_POST, 'idAluno', FILTER_SANITIZE_NUMBER_INT);
    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_NUMBER_INT);

    $incluidos=0;
    $nincluidos=0;
    $msg3="";
    $msg4="";

    // Crie a pasta com base no cpfAluno
    $uploadDir = $baseDir . $cpf . '/';

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
                    $sql = "INSERT INTO tb_docalunos (cpf, caminhoNomeArq, nomeArq) VALUES (:cpf, :caminhoNomeArq, :nomeArq)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
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
    header('Location: cadastroListDoc.php?cpf='. $cpf);
     exit();  
}

if (isset($_POST['excluirDocumento'])) {

//     var_dump($_POST);
// exit();

    // Obtenha o ID do documento do POST
    $idArquivo = filter_input(INPUT_POST, 'idArquivo', FILTER_SANITIZE_NUMBER_INT);
    $idAluno = filter_input(INPUT_POST, 'idAluno', FILTER_SANITIZE_NUMBER_INT);
    $cpf = filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $msg="";

    if ($idArquivo === false || $idArquivo === null) {
        $_SESSION['message'] = 'ID do documento inválido';
        header('Location: cadastroListDoc.php?cpf=' . $cpf);
        exit;
    }

    try {
        $pdo = new Config();

        //pegar o caminho e nome do arquivo para excluir fisicamente
        $sql = "Select caminhoNomeArq,nomeArq FROM tb_docalunos WHERE idArquivo = :idArquivo";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idArquivo', $idArquivo, PDO::PARAM_INT);
        // Executa a consulta

        $stmt->execute();
        // Obtém o resultado
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {

            //excluir fisicamente o arquivo
            $file  = $result['caminhoNomeArq'];

            if (file_exists($file)) {

                if (unlink($file)) {
                    $msg = "Arquivo físico excluído!";
                } else {
                    $msg= "Erro ao excluir o arquivo físico. Verifique as permissões.";
                }
            } else {
                $msg= "Arquivo físico não encontrado.";
            }
        } else {
            $msg=  "Nenhum arquivo físico encontrado para o ID informado.";
        }

        // excluir no BD
        $sql = "DELETE FROM tb_docalunos WHERE idArquivo = :idArquivo";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':idArquivo', $idArquivo, PDO::PARAM_INT);

        // Executar a consulta
        if ($stmt->execute()) {
            $_SESSION['message'] = $msg.' Referência no BD excluída.';
            $_SESSION['message_type'] = 'success'; // Alerta de sucesso

        } else {
            $errorInfo = $stmt->errorInfo();
            $_SESSION['message'] = $msg.' FALHOU a exclusão no BD do arquivo: ' . $errorInfo[2].' no BD.';
            $_SESSION['message_type'] = 'danger';

        }

         // Redirecionamento
         if($idAluno==0){
            header('Location: cadastroListDocUsuario.php?cpf=' . $cpf);
         }else{
            header('Location: cadastroListDoc.php?cpf=' . $cpf);
         }

    } catch (PDOException $e) {
        // Debug - Verifique o valor de $idAluno
        $_SESSION['message'] = 'Erro ao redirecionar para cadastroListDoc.php?, cpf=" . $cpf';
        $_SESSION['message_type'] = 'error';   
    }
    exit;
}

if (isset($_POST['excluir3opcoes'])) {
    $pdo = new Config();
    $cpf = $_POST['cpf'] ?? null;
    $msg="";

    //Opção 1 - excluir usuário
    if (isset($_POST['opcoes']) && in_array('opcao1', $_POST['opcoes'])) {
       
        // excluir Aluno
        $sql = "DELETE FROM tb_usuarios WHERE cpfUsuario = :cpf";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cpf', $cpf, PDO::PARAM_INT);
         // Executar a consulta
        if ($stmt->execute()) {
            $msg=$msg."  Usuario de CPF= ". $cpf ." foi excluído."; 
        }else{
            $msg=$msg."  Erro: usuário de CPF= ". $cpf ." NÃO foi excluído."; 
        }
    }

    //Opção 2 - excluir aluno
    if (isset($_POST['opcoes']) && in_array('opcao2', $_POST['opcoes'])) {
        // excluir Aluno
        $sql = "DELETE FROM tb_aluno WHERE cpfAluno = :cpf";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cpf', $cpf, PDO::PARAM_INT);
         // Executar a consulta
        if ($stmt->execute()) {
            $linhasAfetadas = $stmt->rowCount();
            if ($linhasAfetadas > 0) {
                $msg .= " Aluno de CPF= " . $cpf . " foi excluído.";
            } else {
                $msg .= " CPF= " . $cpf . " NÃO constava como aluno.";
            }
        }
    }
    
    //Opção 3 - excluir a pasta de documentos do aluno (aruivo físico)
    if (isset($_POST['opcoes']) && in_array('opcao3', $_POST['opcoes'])) {
        $baseDir = 'C:/ArquivosFatad/uploads/';

        if (!$cpf) {
            $msg=$msg." CPF não informado para exclusão de pasta de documentos.";
        }

        $folder = $baseDir . $cpf . '/';

        clearstatcache();
        
        if (!is_dir($folder)) {
            $msg=$msg."  CPF=  ". $cpf . " NÃO poussiu documentos para excluir.";
        }else{
            // excluir no BD
            $sql = "DELETE FROM tb_docalunos WHERE cpf = :cpf";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':cpf', $cpf, PDO::PARAM_INT);

            // Executar a consulta
            if ($stmt->execute()) {
                $msg=$msg." Referência no BD foi exluída.";
            }else{
                $msg=$msg." NÃO havia referência no BD a ser exluída.";
            }
        }
        $deletePasta = $fg->deleteFolderIterative($folder);
        if (!$deletePasta) {
            $msg=$msg." Pasta excluída ou inexistente.";
        }
    
    }
    $_SESSION['message'] = $msg;
    $_SESSION['message_type'] = 'error';
    header('Location: cadastroListVisitantes.php');
        exit();
} else {
    $_SESSION['message'] = 'Erro fatal.';
    $_SESSION['message_type'] = 'error';
    header('Location: logout.php');
    exit();

}
?>