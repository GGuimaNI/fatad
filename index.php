
<?php
include_once 'fatadgestaoControler.php';
$fg = new fatadgestaoControler;

include_once './config.php';
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
// Cria uma conexão usando a classe Config
try {
    $pdo = new Config();
} catch (PDOException $e) {
    echo 'Erro ao conectar ao banco de dados: ' . $e->getMessage();
    exit;
}
?>

<html>
    <body class="content" id="perfil" style="background-color: aqua">
        <br><br><br>  
        
        <form class="login" method="POST" action="index.php">
            <legend>LOGIN</legend>
            <label>Usuário:</label>
            <input type="text" placeholder="CPF (Deve ser apenas números)" name="cpfUsuario" id="cpfUsuario" autofocus/>
            <br>
            <label>Senha:</label>
            <input type="password" required placeholder="Não lembra? Clique 'Esqueci Senha'" name="senha" id="senha" />
            <br>
            <input type="submit" name="logar" id="LOGAR" value='Entrar'/>
            
            <?php
            
                if(isset($_POST['cpfUsuario'])){
                    //O formatCnpjCpf insere os pontos e o traço.
                    // $cpfUsuario = $fg->formatCnpjCpf($_POST['cpfUsuario']);
                    $cpfUsuario = $_POST['cpfUsuario'];
                    $usuario=$cpfUsuario;
                    $senha=$_POST['senha'];
                    $idSessao="";
                    
                    $userId =$cpfUsuario; // Supondo que o ID do usuário está armazenado na sessão
                    $sql = "SELECT * FROM tb_usuarios WHERE cpfUsuario = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $userId);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);
                    $prossegue=true;

                    if (!$user || !isset($user['varPalavraBase'])) {
                      echo 'Usuário não encontrado ou senha não definida.';
                    } else {

                    // Verificar se a senha atual está no formato MD5 ou usando password_hash
                    if (strlen($user['varPalavraBase']) == 32) {
                        // Verificar a senha no formato MD5

                        if (md5($senha) !== $user['varPalavraBase']) {
                            $prossegue=false;
                        }
                    } else {
                        // Verificar a senha no formato password_hash
                        if (!password_verify($senha, $user['varPalavraBase'])) {
                            $prossegue=false;
                        }
                    }

                    if($prossegue){
                        $privilegio= $user['varPrivilegio'];
                        $idSessao=$user['idSessao'];

                        session_start();
                        $_SESSION['usuario']=$cpfUsuario;
                        // $_SESSION['senha']=$senha;
                        $_SESSION['usuario_autenticado'] = true; // Apenas um indicador de login
                        $_SESSION['idSessao']=$idSessao;
                        $_SESSION['privilegio']=$privilegio;

                        header('Location: iniciar.php');
                    }else{
                        echo "Problema com senha e/ou usuário";
                    }
                }
            }
            ?>  
            <center>
            <h4>
            <p><a href="recuperarSenha.php">Esqueci Senha</a>&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="cadastro.php">Novo Usuário</a></p>
            </h4>                           
            </right>
        </form>
        
    </body>    
    <title>Faça seu login</title>
    <style>
/*        .style{
            padding: 0;
            margin: 0;
        }*/
        body{
            font-family: Arial, sans-sarif;
            background-image: url(./imagens/logofatad.png);
            background-size: cover;
        }
        .login{
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            padding: 20px;
            box-shadow: 0px 10px rgba(0,0,0,10);
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
            padding: 8px 15px;
            border-radius: 3px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        input[type='submit']:hover{
            background-color: #3c8c41;
        }
    </style>
    
</html>