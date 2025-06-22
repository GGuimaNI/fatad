<?php
	include_once("conexao.php");
	$result_alunos = "SELECT idAluno,nomeAluno,cpfAluno FROM tb_aluno";
	$resultado_alunos = mysqli_query($conn, $result_alunos);
?>
<!DOCTYPE html>
<html lang="pt-br">
    
    
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Listar Alunos</title>
		<link href="css/bootstrap.min.css" rel="stylesheet">
                <title>FATADGestão</title>
                <link rel="stylesheet" href="./css/styles.css">
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
		
	</head>
        
	<body>
            <nav>
                <div class="logo">
                    <h1>                
                        <a><img src="./imagens/LogoFatadSF.png" width="30px" height="alt"></a>              
                        Lista de Alunos
                    </h1>
                </div>
                <button onclick="abrirMenu()">&#9776;</button>
                <ul id="botoesMenu">
                    <a href="./htmlAlunos.html">
                        <li>
                            <p>Voltar</p>
                        </li>
                    </a>
                    <a href="./index.html">
                        <li>
                            <p>Menu</p>
                        </li>
                    </a>
                    <a href="./logout.php">
                        <li>
                            <p>Sair</p>
                        </li>
                    </a>
                </ul>
            </nav>

             
                
            <div class="container theme-showcase" role="main">
                <div class="page-header">
                        <h1>Listar Alunos</h1>
                </div>
                
                <div class="row">
                    <div class="col-md-12">
                        <table class="table">
                            <thead>
                                    <tr>
                                            <th>Número</th>
                                            <th>CPF Aluno</th>
                                            <th>Nome do Aluno</th>
                                            
                                    </tr>
                            </thead>
                            <tbody>
                                    <?php while($rows_alunos = mysqli_fetch_assoc($resultado_alunos)){ ?>
                                            <tr>
                                                    <td><?php echo $rows_alunos['idAluno']; ?></td>
                                                    <td><?php echo $rows_alunos['cpfAluno']; ?></td>
                                                    <td><?php echo $rows_alunos['nomeAluno']; ?></td>
                                                    <td>
                                                            <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#myModal<?php echo $rows_alunos['idAluno']; ?>">Visualizar</button>
                                                            <button type="button" class="btn btn-xs btn-warning" data-toggle="modal" data-target="#exampleModal" data-whatever="<?php echo $rows_alunos['idAluno']; ?>" data-whatevernome="<?php echo $rows_alunos['nomeAluno']; ?>"data-whateverdetalhes="<?php echo $rows_alunos['cpfAluno']; ?>">Editar</button>
                                                            <button type="button" class="btn btn-xs btn-danger">Apagar</button>
                                                    </td>
                                            </tr>
                                            <!-- Inicio Modal -->
                                            <div class="modal fade" id="myModal<?php echo $rows_alunos['idAluno']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                                    <div class="modal-dialog" role="document">
                                                            <div class="modal-content">
                                                                    <div class="modal-header">
                                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                                            <h4 class="modal-title text-center" id="myModalLabel"><?php echo $rows_alunos['nomeAluno']; ?></h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                            <p><?php echo $rows_alunos['idAluno']; ?></p>
                                                                            <p><?php echo $rows_alunos['nomeAluno']; ?></p>
                                                                            <p><?php echo $rows_alunos['cpfAluno']; ?></p>
                                                                    </div>
                                                            </div>
                                                    </div>
                                            </div>
                                            <!-- Fim Modal -->
                                    <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>		

                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
                  <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                  <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                  <h4 class="modal-title" id="exampleModalLabel">Curso</h4>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="processaAluno.php" enctype="multipart/form-data">
                                    <div class="form-group">
                                          <label for="recipient-name" class="control-label">Nome:</label>
                                          <input name="nomeAluno" type="text" class="form-control" id="recipient-name">
                                    </div>
                                    <div class="form-group">
                                          <label for="message-text" class="control-label">CPF:</label>
                                          <textarea name="cpfAluno" class="form-control" id="cpfAluno"></textarea>
                                    </div>
                                  <input name="idAluno" type="hidden" class="form-control" id="id-curso" value="">

                                  <button type="button" class="btn btn-success" data-dismiss="modal">Cancelar</button>
                                  <button type="submit" class="btn btn-danger">Alterar</button>

                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
            <!-- Include all compiled plugins (below), or include individual files as needed -->
            <script src="js/bootstrap.min.js"></script>
            <script type="text/javascript">
		$('#exampleModal').on('show.bs.modal', function (event) {
		  var button = $(event.relatedTarget) // Button that triggered the modal
		  var recipient = button.data('whatever') // Extract info from data-* attributes
		  var recipientnome = button.data('whatevernome')
		  var recipientdetalhes = button.data('whateverdetalhes')
		  // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
		  // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
		  var modal = $(this)
		  modal.find('.modal-title').text('ID ' + recipient)
		  modal.find('#id-curso').val(recipient)
		  modal.find('#recipient-name').val(recipientnome)
		  modal.find('#cpfAluno').val(recipientdetalhes)
		  
		})
            </script>
            
        
    </body>
</html>
