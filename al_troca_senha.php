<?php
session_start();
require './conexao.php';
if (isset($_SESSION['usuario_autenticado'])) { 
    $privilegio = $_SESSION['privilegio'];
    $usuario=$_SESSION['usuario']; 
    // Adicione lógica baseada no privilégio do usuário 
    if($privilegio=="opFatad"){
        include('./index.html');
    }elseif($privilegio=="admFatad"){
        include('./index.html');
    }elseif($privilegio=="opNuc"){
        include('./barOpNuc.php');
    }elseif($privilegio=="opAluno"){
        include('./barOpAluno.php');
    }elseif($privilegio=="Visitante"){
        include('./barVisitante.html');    
    } else { 
    echo 'Sessão não iniciada ou privilégio não definido.'; 
    // Redirecionar para a página de login ou mostrar uma mensagem de erro 
    header('Location: login.php'); exit(); 
  }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Troca de Senha</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <br><br>
    <div class="container mt-5">
    <?php
      if (isset($_SESSION['message'])) {
          echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
          unset($_SESSION['message']); // Limpa a mensagem após exibição
      }
      ?> 
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                        <h4 style="color: blue;">Trocar Senha</h4>
                    </div>
                    <div class="card-body">
                        <form action="al_acoes.php" method="POST">
                            <div class="mb-3">
                                <label for="senhaAtual" class="form-label">Senha Atual</label>
                                <input type="password" name="senhaAtual" id="senhaAtual" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="novaSenha" class="form-label">Nova Senha</label>
                                <input type="password" name="novaSenha" id="novaSenha" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="confirmaSenha" class="form-label">Confirme a Nova Senha</label>
                                <input type="password" name="confirmaSenha" id="confirmaSenha" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Trocar Senha</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-GeWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
</body>
</html>
