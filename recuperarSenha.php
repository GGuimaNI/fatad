

    <?php
    include_once './conexao.php';
    include_once 'fatadgestaoControler.php';
    $fg=new fatadgestaoControler;
    ?>
<html>
    <title>Recupere sua senha</title>
    
    <style>
        body{
            font-family: Arial, sans-sarif;
            background-image: url(./imagens/logofatad.png);
            background-size: cover;
            
            /*background: #f7f7f7;*/
        }
        .recuperarSenha{
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0px 10px rgba(0,0,0,0.1);
        }
        legend{
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        label{
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type='text'],
        input[type='password']{
            width: 100%;
            padding: 10px;
            border-radius: 3px;
            border: 1px solid #ccc;
            margin-bottom: 15px;
            font-size: 16px;
        }
        input[type='submit']{
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 8px 20px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        input[type='submit']:hover{
            background-color: #3c8c41;
        }
          
    </style>
    
    <body>
        
        <form class="recuperarSenha" method="POST" action="recuperarSenha.php">
            <legend>Acesso Complementar</legend><br>
            <label>Usuário:</label>
            <input type="text" required="" placeholder="Seu CPF" name="cpfUsu" id="cpfUsu"/>
            <br>
            <label>Nome animal estimação:</label>
            <input type="text" required="" placeholder="Qual o nome de seu animal de estimação" name="nomeanimal" id="nomeanimal" />
            <br>
            <label>Primeira escola:</label>
            <input type="text" required="" placeholder="Qual o nome sua primeira escola" name="nomeaescola" id="nomeaescola" />
            <br>
            <input type="submit" name="Enviar" id="Enviar"/>
            
             
          
            
<?php

            if(isset($_POST['cpfUsu'])){
                $cpfUsu = $fg->formatCnpjCpf($_POST['cpfUsu']);
                $nomeAnimal=$_POST['nomeanimal'];
                $nomeEscola=$_POST['nomeaescola'];
                $query = "SELECT * FROM tb_usuarios WHERE cpfUsuario  = '$cpfUsu' "
                        ."AND nomeAnimalUsuario = '$nomeAnimal' "
                        . "AND nomeEscolaUsuario = '$nomeEscola'";

                $result = mysqli_query($conn, $query);

                $usuario= "";
                $senha = ""; 
                $privilegio = "";
                $idSessao="";
                

                foreach($result as $row)
                {
                    $senha = $row['varPalavraBase']; 
                    $privilegio = $row['varPrivilegio'];
                    $usuario=   $row['cpfUsuario'];
                    $idSessao=$row['idSessao'];
                }
                session_start();
                If(!empty($senha)){
                    $_SESSION['usuario']=$usuario;
                    $_SESSION['senha']=$senha;
                    $_SESSION['privilegio']=$privilegio; 
                    $_SESSION['idSessao']=$idSessao; 

                    header('Location: iniciar.php');
                    
                }else{
                    echo 'Dados incorretos.';                
                }
                

//                var_dump($_SESSION);
                 
            }
            
?>              
            <center>
                <h4>
                <p><a href="index.php">Voltar</a></p>
                </h4>                           
            </center>
         </form>      
    </body>    

</html>
