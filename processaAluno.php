<?php
	include_once("conexao.php");
	$idAluno = mysqli_real_escape_string($conn, $_POST['idAluno']);
	$nomeAluno = mysqli_real_escape_string($conn, $_POST['nomeAluno']);
	$cpfAluno = mysqli_real_escape_string($conn, $_POST['cpfAluno']);
	echo "$idAluno - $nomeAluno - $cpfAluno";
	$result_alunos = "UPDATE tb_aluno SET nomeAluno='$nomeAluno', cpfAluno = '$cpfAluno' WHERE idAluno = '$idAluno'";
	
	$resultado_alunos = mysqli_query($conn, $result_alunos);	
?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
	</head>

	<body> <?php
		if(mysqli_affected_rows($conn) != 0){
			echo "
				<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=listarAlunos.php'>
				<script type=\"text/javascript\">
					alert($nomeAluno\" Aluno alterado com Sucesso.\");
				</script>
			";	
		}else{
			echo "
				<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=listarAlunos.php'>
				<script type=\"text/javascript\">
					alert(\"Falhou a alteração.\");
				</script>
			";	
		}?>
	</body>
</html>
<?php $conn->close(); ?>
