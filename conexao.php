<?php
	$servidor = "localhost";
	$usuario = "root";
	//$senha = "CruGuaMys1634*";
    $senha = "";
	$dbname = "fatadgestao";
	
	//Criar a conexão
	$conn = mysqli_connect($servidor, $usuario, $senha, $dbname);
        
        if (mysqli_connect_errno()) {
            die("Falha na conexão: " . mysqli_connect_error());
        }
?>
