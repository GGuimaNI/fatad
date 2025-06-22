<?php
session_start(); 
 include_once("conexao.php");
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$nomeAluno="";

// Verificar se o valor de $_SESSION['usuario'] está correto no início 
if (isset($_SESSION['usuario_autenticado'])) { 
  $privilegio = $_SESSION['privilegio'];
  $usuario=$_SESSION['usuario']; 
  // Adicione lógica baseada no privilégio do usuário 
  if($privilegio=="opFatad"){
      include('./index.html');
  }elseif($privilegio=="admFatad"){
      include('./index.html');
  }elseif($privilegio=="opNuc"){
       include('./barOpNuc.html');
  }else { 
  echo 'Sessão não iniciada ou privilégio não definido.'; 
  // Redirecionar para a página de login ou mostrar uma mensagem de erro 
  header('Location: login.php'); exit(); 
} 
}
?>

<html>
    <head>
        <!--<title>Menu Responsivo</title>-->
        <title>FATAD Gestão</title>

    </head>   
    
    <body>

        <div class="content" id="perfil" style="background-color: transparent"> 
        <div class="height-nav"></div>
        <br><br><br>
        <form class="inserirCursos" method="post" action="inserirCursos.php" >
            <h4 style="color: blue;">Criando Cursos</h4>
                <label>Nome:</label>
                <input type="text" placeholder="Nome do curso" required="" name="nomeCurso" id="nomeCurso"/>
                <br>
                <label>Nível:</label>
                <input type="text" required="" placeholder="Nível do curso" name="nivelCurso" id="nivelCurso" />
                <br>
                <input type="submit" name="cadastrarCurso" id="cadastrarCurso" value="Enviar"/>
                <?php
                if(isset($_POST['nomeCurso'])){
                    $nomeCurso=$_POST['nomeCurso'];
                    $nivelCurso=$_POST['nivelCurso'];
                    $sql = "INSERT INTO tb_cursos (nomeCurso,nivelCurso) "
                    ."VALUES ('$nomeCurso','$nivelCurso')";
//                    var_dump($sql);
                    $result= mysqli_query($conn, $sql);

                    if($result){
                        echo 'Curso '.strstr($nomeCurso, ' ', true).' foi criado';
                    }else{
                        echo 'Não funcionou o cadastro';
                    }
                }
                 ?>
            </form>
        </div>
    </body>   
    <style>
        body{
            font-family: Arial, sans-sarif;
            background-image: url(./imagens/logofatad.png);
            background-size: cover;
        }
        .inserirCursos{    
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            padding: 25px;
            box-shadow: 0px 5px rgba(0,0,0,0.1);
        }
        legend{
            font-size: 15px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        label{
            display: block;
            margin-bottom: 3px;
            font-weight: normal;
            text-align: left;
        }
        input[type='text'],
        input[type='password'],
            input[type='email']{
            width: 100%;
            padding: 5px;
            border-radius: 3px;
            border: 1px solid #ccc;
            margin-bottom: 3px;
            font-size: 12px;
        }
        input[type='submit']{
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 5px 15px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 14px;
            margin-top: 10px;
        }
        input[type='submit']:hover{
            background-color: #3c8c41;
        }
    </style>

</html>
