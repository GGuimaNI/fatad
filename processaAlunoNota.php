<?php
	include_once("conexao.php");
        include_once './fatadgestaoControler.php';
        $fg = new fatadgestaoControler;

        $idAluno=$_POST['idAluno']; 
        $idTurma=$_POST['idTurma'];
//        var_dump($idAluno);
//        var_dump($idCurso);
//        var_dump($_POST);
           
        $idHistorico=mysqli_real_escape_string($conn, $_POST['idHistorico']);
        $dtTer = mysqli_real_escape_string($conn, $_POST['dtTerEstudo']);
        $grau=mysqli_real_escape_string($conn, $_POST['grau']);
        
        $nivel="";
        $nivelCurso=$fg->findNivelCurso($idHistorico);
        foreach ($nivelCurso as $rowNivel) {
          $nivel=$rowNivel->nivelCurso;
          break;
        }
        
        //        // Troca a vírgula por ponto, se houver
        $gFormatado=str_replace(',', '.', $grau);
        
        if($gFormatado>10) {
            $gFormatado=10; 
        } else if($gFormatado<0) {
          $gFormatado=0;  
        }
        //Para os cursos médios e básicos, a nota e a média para aprovação é maior ou igual a 5 (cinco).
        //Para o curso avançado, a média para aprovação é maior ou igual a 7 (sete).

        if($gFormatado<5){
          $situacao="Reprovado"; 
        }else{
            if($nivel=="Avançado"){
              if($gFormatado>6.99){
                $situacao="Aprovado";  
              }
              if($gFormatado>4.99 and $gFormatado<7){
                 $situacao="Aprovado(*)"; 
              }
            }else{
              if($gFormatado>4.99){
                $situacao="Aprovado";  
              }
            }
        }
       
        $result_conclusao = "UPDATE tb_historico_aluno SET dtTerEstudo = '$dtTer', nota='$gFormatado', situacao='$situacao' WHERE idHistorico = '$idHistorico'";
        $resultado_alunos = mysqli_query($conn, $result_conclusao);

        
?>
<!DOCTYPE html>
<html lang="pt-br">
	<head>
		<meta charset="utf-8">
	</head>

	<body> 
            <input type="hidden" id="custId" name="custId" value="<?php echo $idAluno; ?>">
            <input type="hidden" id="idTurma" name="idTurma" value="<?php echo $idTurma; ?>">
            <?php
            var_dump($idAluno);
//        var_dump($idCurso);
		if(mysqli_affected_rows($conn) != 0){
			echo "
				<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=listarAlunoNota.php?idAluno=$idAluno&idTurma=$idTurma'>
				<script type=\"text/javascript\">
					alert(naoMostraSucesso\" Alterado com Sucesso.\");
				</script>
			";	
		}else{
			echo "
				<META HTTP-EQUIV=REFRESH CONTENT = '0;URL=listarAlunoNota.php?idAluno=$idAluno&idTurma=$idTurma'>
				<script type=\"text/javascript\">
					alert(\"Falhou a alteração.\");
				</script>
			";	
		}?>
	</body>
</html>
<?php $conn->close(); ?>
