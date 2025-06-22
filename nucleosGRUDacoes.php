<?php
session_start();
require './conexao.php';
require './config.php';
$pdo = new Config();
$msg="";
// var_dump($_POST);
// exit();

if (isset($_POST['cadastro_nucleo'])) {
//    $nucleo_id = mysqli_real_escape_string($conexao, $_POST['idNucleo']);
    $descNucleo = mysqli_real_escape_string($conn, trim($_POST['descNucleo']));
    $nrNucleo =  mysqli_real_escape_string($conn, trim($_POST['nrNucleo']));
    $perfil = mysqli_real_escape_string($conn, trim($_POST['opcao']));
    $cep = mysqli_real_escape_string($conn, trim($_POST['cep']));
    $numero = mysqli_real_escape_string($conn, trim($_POST['numero']));
    $rua = mysqli_real_escape_string($conn, trim($_POST['rua']));
    $complemento = mysqli_real_escape_string($conn, trim($_POST['complemento']));
    $bairro = mysqli_real_escape_string($conn, trim($_POST['bairro']));
    $cidade = mysqli_real_escape_string($conn, trim($_POST['cidade']));
    $estado = mysqli_real_escape_string($conn, trim($_POST['estado']));
    $cidadeUF = $cidade."-".$estado;
    $enderecoNucleo=$_POST['rua'].", nº ". $_POST['numero']." ".$_POST['complemento'].". ".$_POST['bairro'] ;
    $telZap = mysqli_real_escape_string($conn, trim($_POST['telZap']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $nomeRespNucleo = mysqli_real_escape_string($conn, trim($_POST['nomeRespNucleo']));
    // $cpfResp=mysqli_real_escape_string($conn, trim($_POST['cpfResp']));
    $cpfResp=mysqli_real_escape_string($conn, trim($_POST['cpf']));
    $idSessao = "Nu" . str_pad($nrNucleo, 4, "0", STR_PAD_LEFT); // Formata o número do núcleo
    $varPrivilegio = 'opNuc';
    
    //trocar o perfil de usuário de Visitante para opNuc    

    //Neste local deverá ser colocado um texte para evitar que o mesmo usuário assuma
    //mais de um núcleo, como está ocorrendo agora.
    //Se o Usuário controlar algum núcleo, antes de atribuí-lo a um novo Núcleo é preciso
    //Cortar o vínculo dele com o núcleo atual

    $sql = "SELECT varPrivilegio, idSessao FROM tb_usuarios WHERE cpfUsuario = :cpfUsuario";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':cpfUsuario', $cpfResp);
    $stmt->execute();
    $dadosAtuais = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar se há diferença nos valores enviados
    $atualizar = false;

    if ($dadosAtuais) {
        if ($dadosAtuais['varPrivilegio'] !== $varPrivilegio) $atualizar = true;
        if ($dadosAtuais['idSessao'] !== $idSessao) $atualizar = true;
    } else {
        $_SESSION['message'] = "CPF não encontrado no banco de dados.";
        header('Location: nucleosGRUD.php');
        exit();
    }

    // Realizar o UPDATE somente se necessário
    if ($atualizar) {
        $sql = "UPDATE tb_usuarios SET varPrivilegio = :varPrivilegio, idSessao = :idSessao WHERE cpfUsuario = :cpfUsuario";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':varPrivilegio', $varPrivilegio);
        $stmt->bindParam(':idSessao', $idSessao);
        $stmt->bindParam(':cpfUsuario', $cpfResp);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $msg = "Núcleo com coordenador. ";
        } else {
            $_SESSION['message'] = "FALHA. Usuário não assumiu coordenção.";
            header('Location: nucleosGRUD.php');
            exit();
        }
    } else {
        $msg = "Tabela Usuários não não foi alterada.  ";
    }

    $sqlInsert = "INSERT INTO tb_nucleofatad(descNucleo,nrNucleo,perfil, enderecoNucleo, cidadeUF, cep, nomeRespNucleo,cpfResp, telZap, email) "
                        . "VALUES (:descNucleo,:nrNucleo,:perfil,:enderecoNucleo,:cidadeUF,:cep,:nomeRespNucleo,:cpfResp,:telZap,:email)";
                
    // Preparar a instrução
    $stmt = $pdo->prepare($sqlInsert);

    // Associar os parâmetros
    $stmt->bindParam(':descNucleo', $descNucleo);
    $stmt->bindParam(':nrNucleo', $nrNucleo);
    $stmt->bindParam(':perfil', $perfil);
    $stmt->bindParam(':enderecoNucleo', $enderecoNucleo);
    $stmt->bindParam(':cidadeUF', $cidadeUF);
    $stmt->bindParam(':cep', $cep);
    $stmt->bindParam(':nomeRespNucleo', $nomeRespNucleo);
    $stmt->bindParam(':cpfResp', $cpfResp);
    $stmt->bindParam(':telZap', $telZap);
    $stmt->bindParam(':email', $email);

    // Executar a instrução
    
    $stmt->execute();
    // var_dump($_SESSION);
    // exit();

    $_SESSION['message'] = ($stmt->rowCount() > 0) 
    ? 'Núcleo '.$descNucleo.' cadastrado com sucesso.'
    : 'Erro ao cadastrar o núcleo.';
    header('Location: nucleosGRUD.php');
    exit();
} 
if (isset($_POST['update_nucleo'])) {
    // Pegando os dados e prevenindo contra XSS com trim
    $nucleo_id = trim($_POST['idNucleo']);
    $descNucleo = trim($_POST['descNucleo']);
    $nrNucleo = trim($_POST['nrNucleo']);
    $perfil = trim($_POST['opcao']);
    $cep = trim($_POST['cep']);
    $cidadeUF = trim($_POST['cidadeUF']);
    $txtEndereco = trim($_POST['enderecoNucleo']);
    $telZap = trim($_POST['telZap']);
    $email = trim($_POST['email']);
    $nomeRespNucleo = trim($_POST['nomeRespNucleo']);
    $cpfResp = trim($_POST['cpf']);
    
    $idSessao = "Nu" . str_pad($nrNucleo, 4, "0", STR_PAD_LEFT); // Formata o número do núcleo
    $varPrivilegio = 'opNuc';
    $msg = "";

    try {

       
        // Atualizando tb_usuarios
       // Verificar os valores atuais no banco de dados
        $sql = "SELECT varPrivilegio, idSessao FROM tb_usuarios WHERE cpfUsuario = :cpfUsuario";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':cpfUsuario', $cpfResp);
        $stmt->execute();
        $dadosAtuais = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar se há diferença nos valores enviados
        $atualizar = false;

        if ($dadosAtuais) {
            if ($dadosAtuais['varPrivilegio'] !== $varPrivilegio) $atualizar = true;
            if ($dadosAtuais['idSessao'] !== $idSessao) $atualizar = true;
        } else {
            $_SESSION['message'] = "CPF não encontrado no banco de dados.";
            header('Location: nucleosGRUD.php');
            exit();
        }
        
        // Realizar o UPDATE somente se necessário
        if ($atualizar) {
            $sql = "UPDATE tb_usuarios SET varPrivilegio = :varPrivilegio, idSessao = :idSessao WHERE cpfUsuario = :cpfUsuario";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':varPrivilegio', $varPrivilegio);
            $stmt->bindParam(':idSessao', $idSessao);
            $stmt->bindParam(':cpfUsuario', $cpfResp);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $msg = "Privilégio do Usuário foi atualizado. ";
            } else {
                $_SESSION['message'] = "FALHOU a atualização do usuário";
                header('Location: nucleosGRUD.php');
                exit();
            }
        } else {
            $msg = "Tabela Usuários não sofreu alteração.  ";
        }


                // Atualizando tb_nucleofatad
                $sql = "UPDATE tb_nucleofatad SET 
                            descNucleo = :descNucleo,
                            perfil = :perfil,
                            enderecoNucleo = :enderecoNucleo,
                            cidadeUF = :cidadeUF,
                            cep = :cep,
                            nomeRespNucleo = :nomeRespNucleo,
                            cpfResp = :cpfResp,
                            telZap = :telZap,
                            email = :email,
                            nrNucleo = :nrNucleo
                        WHERE idNucleo = :idNucleo";
                        
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':descNucleo', $descNucleo);
                $stmt->bindParam(':perfil', $perfil);
                $stmt->bindParam(':enderecoNucleo', $txtEndereco);
                $stmt->bindParam(':cidadeUF', $cidadeUF);
                $stmt->bindParam(':cep', $cep);
                $stmt->bindParam(':nomeRespNucleo', $nomeRespNucleo);
                $stmt->bindParam(':cpfResp', $cpfResp);
                $stmt->bindParam(':telZap', $telZap);
                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':nrNucleo', $nrNucleo);
                $stmt->bindParam(':idNucleo', $nucleo_id);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    $_SESSION['message'] = $msg . "Núcleo " . $descNucleo . " atualizado com sucesso";
                } else {
                    $_SESSION['message'] = $msg . "Núcleo " . $descNucleo . " não foi atualizado";
                }
                header('Location: nucleosGRUD.php');
                exit();
                
            } catch (PDOException $e) {
                // Tratamento de erro
                $_SESSION['message'] = "Erro: " . $e->getMessage();
                header('Location: nucleosGRUD.php');
                exit();
            }
        }
    
