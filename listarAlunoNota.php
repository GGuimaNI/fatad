<?php
session_start(); 
	include_once './conexao.php';
    include_once './alunosModelClass.php';
    include_once './fatadgestaoControler.php';
    $fg = new fatadgestaoControler;
    $am= new alunosModelClass;
    $nomeAluno="";
    $acumulaNota=0;
    $contaNota=0;
    $nivel="";
    $pendencia="(";
    $abaixoMedia="(";

    if (isset($_SESSION['privilegio'])) { 
        $privilegio = $_SESSION['privilegio'];
        $usuario=$_SESSION['usuario']; 
        $idSessao=$_SESSION['idSessao'];
        
        // Adicione lógica baseada no privilégio do usuário 
        if($privilegio=="opFatad"){
            include('./index.html');

        }elseif($privilegio=="admFatad"){
            include('./index.html');

        }elseif($privilegio=="opNuc"){
            include('./barOpNuc.php');

        }elseif($privilegio=="opAluno"){
            include('./barOpAluno.php');
        } else { 
            echo 'Sessão não iniciada ou privilégio não definido.'; 
            // Redirecionar para a página de login ou mostrar uma mensagem de erro 
            header('Location: logout.php'); 
            exit(); 
        } 
    }

    if(!empty($_POST)){
        $idTurma=$_POST['idTurma'] ;
        $idAluno=$_POST['idAluno'] ;
    }else{
        $idTurma=$_GET['idTurma'] ;
        $idAluno=$_GET['idAluno'] ;
    }

    $rows = $fg->findAlunoEspecifico($idAluno);
    foreach($rows as $row){
        $nomeAluno = $row->nomeAluno; 
    }
//        $cur=$fg->findCursoTurma();
//Início trecho proposto por Capilot
$idAluno = mysqli_real_escape_string($conn, $idAluno);
$idTurma = mysqli_real_escape_string($conn, $idTurma);

$result_historico = "SELECT 
    tha.idTurma, 
    tha.idHistorico, 
    tha.idAluno, 
    tha.idCurso, 
    tha.idDisciplina, 
    tha.situacao, 
    a.nomeAluno, 
    d.nomeDisciplina, 
    d.codigoDisciplina, 
    d.creditoDisciplina, 
    d.cargaHorariaDisciplina AS cargaHoraria, 
    DATE_FORMAT(tha.dtIniEstudo, '%d/%m/%Y') AS dtIniEstudo, 
    DATE_FORMAT(tha.dtTerEstudo, '%d/%m/%Y') AS dtTerEstudo, 
    tha.nota 
FROM 
    tb_historico_aluno AS tha 
JOIN 
    tb_disciplinas AS d ON tha.idDisciplina = d.idDisciplina 
JOIN 
    tb_aluno AS a ON tha.idAluno = a.idAluno 
WHERE 
    tha.idAluno = ? AND 
    tha.idTurma = ? 
ORDER BY 
    d.ordenacao";

$stmt = $conn->prepare($result_historico);
$stmt->bind_param("ii", $idAluno, $idTurma);
$stmt->execute();
$dados = $stmt->get_result();
//Fim trecho proposto por Capilot
?>
<!DOCTYPE html>
<html lang="pt-br">
    
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lançar Nota</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <title>FATAD</title>
    
    <!-- <link rel="stylesheet" href="./css/styles.css"> -->
