<<html>
    <?php 
        $servername = "localhost";
        $username = "root";
        $password="";
        $dbname= "fatadgestao";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if($conn->connect_error){
            die("Connection failed:".$conn->connect_error);
        }else{
            echo "Conectado verifica usuário";
        }
    ?>

    <title>Cadastrando usuário</title>
    
    <style>
        body{
            font-family: Arial, sans-sarif;
            background: #f7f7f7;
        }
        .login{
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
        input[type='text']{
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
            padding: 12px 20px;
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
        
        Faça seu login ou cadastre-se <a href="cadastro.php">aqui</a>;
        <form class="login" method="POST" action="verificausuario.php">
            <legend>LOGIN</legend>
            <label>Usuário:</label>
            <input type="text" name="nmLogin" id="nmLogin"/>
            <br>
            <input type="submit" name="logar" id="Verificar"/>
            <?php
                if(!empty($_POST['nmLogin'])){
//                    if(isset($_POST['nmLogin'])){
                        $nmLogin=$_POST['nmLogin'];
                        $usuario=$nmLogin;
                        $senha=$_POST['senha'];
                        $query = "SELECT * FROM tb_usuarios WHERE nmLogin = '$nmLogin' AND varPalavraBase = '$senha' ";
    //                     echo 'tem'.$query;
                        $result = mysqli_query($conn, $query);
                        foreach($result as $row)
                        {
                            $privilegio= $row['varPrivilegio'];
                        }
                        if (isset($privilegio)){
                            session_start();
                            $_SESSION['$usuario']=$nmLogin;
                            $_SESSION['senha']=$senha;
                            $_SESSION['privilegio']=$privilegio;
                            header('Location: entrou.php');
                            echo 'entrou com usuário cadastrado';
                        }else{
                            echo "Problema com senha e/ou usuário";
                        }
//                    }
                }    
            ?>  
        </form>
    </body>    
</html>