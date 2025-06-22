<?php
session_start();
require './conexao.php';
include_once './fatadgestaoControler.php';
$fg=new fatadgestaoControler;
$fgN=new fatadgestaoControler;
$idNucleo = 0;
$descNucleo = "";

if (isset($_SESSION['usuario_autenticado'])) { 
    $privilegio = $_SESSION['privilegio'];
    $usuario=$_SESSION['usuario']; 
    // Adicione lógica baseada no privilégio do usuário 
    if($privilegio=="opFatad"){
        include('./index.html');
    }elseif($privilegio=="admFatad"){
        include('./index.html');
    }elseif($privilegio=="opNuc"){
        include('./barOpNuc.php');
    }elseif($privilegio=="opAluno"){
        include('./barOpAluno.php');
    }elseif($privilegio=="Visitante"){
        include('./barVisitante.html');    
    } else { 
    echo 'Sessão não iniciada ou privilégio não definido.'; 
    // Redirecionar para a página de login ou mostrar uma mensagem de erro 
    header('Location: login.php'); exit(); 
  }
}
?>   

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Aluno - Editar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <br>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 style="color: blue;">Editar Aluno
                        <a href="alunosGRUD.php" class="btn btn-danger float-end">Voltar</a>
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php
                        if (isset($_GET['idAluno'])) {
                            $aluno_id = mysqli_real_escape_string($conn, $_GET['idAluno']);
                            $sql = "SELECT *,(SELECT descNucleo FROM tb_nucleofatad 
                            WHERE tb_nucleofatad.idNucleo=tb_aluno.idCadastro)as descNucleo 
                            FROM tb_aluno WHERE idAluno='$aluno_id'";

                            $query = mysqli_query($conn, $sql);
                            if (mysqli_num_rows($query) > 0) {
                              $aluno = mysqli_fetch_array($query);

                        
                        ?>
                        <form action="alunosGRUDacoes.php" method="POST">

                        <?php 
                        if($privilegio=="opFatad"){ 
                        ?>
                            <div class="mb-3">
                                <label>Vínculo do Aluno:</label>
                                <select name="nucleo">
                                    <?php

                                    $todosNucleos = $fg->findNucleo();
                                    $idNucleo=$aluno['idCadastro'];
                                    foreach ($todosNucleos as $nucleoItem) {
                                        $selected = ($nucleoItem->idNucleo == $idNucleo) ? 'selected' : '';
                                        echo "<option value='{$nucleoItem->idNucleo}' {$selected}>{$nucleoItem->descNucleo}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                        <?php }?>

                            <div class="mb-3">
                              <label>Nome</label>
                              <input type="text" name="nomeAluno" value="<?= isset($aluno['nomeAluno']) ? htmlspecialchars($aluno['nomeAluno']) : '' ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>CPF:</label>
                                <input type="number" required="" placeholder="Apenas números" name="cpfAluno" id="cpfAluno" value="<?= isset($aluno['cpfAluno']) ? htmlspecialchars($aluno['cpfAluno']) : '' ?>" class="form-control">
                            </div> 
                            <div class="mb-3">
                                <label>Identidade:</label>
                                <input type="text" required="" placeholder="Apenas números" name="idtAluno" id="idtAluno" value="<?= isset($aluno['idtAluno']) ? htmlspecialchars($aluno['idtAluno']) : '' ?>" class="form-control">
                            </div>   

                            <div class="mb-3">
                                <label>Natural de:</label>
                                <input type="text" placeholder="Insira Cidade-UF" required="" name="cidadeNatal" id="cidadeNatal" value="<?= isset($aluno['cidadeNatAluno']) ? htmlspecialchars($aluno['cidadeNatAluno']) : '' ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Data de Nascimento:</label>
                                <input type="date" required="" placeholder="dd/mm/AAAA" name="dtNasc" id="dtNasc" value="<?=date('Y-m-d', strtotime($aluno['dtNascAluno']))?>" class="form-control">
                            </div>  
                            <div class="mb-3">
                                <label>Nome Pai:</label>
                                <input type="text" required="" placeholder="Nome do pai completo" name="nomePai" id="nomePaiAluno" value="<?= isset($aluno['nomePaiAluno']) ? htmlspecialchars($aluno['nomePaiAluno']) : '' ?>" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Nome Mãe:</label>
                                <input type="text" required="" placeholder="Nome da mãe completo" name="nomeMae" id="nomeMaeAluno" value="<?= isset($aluno['nomeMaeAluno']) ? htmlspecialchars($aluno['nomeMaeAluno']) : '' ?>" class="form-control">
                            </div> 
                            <div class="mb-3">
                                <label>E-mail:</label>
                                <input type="email" required="" placeholder="Melhor e-mail" name="emailAluno" id="emailAluno" value="<?= isset($aluno['emailAluno']) ? htmlspecialchars($aluno['emailAluno']) : '' ?>" class="form-control">
                            </div>  
                            <div class="mb-3">
                                <label>Telefone:</label>
                                <input type="number" required="" placeholder="Apenas números, com DDD" name="telZap" id="telZap" value="<?= isset($aluno['telZapAluno']) ? htmlspecialchars($aluno['telZapAluno']) : '' ?>" class="form-control">
                             </div> 

                            <div class="mb-3">
                                <label class="form-label">CEP:</label>
                                <input type="text" required="" value="<?= isset($aluno['cep']) ? htmlspecialchars($aluno['cep']) : '' ?>" name="cep" id="cep" class="form-control" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Endereço:</label>
                                <input type="text" required="" name="enderecoAluno" id="enderecoAluno" value="<?= isset($aluno['enderecoAluno']) ? htmlspecialchars($aluno['enderecoAluno']) : '' ?>"  class="form-control">
                            </div>
                           
                            <div class="mb-3">
                                <label class="form-label">Cidade-UF:</label>
                                <input type="text" name="cidadeUF" id="cidadeUF" value="<?= isset($aluno['cidadeMoradia']) ? htmlspecialchars($aluno['cidadeMoradia']) : '' ?>" class="form-control">
                            </div>
                           
                           <input type="hidden" id="idAluno" name="idAluno" value="<?=$aluno['idAluno']?>">

                            <div class="mb-3">
                              <button type="submit" name="update_aluno" class="btn btn-primary">Salvar</button>
                            </div>

    
                        </form>
                        <?php
                        } else {
                            echo "<h5>Usuário não encontrado</h5>";
                          }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function buscaCep(cep){
            fetch('https://viacep.com.br/ws/'+cep+'/json/')
            .then(response => {
               if(!response.ok){
                        console.log("erro de conexao");
                        return;
               }
               return response.json();
            })
           .then(data => {
                   console.log(data);
                   txtRua.value = data.logradouro;
           })
           .catch(error => {
               console.log("Erro: ", error);
           });                         
       }
    </script>
    <script src="js/code.jquery.com_jquery-3.7.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"
            integrity="sha512-d4KkQohk+HswGs6A1d6Gak6Bb9rMWtxjOa0IiY49Q3TeFd5xAzjWXDCBW9RS7m86FQ4RzM2BdHmdJnnKRYknxw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="js/cep.js"></script>
  </body>
</html>