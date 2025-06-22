<?php
session_start();
require './config.php';
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$idAluno=0;

 //para evitar que a página recarrege do cache.
 //Esta página recarrega a cada 5 min (linha 45)
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache"); 
header("Expires: Sat, 1 Jan 2000 00:00:00 GMT");

if (isset($_SESSION['usuario_autenticado'])) { 
    $privilegio = $_SESSION['privilegio'];
    $usuario=$_SESSION['usuario']; 
    // Adicione lógica baseada no privilégio do usuário 
    if($privilegio=="opFatad"){
        include('./index.html');
    }elseif($privilegio=="admFatad"){
        include('./index.html');
    }else { 
    echo 'Sessão não iniciada ou privilégio não definido.'; 
    // Redirecionar para a página de login ou mostrar uma mensagem de erro 
    header('Location: logout.php'); exit(); 
  }
}
?>

<!doctype html>
<html lang="pt-BR">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visitantes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- //para recarregar a cada 5 min -->
    <meta http-equiv="refresh" content="300">
  </head>
  
  <body>
    <div class="container mt-12">
      <br><br><br>
      <?php
      if (isset($_SESSION['message'])) {
          echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
          unset($_SESSION['message']); // Limpa a mensagem após exibição
      }

      $usuarios = $fg->findListVisitantes();           
       if (count($usuarios) > 0) {
      ?>  
          
      <div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 style="color: blue;">Lista de Visitantes aguardando moderação</h5>
        <!-- <input type="text" id="filtroTabela" class="form-control w-25" placeholder="Filtrar Nome..."> -->
      </div>
      <div class="card-body">
        <table class="table table-bordered table-striped">
          <thead>
            <tr>
              <th>ID</th>
              <th>Nome</th>
              <th>Telefone</th>
              <th>Editar</th>
              <th>Outras Ações</th>
            </tr>
          </thead>
          <tbody id="conteudoTabela">
            <?php foreach($usuarios as $usuario): 
              $telZap = $usuario['telZapUsuario'];
              $cpf = $usuario['cpfUsuario'];
            ?>
              <tr>
                <td><?= $usuario['idUsuario'] ?></td>
                <td><?= $usuario['nomeUsuario'] ?></td>
                <td>
                  <?= $telZap ?>
                  <button class="whatsapp-button" onclick="window.open('https://api.whatsapp.com/send?phone=<?= $telZap ?>&text=Olá, gostaria de mais informações sobre os cursos FATAD!', '_blank')">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
                  </button>
                </td>
                <td>
                  <a href="cadastroUsuarioEditFatad.php?cpf=<?= $cpf ?>" class="btn btn-success btn-sm"><span class="bi-pencil-fill"></span>Usuario</a>
                  <a href="cadastroAlunoEditFatad.php?cpfUsuario=<?= $cpf ?>" class="btn btn-success btn-sm"><span class="bi-pencil-fill"></span>Aluno</a>
                </td>
                <td>
                  <a href="cadastroListDocUsuario.php?cpf=<?= $cpf ?>" class="btn btn-outline-warning"><span class="bi bi-archive"></span>Documentos</a>
                  <a href="cadastroMatricularAluno.php?cpf=<?= $cpf ?>" class="btn btn-outline-info"><span class="bi bi-box-arrow-in-left"></span>Matricular</a>
                  <a href="iniciarAcesso.php?cpf=<?= $cpf ?>" class="btn btn-outline-info"><span class="bi bi-card-checklist"></span>Concluir</a>
                  <a href="cadastroExcluir.php?cpf=<?= $cpf ?>" class="btn btn-outline-danger"><span class="bi bi-box-arrow-right"></span>Excluir</a>
                </td>
              </tr>
            <?php endforeach; }?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div><br>


      <!-- Pedido de Apostilas  xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-->

      <?php
      $usuarios = $fg->findListOpFinanceira("P");
      if (count($usuarios) > 0) {
       ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 style="color: blue;">Pedidos de Apostilas</h5>
              <!-- //A linha abaixo está comentada porque não está funcionando bem.
              //<input type="text" id="filtroTabela" class="form-control w-25" placeholder="Filtrar Nome..."> -->
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Material Solicitado</th>                    
                    <th>Nome Aluno</th>
                    <th>Telefone</th>

                    <th>Ação</th>
                  </tr>
                </thead>
                <tbody id="conteudoTabela">
                  <?php
                  // Carrega os registros ao abrir a página
                  //  $visitantes = [];                 
                  if($privilegio=="opFatad" || $privilegio=="admFatad" ){
                    
                  ///Resolver isto depois
                  }
                  // $usuarios = $fg->findListOpFinanceiraP();
                
                 if (!empty($usuarios) && is_array($usuarios)) {
                      foreach($usuarios as $usuario) {
                      $telZap=$usuario['telZapAluno'];//para mostrar o botão do WhatsUp
                      $cpf=$usuario['cpfAluno'];
                  ?>
                  <tr>
                    <td><?=$usuario['idHistorico']?></td>
                    <td><?=explode("\n", wordwrap($usuario['nomeDisciplina'], 45, "\n"))[0];?></td>                     
                    <td><?=explode("\n", wordwrap($usuario['nomeAluno'], 45, "\n"))[0];?></td>                     
                    <td><?=$usuario['telZapAluno']?>
                      <button class="whatsapp-button" onclick="window.open('https://api.whatsapp.com/send?phone=<?php echo $telZap; ?>&text=Olá, gostaria de mais informações sobre os Cursos Teológicos da FATAD?', '_blank')">
                      <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
                      </button>
                    </td>  
                    <td>
                        <?php
                        $cpf = $cpf ?? '';
                        $parametros = [
                            'cpf'            => $cpf,
                            'idHistorico'    => $usuario['idHistorico'] ?? '',
                            'nomeDisciplina' => $usuario['nomeDisciplina'] ?? '',
                            'opcao'          => $usuario['opcao'] ?? '',
                            'idOp'           => $usuario['idOp'] ?? '',
                            'acao'           => 'liberarApostila'
                        ];

                        $url = 'cadastroLiberarDocAluno.php?' . http_build_query($parametros);
                        ?>

                        <a href="<?= $url ?>" class="btn btn-outline-info">
                          Liberar
                        </a>                   
                        </td>
                  </tr>
                  <?php
                    }
                  } else {
                    exit();
                    echo '<tr><td colspan="5">Nenhum aluno encontrado.</td></tr>';
                  }
                }
                  ?>
                </tbody>
              </table>
            </div>      
          </div>
        </div>
      </div><br>
    
                
    
      <!-- Lembrete para despacho de apostilas pelos correios -->
      <?php
      $usuarios = $fg->findListOpFinanceira("E");
       if (!count($usuarios) > 0) {
          exit();
       }
       ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <h5 style="color: blue;">Pedidos Aguardando Envio</h5>
            </div>
            <div class="card-body">
              <table class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Material Solicitado</th>                    
                    <th>Nome Aluno</th>
                    <th>Telefone</th>

                    <th>Ação</th>
                  </tr>
                </thead>
                <tbody id="conteudoTabela">
                  <?php
                  // Carrega os registros ao abrir a página
                  //  $visitantes = [];                 
                  if($privilegio=="opFatad" || $privilegio=="admFatad" ){
                    
                  ///Resolver isto depois
                  }
                
                 if (!empty($usuarios) && is_array($usuarios)) {
                      foreach($usuarios as $usuario) {
                      $telZap=$usuario['telZapAluno'];//para mostrar o botão do WhatsUp
                      $cpf=$usuario['cpfAluno'];
                  ?>
                  <tr>
                    <td><?=$usuario['idHistorico']?></td>
                    <td><?=explode("\n", wordwrap($usuario['nomeDisciplina'], 45, "\n"))[0];?></td>                     
                    <td><?=explode("\n", wordwrap($usuario['nomeAluno'], 45, "\n"))[0];?></td>                     
                    <td><?=$usuario['telZapAluno']?>
                      <button class="whatsapp-button" onclick="window.open('https://api.whatsapp.com/send?phone=<?php echo $telZap; ?>&text=Olá, gostaria de mais informações sobre os Cursos Teológicos da FATAD?', '_blank')">
                      <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" alt="WhatsApp">
                      </button>
                    </td>  
                    <td>
                        <?php
                        $cpf = $cpf ?? '';
                        $parametros = [
                            'cpf'            => $cpf,
                            'idHistorico'    => $usuario['idHistorico'] ?? '',
                            'nomeDisciplina' => $usuario['nomeDisciplina'] ?? '',
                            'opcao'          => $usuario['opcao'] ?? '',
                            'idOp'           => $usuario['idOp'] ?? '',
                            'acao'           => 'enviarCorreios'
                        ];

                        $url = 'cadastroListVisitantesPro.php?' . http_build_query($parametros);
                        ?>

                        <a href="<?= $url ?>" class="btn btn-primary" >
                          Confirmar Envio
                        </a>                   
                        </td>
                  </tr>
                  <?php
                    }
                  } else {
                    exit();
                    echo '<tr><td colspan="5">Nenhum aluno encontrado.</td></tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>      
          </div>
        </div>
      </div>
 
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js" integrity="sha512-d4KkQohk+HswGs6A1d6Gak6Bb9rMWtxjOa0IiY49Q3TeFd5xAzjWXDCBW9RS7m86FQ4RzM2BdHmdJnnKRYknxw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function() {
            $('#filtroTabela').on('input', function() {
                var filtro = $(this).val();
                console.log("Filtro digitado:", filtro);
                $.post('cadastroBuscarAlunos.php', { filtro: filtro }, function(data) {
                    try {
                        var result = JSON.parse(data);
                        $('#conteudoTabela').html(result.tabela);
                    } catch (error) {
                        console.error("Erro ao analisar JSON:", error);
                        console.log("Conteúdo recebido: " + data);
                    }
                });
            });
        });
    </script>
    <script>
    function toggleBotao() {
        let botao = document.getElementById("botaoEditar");
        if (botao.classList.contains("disabled")) {
            botao.classList.remove("disabled");
            botao.setAttribute("aria-disabled", "false");
        } else {
            botao.classList.add("disabled");
            botao.setAttribute("aria-disabled", "true");
        }
    }
    </script>
    <style>
    .whatsapp-button {
      background: none;
      border: none; /* Remove qualquer moldura */
      padding: 0; /* Elimina espaçamentos internos */
    }

    .whatsapp-button img {
        width: 24px;
        height: 24px;
        display: inline-block;
    }
    </style>
  </body>
</html>
