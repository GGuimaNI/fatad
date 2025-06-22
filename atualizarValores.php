<?php
session_start(); 

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

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Atualizar Valores</title>
</head>
<body>
    <div class="content" id="perfil" style="background-color: transparent">  
        <div class="height-nav"></div><br><br><br>

        <form class="atualizarValores" method="post" action="atualizarValoresPro.php">
           


            <div class="bloco-informativo">
                <h4><span class="emoji">📦</span>Atualizar Valores de Frete e Impressão de Apostilas</h4>
                <h6>
                    Você pode atualizar apenas um valor, ou os dois ao mesmo tempo.<br>
                    Esta alteração afetará todos os matriculados que optaram por receber o material em casa.
                </h6>
            </div>

            <label>Novo valor da Encadernação:</label>
            <input type="text" placeholder="Pode ser deixado em branco." name="valorEncadernacao" id="valorEncadernacao"/><br>

            <label>Novo valor do Frete:</label>
            <input type="text" placeholder="Pode ser deixado em branco." name="valorFrete" id="valorFrete" /><br>

            <input type="submit" name="alterarValores" id="alterarValores" value="Enviar"/>
        </form>
    </div>
</body>



<style>
        body{
            font-family: Arial, sans-sarif;
            background-image: url(./imagens/logofatad.png);
            background-size: cover;
        }
        .atualizarValores{    
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
        input[type='text']{
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

    .bloco-informativo {
        background-color: #f0f8ff;
        border-left: 5px solid #007bff;
        padding: 20px;
        margin-bottom: 25px;
        border-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    .bloco-informativo h4 {
        color: #0056b3;
        margin-top: 0;
        font-size: 1.25rem;
    }
    .bloco-informativo h6 {
        color: #444;
        font-weight: normal;
        margin-top: 10px;
        line-height: 1.5em;
    }
    .bloco-informativo .emoji {
        font-size: 1.4rem;
        margin-right: 6px;
        vertical-align: middle;
    }
</style>
</html>