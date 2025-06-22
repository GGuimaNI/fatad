<?php
session_start(); 
// Incluir a conexão com o banco de dados
include_once 'config.php';
include_once './fatadgestaoControler.php';

$fg = new fatadgestaoControler;
$pdo = new Config();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario_id = filter_input(INPUT_POST, 'usuario', FILTER_SANITIZE_NUMBER_INT);
    $varPrivilegio = filter_input(INPUT_POST, 'varPrivilegio', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $idNucleo = filter_input(INPUT_POST, 'idNucleo', FILTER_SANITIZE_NUMBER_INT);
    $retorno = filter_input(INPUT_POST, 'retorno', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $msg1="";
    $msg2="";
    $descNucleo="";
    
    var_dump($varPrivilegio);
    var_dump($idNucleo);
    var_dump($_POST);
    //exit();

    try {
        // Iniciar transação
        $pdo->beginTransaction();
        
        // Tratamento para usuário
        if ($usuario_id && $varPrivilegio) {
            if ($varPrivilegio === 'opNuc' && $idNucleo) {
                $nrNucleo = $fg->findNrNucleo($idNucleo);
                $idSessao = "Nu" . substr("0000000" . $nrNucleo, -4);
                $query_usuario = "UPDATE tb_usuarios SET varPrivilegio = :varPrivilegio, idSessao = :idSessao WHERE idUsuario = :idUsuario";
                $stmt = $pdo->prepare($query_usuario);
                $stmt->bindParam(':varPrivilegio', $varPrivilegio, PDO::PARAM_STR);
                $stmt->bindParam(':idSessao', $idSessao, PDO::PARAM_STR);
                $stmt->bindParam(':idUsuario', $usuario_id, PDO::PARAM_INT);
               
                if (!$stmt->execute()) {
                    throw new Exception("Erro ao atualizar usuário.");
                }else{ 
                    $msg1=$usuario_id." passou para nível de acesso: ".$varPrivilegio.", ";
                }

                // Tratamento para Núcleo
                $cpfNome = $fg->findCPFusuario($usuario_id);
                $cpfResp=$cpfNome['cpfUsuario'];
                $nomeUsuario=$cpfNome['nomeUsuario'];
                $query_nucleo = "UPDATE tb_nucleofatad SET cpfResp = :cpfResp,nomeRespNucleo=:nomeResp
                 WHERE idNucleo = :idNucleo";
                $stmt = $pdo->prepare($query_nucleo);
                $stmt->bindParam(':cpfResp', $cpfResp, PDO::PARAM_STR);
                $stmt->bindParam(':nomeResp', $nomeUsuario, PDO::PARAM_STR);
                $stmt->bindParam(':idNucleo', $idNucleo, PDO::PARAM_INT);

                if (!$stmt->execute()) {
                    throw new Exception("Erro ao atualizar núcleo.");
                }else{
                    $msg2="assumindo coordenaçao de núcleo.";
                }
            } else {
                //Com o $idUsuario, buscar o cpfUsuario.
                $cpfUsu=$fg->findCPFusuario($usuario_id);
                $cpfUsuario=$cpfUsu['cpfUsuario'];
                $nomeUsuario=$cpfUsu['nomeUsuario'];
                //com o cpfUsuario, buscar em tb_núcleofatad se o cpfResp=cpfUsuario
                $idNucUsu=$fg->findNucleoCpf($cpfUsuario);

                if($idNucUsu){  
                    foreach($idNucUsu as $row){
                        $idNucleoUsuario=$row->idNucleo;
                        $descNucleo=$row->descNucleo;
                        break;
                    }
                    //Se sim, alterar cpfResp para ___ e nomeRespNucleo para 'Sem coordenador'
                    $cpfResp="000.000.000-00";
                    $semCoord="Sem coordenador";
                    // Tratamento para Núcleo
                    $query_nucleo = "UPDATE tb_nucleofatad SET cpfResp = :cpfResp,nomeRespNucleo=:nomeResp
                    WHERE idNucleo = :idNucleo";
                    $stmt = $pdo->prepare($query_nucleo);
                    $stmt->bindParam(':cpfResp', $cpfResp, PDO::PARAM_STR);
                    $stmt->bindParam(':nomeResp', $semCoord, PDO::PARAM_STR);
                    $stmt->bindParam(':idNucleo', $idNucleoUsuario, PDO::PARAM_INT);
                
                    if (!$stmt->execute()) {
                        throw new Exception("Erro ao atualizar núcleo.");
                    }else{
                        $msg2="com perda de coordenaçdo do núcleo ". $descNucleo;
                    }
                }else{
                    $msg2="";
                }
                //fim do tratamento para núcleo

                //início tratamento para usuário
                $idSessao = $varPrivilegio;
                $query_usuario = "UPDATE tb_usuarios SET varPrivilegio = :varPrivilegio, idSessao = :idSessao WHERE idUsuario = :idUsuario";
                $stmt = $pdo->prepare($query_usuario);
                $stmt->bindParam(':varPrivilegio', $varPrivilegio, PDO::PARAM_STR);
                $stmt->bindParam(':idSessao', $idSessao, PDO::PARAM_STR);
                $stmt->bindParam(':idUsuario', $usuario_id, PDO::PARAM_INT);

                if (!$stmt->execute()) {
                    throw new Exception("Erro ao atualizar usuário.");
                }else{
                    $msg1=$nomeUsuario." passou para nível de acesso: ".$varPrivilegio.", ";
                }
            }

            // Confirmar transação
            $pdo->commit();

            $_SESSION['message'] = $msg1.$msg2;
            if($retorno=="ListVisitantes"){
                //Retorno é um boleano definido em iniciarAcesso 
                //e significa que a chamada veio de  cadastroListVisitantes.php
                header('Location: iniciarGeral.php'); 
            }else{
                header('Location: iniciarGeral.php'); 
            }
        } else {
            throw new Exception("Dados inválidos.");
        }
    } catch (Exception $e) {
        // Reverter transação
        $pdo->rollBack();
        $_SESSION['message'] = 'Erro: ' . $e->getMessage();

        if($retorno){
            //Retorno é um boleano definido em iniciarAcesso 
            //e significa que a chamada veio de  cadastroListVisitantes.php
            header('Location: iniciarGeral.php'); 
        }else{
            header('Location: iniciarGeral.php'); 
        }

    }
}

?>
