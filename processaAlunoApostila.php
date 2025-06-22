<?php
	include_once("conexao.php");
//        include_once './fatadgestaoControler.php';
//        $fg = new fatadgestaoControler;

        $idAluno=$_POST['idAluno']; 
        $idCurso=$_POST['idCurso'];
//        var_dump($_POST);
        

        $idHistorico=mysqli_real_escape_string($conn, $_POST['idHistorico']);
        $dtIni = mysqli_real_escape_string($conn, $_POST['dtIniEstudo']);
        $result_apostila = "UPDATE tb_historico_aluno SET dtIniEstudo = '$dtIni' WHERE idHistorico = '$idHistorico'";
        $resultado_alunos = mysqli_query($conn, $result_apostila);
?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
	</head>

	<body> 
            <input type="hidden" id="custId" name="custId" value="<?php echo $idAluno; ?>">
            <input type="hidden" id="idCurso" name="idCurso" value="<?php echo $idCurso; ?>">
            <?php
		if(mysqli_affected_rows($conn) != 0){
			echo "
				<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=listarAlunoNota.php?idAluno=$idAluno&idCurso=$idCurso'>
				<script type=\"text/javascript\">
					alert(naoMostraSucesso\" Alterado com Sucesso.\");
				</script>
			";	
		}else{
			echo "
				<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=listarAlunoNota.php?idAluno=$idAluno&idCurso=$idCurso'>
				<script type=\"text/javascript\">
					alert(\"Falhou a alteração.\");
				</script>
			";	
		}?>
	</body>
</html>
<?php $conn->close(); ?>

