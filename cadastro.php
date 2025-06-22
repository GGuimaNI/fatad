<?php
session_start(); 
include_once 'fatadgestaoControler.php';
$fg=new fatadgestaoControler;
require_once 'config.php'; // Arquivo de conexão com PDO
?>;

<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <!--<title>Menu Responsivo</title>-->
        <title>FATAD</title>
        <link rel="stylesheet" href="./styles.css">
        
    </head>
        <body>
            <!-- <?php
            // if (isset($_SESSION['message'])) {
            //     echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
            //     unset($_SESSION['message']); // Limpa a mensagem após exibição
            //}?> -->

        <div class="content" id="perfil" style="background-color: transparent"> 
            <div class="height-nav"></div>
                    <br>
            <form class="cadastro" method="post" action="cadastroAcoes.php" >
                <label>CPF:</label>
                <input type="text" required="" name="cpf" value="<?php echo isset($_SESSION['cpf']) ? htmlspecialchars($_SESSION['cpf'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                
                <label>Nome Completo:</label>
                <input type="text" required="" name="nomeUsuario" value="<?php echo isset($_SESSION['nomeUsuario']) ? htmlspecialchars($_SESSION['nomeUsuario'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                <br>
                <label>E-mail:</label>
                <input type="email" required="" name="email" value="<?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8') : ''; ?>">
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
                        <option value="<?= htmlspecialchars($pais['codigo_pais']) ?>"
                            <?= ($pais['codigo_pais'] === '+55') ? 'selected' : '' ?>>
                            <?= htmlspecialchars($pais['emoji']) . " " . htmlspecialchars($pais['nome']) . " (" . htmlspecialchars($pais['codigo_pais']) . ")" ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <label for=>Acrescente o número do telefone:</label>
                <input type="text" required placeholder="(XX) XXXXX-XXXX" name="telZap" value="<?php echo isset($_SESSION['telZap']) ? htmlspecialchars($_SESSION['telZap'], ENT_QUOTES, 'UTF-8') : ''; ?>">
                
                <br>
                <label>Senha:</label>
                <input type="password" required name="senha" placeholder="No mínimo 8 caracteres (letras e números)." >
                
                <br>
                <label>Nome animal de estimação:</label>
                <input type="text" required placeholder="Nome animal estimação" name="nmAnimal" value="<?php echo isset($_SESSION['nmAnimal']) ? htmlspecialchars($_SESSION['nmAnimal'], ENT_QUOTES, 'UTF-8') : ''; ?>">
               
                <br>
                <label>Nome escola:</label>
                <input type="text" required placeholder="Nome da primeira escola que estudou" name="nmEscola" value="<?php echo isset($_SESSION['nmEscola']) ? htmlspecialchars($_SESSION['nmEscola'], ENT_QUOTES, 'UTF-8') : ''; ?>">

                <br>
                <input type="submit" name="btn" id="btn" value="Cadastrar"/>
                <!--<input type="submit" name="btn" id="btn" value="Login"/>-->

                <?php


?>
            </form>
        </div>
    </body>   

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
