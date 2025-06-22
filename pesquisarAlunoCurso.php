<?php
session_start();
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
include("includes/conn.php");
$nmCurso="";

if (isset($_SESSION['privilegio'])) { 
    $privilegio = $_SESSION['privilegio'];
    $usuario=$_SESSION['usuario']; 
    $idSessao=$_SESSION['idSessao'];

    // Adicione lógica baseada no privilégio do usuário 
    if($privilegio=="opFatad"){
        include('./index.html');
    }elseif($privilegio=="admFatad"){

    }elseif($privilegio=="opNuc"){
        $rsNucleo=$fg->findNucleoCpf($usuario);
        foreach($rsNucleo as $row){
            $idNucleo=$row->idNucleo;
        }
        $rsInadimplencia=$fg->findInadimplencia($idNucleo);
        foreach($rsInadimplencia as $row){
          $inadimplencia=$row['total'];
        }
        //desabilitado porque o material só é distribuido pela FATAD
        // if($inadimplencia>2){
        //     include('./barOpNucInadim.php');
        // }else{
            include('./barOpNuc.php');
        // }
    
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
    <title>Aluno - Pesquisar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  </head>
  <body>
  <br>
  <div class="container mt-5">
        <?php
            // Apresentar a mensagem de erro ou sucesso
            if(isset($_SESSION['msg'])){
                echo $_SESSION['msg'];
                unset($_SESSION['msg']);
            }
            ?>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <?php if($privilegio=="opNuc"){ ?>
                            <h5 style="color: blue">Escolher um aluno para avaliar  
                            </h5>
                        <?php }else{ ?>
                            <h5 style="color: blue">Escolher um aluno para atribuir nota ou  
                                <a href="importExcelEscArquivo.php" class="btn btn-danger btn-sm ">Importar</a> de uma Planilha
                            </h5>
                        <?php } ?>
                    </div>
                    <div class="card-body">
                        <form class="pesquisarAlunoCurso" method="POST" action="listarAlunoNota.php">
                            <div class="mb-3">
                                <label>Escolha o Núcleo do aluno:</label>
                                <select  name="idTurma" id="idTurma" required autofocus class="form-control">
                                    <option value="">Selecione Curso/Núcle/IdTurma</option>
                                    <?php
                                    if($privilegio=="opNuc"){
                                        $stmt = $conn->prepare("SELECT DISTINCT t.idTurma, t.idNucleo, t.idCursoCurriculo AS idCurso, t.nomeSala, n.descNucleo AS nomeNucleo, c.nomeCurso 
                                        FROM tb_turma t 
                                        JOIN tb_nucleofatad n ON n.idNucleo = t.idNucleo
                                        JOIN tb_cursos c ON c.idCurso = t.idCursoCurriculo
                                        WHERE t.ativo = 0 AND n.perfil = 'Núcleo' AND n.cpfResp = :usuario");
                
                                        $stmt->bindParam(':usuario', $usuario);
                                        // $stmt->execute();
                                        
                                        // $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
                
                                    }else{
                                        $stmt = $conn->prepare("SELECT DISTINCT t.idTurma, t.idNucleo, t.idCursoCurriculo AS idCurso, t.nomeSala, n.descNucleo AS nomeNucleo, c.nomeCurso 
                                        FROM tb_turma t 
                                        JOIN tb_nucleofatad n ON n.idNucleo = t.idNucleo
                                        JOIN tb_cursos c ON c.idCurso = t.idCursoCurriculo
                                        WHERE t.ativo = 0
                                        ORDER BY nomeCurso");
                                    }

                                    $stmt->execute();
                                        
                                    $registros = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                   
                                    // $registros = $query->fetchAll(PDO::FETCH_ASSOC);
                                    foreach($registros as $option) {
                                        ?>
                                            <option value="<?php echo $option['idTurma']?>"><?php echo $option['nomeCurso']." (".$option['nomeNucleo']."/Turma= ".$option['idTurma'].")"?></option>
                                        <?php
                                        $nmCurso=$option['nomeCurso'];
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label>Selecione o Aluno a ser avaliado:</label>
                                <select multiple size="8" name="idAluno" id="idAluno" required class="form-control">   
                                    <option value="">Escolha um aluno</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button type="submit" name="btn" id="apostila" class="btn btn-primary btn-sm">Avaliar</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
      <script src="js/alunoCurso.js"></script>
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