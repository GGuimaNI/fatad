<?php 
session_start(); 
 if (isset($_SESSION['usuario_autenticado'])) { 
    $privilegio = $_SESSION['privilegio'];
    $usuario=$_SESSION['usuario']; 
    $idSessao=$_SESSION['idSessao'];
    // Adicionar lógica baseada no privilégio do usuário 
    if($privilegio=="Visitante"){
        header('Location: iniciarVisitante.php'); 
        exit();
    }else{
        header('Location: iniciarGeral.php'); 
        exit();
    }

}else{ 
    echo 'Sessão não iniciada ou privilégio não definido.'; 
    // Redirecionar para a página de login ou mostrar uma mensagem de erro 
    header('Location: logout.php'); 
    exit(); 
    } 
?>
