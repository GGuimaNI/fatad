<?php
session_start(); 
include_once 'fatadgestaoControler.php';
$fg=new fatadgestaoControler;
require_once 'config.php'; // Arquivo de conexão com PDO
$userEdit="";
 if (isset($_SESSION['usuario_autenticado'])) { 
     $privilegio = $_SESSION['privilegio'];
     $usuario=$_SESSION['usuario']; 
//     // Adicione lógica baseada no privilégio do usuário 
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
    header('Location: logout.php'); exit(); 
   }
}

?>
<br>
<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <!--<title>Menu Responsivo</title>-->
        <title>FATAD</title>
        <link rel="stylesheet" href="./styles.css">
        
    </head>
        <body>
            <?php
            if (isset($_SESSION['message'])) {
                echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
                unset($_SESSION['message']); // Limpa a mensagem após exibição
            }
            $pdo = new Config();

            $cpf = filter_input(INPUT_GET, 'cpf', FILTER_SANITIZE_NUMBER_INT) ?? 
                filter_input(INPUT_POST, 'cpf', FILTER_SANITIZE_NUMBER_INT);
                
            $query = "SELECT * FROM tb_usuarios WHERE cpfUsuario = :cpf";
                $stmt = $pdo->prepare($query);
                $stmt->bindParam(':cpf', $cpf, PDO::PARAM_STR);
                $stmt->execute();

                if($stmt->rowCount()>0){
                    $userEdit = $stmt->fetch(PDO::FETCH_OBJ);
                }          
                

            ?>

        <div class="content" id="perfil" style="background-color: transparent"> 
            <form class="cadastro" method="post" action="cadastroAcoes.php" >
                <label>CPF:</label>
                <input type="text" required="" name="cpf" value="<?php echo isset($userEdit->cpfUsuario) ? htmlspecialchars($userEdit->cpfUsuario, ENT_QUOTES, 'UTF-8') : ''; ?>">
                
                <label>Nome Completo:</label>
                <input type="text" required="" name="nomeUsuario" value="<?php echo isset($userEdit->nomeUsuario) ? htmlspecialchars($userEdit->nomeUsuario, ENT_QUOTES, 'UTF-8') : ''; ?>">
                <br>
                <label>E-mail:</label>
                <input type="email" required="" name="email" value="<?php echo isset($userEdit->emailUsuario) ? htmlspecialchars($userEdit->emailUsuario, ENT_QUOTES, 'UTF-8') : ''; ?>">
                <br>
                <label for="countryCode">Selecione o código do país:</label>
                <?php
                try {
                    $pdo = new Config;
                    $stmt = $pdo->query("SELECT * FROM paises ORDER BY nome");
                    $paises = $stmt->fetchAll();
                } catch (PDOException $e) {
                    die("Erro ao buscar países: " . $e->getMessage());
                }
                ?>
                <select id="countryCode">
                    <?php foreach ($paises as $pais): ?>
                        <option value="<?= htmlspecialchars($pais->codigo_pais) ?>"
                            <?= ($pais->codigo_pais === '+55') ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pais->emoji) . " " . htmlspecialchars($pais->nome) . " (" . htmlspecialchars($pais->codigo_pais) . ")" ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label for=>Acrescente o número do telefone:</label>
                <input type="text" required placeholder="(XX) XXXXX-XXXX" name="telZap" value="<?php echo isset($userEdit->telZapUsuario) ? htmlspecialchars($userEdit->telZapUsuario, ENT_QUOTES, 'UTF-8') : ''; ?>">
                
                <br>
                <label>Nome animal de estimação:</label>
                <input type="text" required placeholder="Nome animal estimação" name="nmAnimal" value="<?php echo isset($userEdit->nomeAnimalUsuario) ? htmlspecialchars($userEdit->nomeAnimalUsuario, ENT_QUOTES, 'UTF-8') : ''; ?>">
               
                <br>
                <label>Nome escola:</label>
                <input type="text" required placeholder="Nome da primeira escola que estudou" name="nmEscola" value="<?php echo isset($userEdit->nomeEscolaUsuario) ? htmlspecialchars($userEdit->nomeEscolaUsuario, ENT_QUOTES, 'UTF-8') : ''; ?>">

                <br>
                <input type="submit" name="salvarEdicao" id="salvarEdicao" value="Salvar"/>
                <?php


?>
            </form>
        </div>
    </body>   
    <style> 
 
    </style>

        <style>
        body{
            font-family: Arial, sans-sarif;
            background-image: url(./imagens/logofatad.png);
            background-size: cover;
            /*background: #f7f7f7;*/
        }
        .cadastro{
            
            max-width: 300px;
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
        select {
            text-align: left;
            width: 100%;
            padding: 5px;
            border-radius: 3px;
            border: 1px solid #ccc;
            font-size: 12px;
        }

        option {
            text-align: left;
        }
    </style>
    <script>
        const arrayHeight = document.getElementsByClassName('height-nav');
        const navHeight = document.getElementsByTagName('nav')[0].clientHeight;

        for (let navHeightObj of arrayHeight)
            navHeightObj.style.height = navHeight + 'px';

        function abrirMenu() {
            const botoesMenu = document.getElementById('botoesMenu');

            botoesMenu.className = botoesMenu.className.includes('responsivo') ? '' : 'responsivo'
        }
    </script>
    
   

<script>
    document.getElementById("countryCode").addEventListener("change", function () {
        document.getElementById("telZap").value = this.value + " ";
    });

    document.getElementById("telZap").addEventListener("input", function (e) {
        let value = e.target.value.replace(/\D/g, ""); // Remove caracteres não numéricos
        let countryCode = document.getElementById("countryCode").value;

        if (value.startsWith(countryCode.replace("+", ""))) {
            value = value.substring(countryCode.length - 1);
        }

        if (value.length > 2) {
            value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
        }
        if (value.length > 10) {
            value = `${value.slice(0, 10)}-${value.slice(10, 14)}`;
        }

        e.target.value = countryCode + " " + value;
    });
</script>
</html>
