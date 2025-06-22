<?php 
session_start(); 
$rsNucleos="";

include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;

if (isset($_SESSION['usuario_autenticado'])) { 
    $privilegio = $_SESSION['privilegio'];
    $usuario=$_SESSION['usuario']; 
    // Adicione lógica baseada no privilégio do usuário 
    if($privilegio=="opFatad"){
        include('./index.html');
        $rsNucleos = $fg->findNucleo();
    }elseif($privilegio=="admFatad"){
        include('./index.html');
    }elseif($privilegio=="opNuc"){
        include('./barOpNuc.php');
        $rsNucleos = $fg->findNucleoCpf($usuario);
    
    } else { 
        echo 'Sessão não iniciada ou privilégio não definido.'; 
        // Redirecionar para a página de login ou mostrar uma mensagem de erro 
        header('Location: logout.php'); exit(); 
} 
}
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Criar Turmas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
    <br><br>
    <div class="container mt-5">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                <h5 style="color: blue" >Cadastrar Turmas em Núcleo
                <!-- <a href="criarTurmasGRUD.php" class="btn btn-danger float-end">Voltar</a> -->
              </h5>
            </div>
            <div class="card-body">
              <form action="criarTurmasGRUDacoes.php" method="POST">
                <div class="mb-3">
                    <lbel>Curso:</label>a
                    <select   required="Clique para escolher um curso" name="idCurso" class="form-control">
                        <option value="">Escolha Curso</option>
                        <?php $rsCursos = $fg->findCursoTurma();
                        foreach ($rsCursos as $row) {
                            ?>
                            <option value="<?= $row->idCurso ?>"><?= $row->nomeCurso ?></option>                                                        
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <lbel>Núcleo:</label>a
                    <select   required="Clique para escolher um núcleo" name="idNucleo" class="form-control">
                        <option value="">Escolha Núcleo</option>
                        $id=0;
                        <?php 
                        foreach ($rsNucleos as $row) {
                        ?>
                            <option value="<?= $row->idNucleo ?>"><?= $row->descNucleo ?></option>                                                        
                        <?php } ?>
                    </select>
                </div> 
                <div class="mb-3">
                    <label>Data de Início:&nbsp;&nbsp;&nbsp;&nbsp;</label>
                <input type="date" required="" placeholder="dd/mm/AAAA" name="dtIni" id="dtIni" />
                </div>   
 
                <div class="mb-3">
                <lbel>Data de Término:</label>
                <input type="date" required="" placeholder="dd/mm/AAAA" name="dtTer" id="dtTer" />
                </div>
                
                
                <div class="mb-3">
                  <button type="submit" name="cadastro_curso" class="btn btn-primary">Salvar</button>
                </div>
                  
                
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  
     <script> src="js/code.jquery.com_jquery-3.7.0.min.js"</script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"
            integrity="sha512-d4KkQohk+HswGs6A1d6Gak6Bb9rMWtxjOa0IiY49Q3TeFd5xAzjWXDCBW9RS7m86FQ4RzM2BdHmdJnnKRYknxw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="js/cep.js"></script>
  </body>
</html>