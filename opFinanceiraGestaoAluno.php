<?php
session_start();
require './conexao.php';

include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;

if (isset($_SESSION['usuario_autenticado'])) { 
  $privilegio = $_SESSION['privilegio'];
  $usuario=$_SESSION['usuario']; 
  $idSessao=$_SESSION['idSessao'];

  // Adicione lógica baseada no privilégio do usuário 
  if($privilegio=="opAluno"){
     include('./barOpAluno.php');
  }
} else { 
  echo 'Sessão não iniciada ou privilégio não definido.'; 
  // Redirecionar para a página de login ou mostrar uma mensagem de erro 
  header('Location: logout.php'); exit(); 
} 
// var_dump($_SESSION);
// var_dump($_GET);
// var_dump($_POST);
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Receitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style></style>
  </head>
  
  <body>
 
    <br><br>
    <div class="container mt-4">
      <?php include('mensagem.php'); ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                <tr>
                  <td>
                  <?php $telZap = '+5561999962550'; ?>
                    <p style="font-size: 1.3em; color: blue;">
                  Se precisar enviar algum recibo avulso, utilize o número +55 (61) 9 9996-2550 clicando no botão
                  <button class="whatsapp-button" onclick="window.open('https://api.whatsapp.com/send?phone=<?php echo $telZap; ?>&text=Envio de recibo para a FATAD.  Certifique-se de que o fato está corretamente documentado!', '_blank')">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
                  </button>.
                </p>
   
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Data</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                  </tr>
                </thead>
                <tbody>
                  <?php
                    
//                <!--Início de tratamento para alunos-->
                  $cpf = preg_replace('/[^0-9]/', '', $usuario);

                  $sql = "SELECT * FROM qry_opfinanceirogestaoaluno "
                              . "WHERE cpfAluno =".$cpf;

                  $alunos = mysqli_query($conn, $sql);
                  if (mysqli_num_rows($alunos) > 0) {
                    foreach($alunos as $aluno) {
                  ?>
                  <tr>
                    <td><?=date('d/m/Y', strtotime($aluno['dtContrato']))?></td>
                    <td><?=$aluno['descOp']?></td>                             
                    <td>
                      
                      <a href="opFinanceiraVisualizar.php?
                         idOp=<?=$aluno['idOp']?>
                         &perfil=<?=$aluno['perfil']?>"  
                         class="btn btn-secondary btn-sm"><span class="bi-eye-fill"></i></span>&nbsp;Visualisar Operação</a>
                      <?php 
//                        $dtPagOk=date('d/m/Y', strtotime($disciplina['dtPagamento']));

//                            if($disciplina['idOp']>0){
                            if (is_null($aluno['dtPagamento'])
                               || $aluno['dtPagamento']=='0000-00-00'
                               || $aluno['dtPagamento']=='' ){                                 
                             
                            } else { ?>
                            <a href="rptOpFinanceirasReciboNucleo.php?
                               idOp=<?=$aluno['idOp']?> 
                               &perfil=<?=$aluno['perfil']?>"
                               class="btn btn-outline-primary" btn-sm"><i class="bi bi-printer"></i></span>&nbsp;&nbsp;Recibo&nbsp;</a>
                             <?php
                            }
                ?>
                    </td>
                  </tr>
                  <?php
                  }
                  }  
                
                 ?> 
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal -->
<div class="modal fade" id="meuModal" tabindex="-1" aria-labelledby="meuModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 style="color: red;" class="modal-title" id="meuModalLabel">Aviso de Débito</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Até o presente momento esta operação consta como em débito.  Caso já tenha sido quitado, favor comunicar à FATAD.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <!-- <button type="button" class="btn btn-primary">Salvar mudanças</button> -->
            </div>
        </div>
    </div>
</div>
<style>
.whatsapp-button {
  background-color: transparent;
  border: none;
  cursor: pointer;
  vertical-align: middle;
  padding: 0;
}

.whatsapp-button img {
  width: 40px;
  height: 40px;
  vertical-align: middle;
  transition: transform 0.2s ease;
}

.whatsapp-button img:hover {
  transform: scale(1.15);
}
</style>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
   <script src="js/code.jquery.com_jquery-3.7.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"
            integrity="sha512-d4KkQohk+HswGs6A1d6Gak6Bb9rMWtxjOa0IiY49Q3TeFd5xAzjWXDCBW9RS7m86FQ4RzM2BdHmdJnnKRYknxw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  </body>
</html>