</head>
<br><br><br> 
<body>
    <br>
    <div class="container mt-4">  
        <table class="table">
            <tr>
                <td>
                    <h4 style="color: blue;">                
                    Histórico de <?php echo $nomeAluno; ?> 
                    </h4>
                </td>
                <td>
                    <h5>                
                    <a href="./pesquisarAlunoCurso.php">
                    <p style="color: red;">Analisar Outro Aluno</p></a> 
                    </h5>
                </td>
                <td>
                    <h5 style="color: blue">PDF deste histórico:  
                        <button type="button" class="btn btn-xs btn-danger" data-toggle="modal" 
                        data-whatever=" - Impressão de Histórico"
                        data-target="#historicoModal" >Histórico Escolar</button>
                    </h5>
                </td>
            </tr>       
        </table>
    </div>
    <div class="container mt-4">
        <div class="col-md-12">
            <div class="row">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>COD</th>
                            <th>Nome da Disciplina</th>
                            <th>Início Estudo</th>
                            <th>Término Estudo</th>
                            <th>Nota</th>
                            <th>Situação</th>
                            <th>Ações</th>      
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($rows_historico = mysqli_fetch_assoc($dados)){ ?>
                        <tr>
                            <td><?php echo $rows_historico['codigoDisciplina']; ?></td>
                            <td><?php echo $rows_historico['nomeDisciplina']; ?></td>
                            <td><?php echo $rows_historico['dtIniEstudo']; ?></td>
                            <td><?php echo $rows_historico['dtTerEstudo']; ?></td>
                            <td><?php echo $rows_historico['nota']; ?></td>
                            <td>
                                <?php 
                                if(!empty($rows_historico['dtIniEstudo'])){
                                    echo $rows_historico['situacao'];
                                }
                                ?>
                            </td> 
                            <td>
                                <?php 
                                if($privilegio=="opFatad" OR $privilegio=="admFatad"){
                                $nivelCurso=$fg->findNivelCurso($rows_historico['idHistorico']);
                                foreach ($nivelCurso as $rowNivel) {
                                    $nivel=$rowNivel->nivelCurso;
                                    break;
                                }
                                if(!empty($rows_historico['dtIniEstudo'] && $rows_historico['situacao']!=="Matriculado")){
                                    if($rows_historico['nota'] <5){ ?>                                      
                                        <button type="button" class="btn btn-xs btn-warning" data-toggle="modal" 
                                                    data-target="#exampleModal" 
                                                    data-whatever="<?php echo $rows_historico['idHistorico']; ?>"
                                                    data-whateverter="<?php echo $rows_historico['dtTerEstudo']; ?>"
                                                    data-whatevernota="<?php echo $rows_historico['nota']; ?>">Lançar Nota</button>
                                    <?php  
                                    }else{
                                        if($rows_historico['nota'] <7 AND $nivel=="Avançado"){ ?>                                      
                                                <button type="button" class="btn btn-xs btn-warning" data-toggle="modal" 
                                                    data-target="#exampleModal" 
                                                    data-whatever="<?php echo $rows_historico['idHistorico']; ?>"
                                                    data-whateverter="<?php echo $rows_historico['dtTerEstudo']; ?>"
                                                    data-whatevernota="<?php echo $rows_historico['nota']; ?>">Lançar Nota</button>

                                        <?php 
                                        }
                                    }   
                                }}
                                ?>
                            </td>
                        </tr>
                            
                            <?php 
                            $acumulaNota=$acumulaNota+$rows_historico['nota'];
                            if($rows_historico['nota']>0){$contaNota=$contaNota+1;}
                            if($rows_historico['nota']>0 and $rows_historico['nota']<5 ){$pendencia=$pendencia.$rows_historico['codigoDisciplina'].",";}
                            
                            //Para os cursos médios e básicos, a nota e a média para aprovação é maior ou igual a 5 (cinco).
                            //Para o curso avançado, a média para aprovação é maior ou igual a 7 (sete).
                        
                            if($nivel=="Avançado"){
                                if($rows_historico['nota']>4.99 and $rows_historico['nota']<7 ){$abaixoMedia=$abaixoMedia.$rows_historico['codigoDisciplina'].",";}
                            }
                        } //Fim do While

                        if ($contaNota>0 and $acumulaNota>0){
                            $media=number_format($acumulaNota/$contaNota, 2, '.', ',');
                        }else{
                           $media=""; 
                        }
                        
                        ?>
                        
                    </tbody> 
                </table>

                    <!-- As linhas abaixo são para controlar o que é mostrado no rodapé da página -->

                    <?php 
                    $abaixoMedia=substr($abaixoMedia, 0, -1);
                    if(strlen($abaixoMedia)>1) { ?>
                        <center><?php echo '(*) Abaixo da média: '.$abaixoMedia.")"; ?></center>
                    <?php } ?>

                    <?php $pendencia=substr($pendencia, 0, -1);
                    if(strlen($pendencia)>1) { ?>
                        <center><?php echo 'Dependência: '.$pendencia.")"; ?></center>
                    <?php } ?>

                    <?php 
                    if(strlen($media)>1) { ?>
                        <center><?php echo 'Média: '.$media; ?></center>
                    <?php } ?>
                    <br><br><br>
            </div>
        </div>       
    </div>
        	
		       
        <!--Data de Conclusão e nota-->
	<div class="modal fade" id="exampleModal"  tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="exampleModalLabel">Término do Estudo</h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="processaAlunoNota.php" enctype="multipart/form-data">

                            <div class="form-group">
                                <label for="recipient-ter" class="control-label">Dt Conclusão Estudo Apostila:</label>
                                <input name="dtTerEstudo" required="" type="date" class="form-control" id="recipient-ter">
                            </div>  

                            <div class="form-group">
                                <label for="recipient-nota" class="control-label">Nota Obtida:</label>
                                <input name="grau" type="text" class="form-control" id="recipient-nota">
                            </div>
                            <!--CONCLUIR-->
                            <input name="idHistorico" type="hidden" class="form-control" id="id-curso" value="">
                            <input type="hidden" id="idAluno" name="idAluno" value="<?php echo $idAluno; ?>">
                            <input type="hidden" id="idTurma" name="idTurma" value="<?php echo $idTurma; ?>">
                            <input type="hidden" id="nmaluno" name="nmaluno" value="<?php echo $nomeAluno; ?>">
                            <button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger" name="botao" id="botao" value="atribuirgrau">Gravar</button>                          
                        </form>
                    </div>
                </div>
            </div>
	</div>
        
        <!--Impressão do Histórico (até ser colocado em um único botão-->
        <div class="modal fade" id="historicoModal"  tabindex="-1" role="dialog" aria-labelledby="historicoModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="historicoModalLabel">Imprimindo Histórico </h4>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="rptHistoricoAlunoPDF.php" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="recipient-ini" class="control-label">Poderá haver uma demora para montar a página, dependendo da capacidade de processamento e do momento. <br><br> A página com o histórico será gravada na pasta padrão de 'download', em pdf, com nome do aluno.</label>
                                
                            </div>

                            <input name="idHistorico" type="hidden" class="form-control" id="id-curso" value="">
                            <input type="hidden" id="idAluno" name="idAluno" value="<?php echo $idAluno; ?>">
                            <input type="hidden" id="idTurma" name="idTurma" value="<?php echo $idTurma; ?>">
                            <input type="hidden" id="nmaluno" name="nmaluno" value="<?php echo $nomeAluno; ?>">
                            <button type="button" class="btn btn-success" data-dismiss="modal">Fechar</button>
                            <button type="submit" class="btn btn-danger" target='_blank' name="botao" id="botao" value="historico">Gerar o PDF</button>
                            
                        </form>
                        
                    </div>
                </div>
            </div>
	</div>
        
        
       <style>
        /* Estilo para o menu fixo */
        nav {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background-color: #db096f;
            color: white;
            padding: 6px;
            z-index: 1000; /* Mantém o nav acima do conteúdo */
        }

        /* Estilo para o conteúdo principal */
        .content {
            padding-top: 60px; /* Espaço para o conteúdo não ficar atrás do nav */
        }

        /* Exemplo de estilo para o conteúdo */
        .content p {
            padding: 20px;
            margin: 0;
        }
    </style>

   <script>
        const arrayHeight = document.getElementsByClassName('height-nav');
        const navHeight = document.getElementsByTagName('nav')[0].clientHeight;

        for (let navHeightObj of arrayHeight)
            navHeightObj.style.height = navHeight + 'px';

        function abrirMenu() {
            const botoesMenu = document.getElementById('botoesMenu');

            botoesMenu.className = botoesMenu.className.includes('responsivo') ? '' : 'responsivo'
        }
    </script>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script type="text/javascript">
        
            $('#apostilaModal').on('show.bs.modal', function (event) {
              var button = $(event.relatedTarget) // Button that triggered the modal
              var recipient = button.data('whatever') // Extract info from data-* attributes
              var recipientnome = button.data('whatevernome')
              var recipientini = button.data('whateverini')
              
              var modal = $(this)
              modal.find('.modal-title').text('ID ' + recipient)
              modal.find('#id-curso').val(recipient)
              modal.find('#recipient-name').val(recipientnome)
              modal.find('#recipient-ini').val(recipientini)
            })
            
            $('#historicoModal').on('show.bs.modal', function (event) {
              var button = $(event.relatedTarget) // Button that triggered the modal
              var recipient = button.data('whatever') // Extract info from data-* attributes
              var recipientnome = button.data('whatevernome')
              var recipientini = button.data('whateverini')
              
              var modal = $(this)
              modal.find('.modal-title').text('ID ' + recipient)
              modal.find('#id-curso').val(recipient)
              modal.find('#recipient-name').val(recipientnome)
              modal.find('#recipient-ini').val(recipientini)
            })
            
            $('#fichaModal').on('show.bs.modal', function (event) {
              var button = $(event.relatedTarget) // Button that triggered the modal
              var recipient = button.data('whatever') // Extract info from data-* attributes
              var recipientnome = button.data('whatevernome')
              var recipientini = button.data('whateverini')
              
              var modal = $(this)
              modal.find('.modal-title').text('ID ' + recipient)
              modal.find('#id-curso').val(recipient)
              modal.find('#recipient-name').val(recipientnome)
              modal.find('#recipient-ini').val(recipientini)
            })
        
            $('#exampleModal').on('show.bs.modal', function (event) {
              var button = $(event.relatedTarget) // Button that triggered the modal
              var recipient = button.data('whatever') // Extract info from data-* attributes
              var recipientnome = button.data('whatevernome')
              var recipientini = button.data('whateverini')
              var recipientter = button.data('whateverter')
              var recipientnota = button.data('whatevernota')
              // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
              // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
              var modal = $(this)
              modal.find('.modal-title').text('ID ' + recipient)
              modal.find('#id-curso').val(recipient)
              modal.find('#recipient-name').val(recipientnome)
              modal.find('#recipient-ini').val(recipientini)
              modal.find('#recipient-ter').val(recipientter)
              modal.find('#recipient-nota').val(recipientnota)



            })
            $(document).on('click'),'.editar', function(){
                var id=$(this).data('$idAluno');
            }
    </script>
  </body>
</html>