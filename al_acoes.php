<?php
session_start();
include_once './config.php';
include_once './conexao.php';

 include_once './fatadgestaoControler.php';
 $fg=new fatadgestaoControler;

if (isset($_SESSION['privilegio'])) { 
    $privilegio = $_SESSION['privilegio'];
    $usuario=$_SESSION['usuario']; 
}
if (isset($_POST['cadastro_aluno'])) {
//       var_dump($_POST);
//  var_dump($_SESSION);
//    exit();
    // Obtenha os dados do formulário
    $cpfAluno = filter_input(INPUT_POST, 'cpfAluno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nomeAluno = filter_input(INPUT_POST, 'nomeAluno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nomeAluno = html_entity_decode($nomeAluno, ENT_QUOTES, 'UTF-8');

    $cep = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $numero = mysqli_real_escape_string($conn, trim($_POST['numero']));
    $rua = mysqli_real_escape_string($conn, trim($_POST['rua']));
    $complemento = mysqli_real_escape_string($conn, trim($_POST['complemento']));
    $bairro = mysqli_real_escape_string($conn, trim($_POST['bairro']));
    $cidade = mysqli_real_escape_string($conn, trim($_POST['cidade']));
    $estado = mysqli_real_escape_string($conn, trim($_POST['estado']));
    $cidade = $cidade."-".$estado;

    $txtEndereco=$_POST['rua'].", nº ". $_POST['numero']." ".$_POST['complemento']." - ".$_POST['bairro'] ;
    $txtEndereco = html_entity_decode($txtEndereco, ENT_QUOTES, 'UTF-8');

    $cidadeNatAluno = mysqli_real_escape_string($conn, trim($_POST['cidadeNatal']));
    $dtNascAluno = filter_input(INPUT_POST, 'dtNasc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    $nomePaiAluno = filter_input(INPUT_POST, 'nomePai', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nomePaiAluno = html_entity_decode($nomePaiAluno, ENT_QUOTES, 'UTF-8');

    $nomeMaeAluno = filter_input(INPUT_POST, 'nomeMae', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nomeMaeAluno = html_entity_decode($nomeMaeAluno, ENT_QUOTES, 'UTF-8');

    $idtAluno = filter_input(INPUT_POST, 'idtAluno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $emailAluno = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $telZapAluno = filter_input(INPUT_POST, 'telZap', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // $cidade = $cidade . "-" . $estado;
    // $txtEndereco = $rua . ", nº " . $numero . " " . $complemento . " - " . $bairro;

    try {
        $pdo = new Config();

        // Verifique se o CPF já existe
        //Esta função formata o cpf como 999.999.999-99
        //$cpfAluno=$fg->formatCnpjCpf($cpfAluno);

        $query = "SELECT * FROM tb_aluno WHERE cpfAluno = :cpfAluno";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':cpfAluno', $cpfAluno, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $_SESSION['message'] = "CPF: " . $cpfAluno . ' já existe. Aluno não foi cadastrado';
            $_SESSION['message_type'] = 'danger'; // Alerta de fracasso
            header('Location: al_dados_gerais.php');
            exit;
        } else {
            // Insira os dados do aluno
            $sql = "INSERT INTO tb_aluno (cpfAluno, nomeAluno, enderecoAluno, cidadeMoradia, cep, 
                     cidadeNatAluno, dtNascAluno, nomePaiAluno, nomeMaeAluno, idtAluno, emailAluno, telZapAluno, idCadastro)
                    VALUES (:cpfAluno, :nomeAluno, :enderecoAluno, :cidadeMoradia, :cep, 
                    :cidadeNatAluno, :dtNascAluno, :nomePaiAluno, :nomeMaeAluno, :idtAluno, :emailAluno, :telZapAluno, :idCadastro)";
            
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

            if ($stmt->execute()) {
                $_SESSION['message'] = 'Aluno ' . $nomeAluno . ' cadastrado com sucesso';
             $_SESSION['message_type'] = 'danger'; // Alerta de sucesso
            } else {
                $_SESSION['message'] = 'Aluno ' . $nomeAluno . ' não foi cadastrado';
                $_SESSION['message_type'] = 'danger'; // Alerta de sucesso
            }
            header('Location: al_dados_gerais.php');
        }

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}

if (isset($_POST['update_aluno'])) {
    // var_dump($_POST);
    // var_dump($_SESSION);
    // exit();
    // Obtenha os dados do formulário
    $aluno_id = filter_input(INPUT_POST, 'idAluno', FILTER_SANITIZE_NUMBER_INT);
    $cpfAluno = filter_input(INPUT_POST, 'cpfAluno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nomeAluno = filter_input(INPUT_POST, 'nomeAluno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $nomeAluno = html_entity_decode($nomeAluno, ENT_QUOTES, 'UTF-8');
    $cep = filter_input(INPUT_POST, 'cep', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $txtEndereco = mysqli_real_escape_string($conn, trim($_POST['enderecoAluno']));
    $cidadeMoradia = mysqli_real_escape_string($conn, trim($_POST['cidadeUF']));
    $cidadeNatAluno = mysqli_real_escape_string($conn, trim($_POST['cidadeNatal']));
    $dtNascAluno = filter_input(INPUT_POST, 'dtNasc', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $nomePaiAluno = filter_input(INPUT_POST, 'nomePai', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $nomePaiAluno = html_entity_decode($nomePaiAluno, ENT_QUOTES, 'UTF-8');
    $nomeMaeAluno = filter_input(INPUT_POST, 'nomeMae', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $nomeMaeAluno = html_entity_decode($nomeMaeAluno, ENT_QUOTES, 'UTF-8');
    $idtAluno = filter_input(INPUT_POST, 'idtAluno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $emailAluno = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $telZapAluno = filter_input(INPUT_POST, 'telZap', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

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
                telZapAluno = :telZapAluno
                WHERE cpfAluno = :cpfAluno";

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

        if ($stmt->execute()) {
            $_SESSION['message'] = 'Aluno ' . $nomeAluno . ' atualizado com sucesso';
        } else {
            $_SESSION['message'] = 'Aluno ' . $nomeAluno . ' não foi atualizado';
        }

        // Fechar a conexão
        $pdo = null;
        header('Location: al_dados_gerais.php');
        exit;

    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pdo=new config;
    $senhaAtual = $_POST['senhaAtual'];
    $novaSenha = $_POST['novaSenha'];
    $confirmaSenha = $_POST['confirmaSenha'];

    if ($novaSenha !== $confirmaSenha) {
        $_SESSION['message'] = 'A nova senha e a confirmação não coincidem.';
        $pdo=null;
        header("Location: al_troca_senha.php");
        exit();
    }

    $userId = $_SESSION['usuario']; // Supondo que o ID do usuário está armazenado na sessão
    $sql = "SELECT varPalavraBase FROM tb_usuarios WHERE cpfUsuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $userId);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar se a senha atual está no formato MD5 ou usando password_hash
    if (strlen($user['varPalavraBase']) == 32) {
        // Verificar a senha no formato MD5
        if (md5($senhaAtual) !== $user['varPalavraBase']) {
            $_SESSION['message'] = 'A senha atual está incorreta.';
            $pdo=null;
            header("Location: al_troca_senha.php");
            exit();
        }
    } else {
        // Verificar a senha no formato password_hash
        if (!password_verify($senhaAtual, $user['varPalavraBase'])) {
            $_SESSION['message'] = 'A senha atual está incorreta.';
            $pdo=null;
            header("Location: al_troca_senha.php");
            exit();
        }
    }

    // Atualizar a senha para o novo formato usando password_hash
    $novaSenhaHash = password_hash($novaSenha, PASSWORD_DEFAULT);
    $sql = "UPDATE tb_usuarios SET varPalavraBase = :senha WHERE cpfUsuario = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':senha', $novaSenhaHash);
    $stmt->bindParam(':id', $userId);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Senha atualizada com sucesso!';
    } else {
        $_SESSION['message'] = 'Erro ao atualizar a senha. Tente novamente.';
    }
    $pdo=null;
    header("Location: al_troca_senha.php");
    exit();
}
?>