if (isset($_POST['CadArquivo'])) {

 // Receber os dados do formulário
// var_dump($_POST);
// exit();
    // Diretório base onde as pastas serão criadas
    $baseDir = 'C:/ArquivosFatad/uploads/Nucleos/';
    $idNucleo = filter_input(INPUT_POST, 'idNucleo', FILTER_SANITIZE_NUMBER_INT);

    $incluidos=0;
    $nincluidos=0;
    $msg3="";
    $msg4="";

    // Crie a pasta com base no cpfAluno
    $uploadDir = $baseDir . $idNucleo . '/';

    // Verifique se o diretório existe, caso contrário, crie-o
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Processar múltiplos arquivos
    $files = $_FILES['files'];
    $numFiles = count($files['name']);

    for ($i = 0; $i < $numFiles; $i++) {
        $fileName = basename($files['name'][$i]);
        $targetFilePath = $uploadDir . $fileName;

        // Verifique se o arquivo foi enviado corretamente
        if (move_uploaded_file($files['tmp_name'][$i], $targetFilePath)) {


            try {
                $pdo = new Config();
                $idAluno = filter_input(INPUT_POST, 'idAluno', FILTER_SANITIZE_NUMBER_INT);
            
                $sql = "INSERT INTO tb_docnucleos (idNucleo, caminhoNomeArq, nomeArq) VALUES (:idNucleo, :caminhoNomeArq, :nomeArq)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':idNucleo', $idNucleo, PDO::PARAM_INT);
                $stmt->bindParam(':caminhoNomeArq', $targetFilePath, PDO::PARAM_STR);
                $stmt->bindParam(':nomeArq', $fileName, PDO::PARAM_STR);
            
                if ($stmt->execute()) {
                    $msg3= "Banco de Dados OK!";
                } else {
                    $errorInfo = $stmt->errorInfo();
                    $msg4= "Erro ao inserir no Banco: " . $errorInfo[2];
                }
            
                // Fechar a conexão
                $pdo = null;
            
            } catch (PDOException $e) {
                $msg4="Erro: " . $e->getMessage();
            }

            $incluidos++;
        } else {
            $nincluidos++;
        }
         if($nincluidos>0){$msg2=$nincluidos.' rejeitado(s).'; }else{$msg2="";}
    }
    $pdo=null;
    $_SESSION['message'] = $incluidos.' arquivo(s) copiado(s).  '.  $msg3.' '.$msg2;
		header('Location: nucleosGRUDlistDoc.php?idNucleo='. $idNucleo);

} else {
    $pdo=null;
    $_SESSION['message'] = $nincluidos.' não copiado(s).';
		header('Location: nucleosGRUDlistDoc.php?idNucleo='. $idNucleo);
}


if (isset($_POST['excluirdocumento'])) {
    // Obtenha o ID do documento do POST
    $idArquivo = filter_input(INPUT_POST, 'idArquivo', FILTER_SANITIZE_NUMBER_INT);
    $idNucleo = filter_input(INPUT_POST, 'idNucleo', FILTER_SANITIZE_NUMBER_INT);


    if ($idArquivo === false || $idArquivo === null) {
        $_SESSION['message'] = 'ID do documento inválido';
        header('Location: nucleosGRUDlistDoc.php?idnucleo=' . $idNucleo);
        exit;
    }

    try {
        $pdo = new Config();

        // Preparar a consulta de exclusão
        $sql = "DELETE FROM tb_docnucleos WHERE idArquivo = :idArquivo";
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
         header('Location: nucleosGRUDlistDoc.php?idNucleo=' . $idNucleo);
         $pdo=null;
         exit;

    } catch (PDOException $e) {


        // Debug - Verifique o valor de $idAluno
        $pdo=null;
        echo "Erro ao redirecionar para nucleosGRUDlistDoc.php?idNucleo=" . $idNucleo;
        exit;


    }
}
?>
