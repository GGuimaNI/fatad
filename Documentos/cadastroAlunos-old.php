
<?php
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
?>
<?php 
        $servername = "localhost";
        $username = "root";
        $dbname= "fatadgestao";
        $password="";
        $conn = new mysqli($servername, $username, $password, $dbname);
        if($conn->connect_error){
            die("Connection failed:".$conn->connect_error);
        }
    ?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <title>FATADGestão</title>
        <link rel="stylesheet" href="./css/styles.css">
    </head>
    
        <style>
        body{
            font-family: Arial, sans-sarif;
            background-image: url(./imagens/logofatad.png);
            background-size: cover;
            /*background: #f7f7f7;*/
        }
        .cadastroAlunos{    
            max-width: 400px;
            margin: 0 auto;
            background-color: #fff;
            border-radius: 5px;
            padding: 25px;
            box-shadow: 0px 5px rgba(0,0,0,0.5);
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
        input[type='password']{
            width: 100%;
            padding: 5px;
            border-radius: 3px;
            border: 1px solid #ccc;
            margin-bottom: 3px;
            font-size: 12px;
        }
        input[type='email'],
            input[type='select']{
            width: 35%;
            padding: 5px;
            border-radius: 3px;
            border: 1px solid #ccc;
            margin-bottom: 3px;
            font-size: 12px;
        }
        input[type='number']{
            width: 35%;
            padding: 5px;
            border-radius: 3px;
            border: 1px solid #ccc;
            margin-bottom: 3px;
            font-size: 12px;
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

    
    <body>
        <nav>
            <div class="logo">
                <h1>                
                    <a><img src="./imagens/LogoFatadSF.png" width="30px" height="alt"></a>              
                    Cadastrando Alunos
                </h1>
            </div>
            <button onclick="abrirMenu()">&#9776;</button>
            <ul id="botoesMenu">
                <a href="./htmlAlunos.html">
                    <li>
                        <p>Voltar</p>
                    </li>
                </a>
                <a href="./index.html">
                    <li>
                        <p>Menu</p>
                    </li>
                </a>
                </a>
                <a href="./logout.php">
                    <li>
                        <p>Sair</p>
                    </li>
                </a>
            </ul>
        </nav>
        <div class="content" id="perfil" style="background-color: transparent"> 
            <div class="height-nav"></div>
            <br><br><br><br><br>
            <form class="cadastroAlunos" method="post" action="cadastroAlunos.php" >
                <label>Nome:</label>
                <input type="text" placeholder="Nome completo" required="" name="nomeAluno" id="nomeAluno" autofocus/>
                <br>
                <label>Natural de:</label>
                <input type="text" placeholder="Insira Cidade-UF" required="" name="cidadeNatal" id="cidadeNatal"/>

                <br>
                <label>Data de Nascimento:</label>
                <input type="date" required="" placeholder="dd/mm/AAAA" name="dtNasc" id="dtNasc" />
                <br>
                <label>Nome Pai:</label>
                <input type="text" required="" placeholder="Nome do pai completo" name="nomePai" id="nomePaiAluno" />
                <br>
                <label>Nome Mãe:</label>
                <input type="text" required="" placeholder="Nome da mãe completo" name="nomeMae" id="nomeMaeAluno" />
                <br>
                
                <label class="form-label">CEP:</label>
                <input type="text" name="cep" id="cep" class="form-control" />
                <label class="form-label">Nº:</label>
                <input type="text" name="numero" id="numero" class="form-control" />
                <label class="form-label">Rua:</label>
                <input type="text" name="rua" id="rua" class="form-control" />
                 <label>Complemento:</label>
                <input type="text" placeholder="Alguma referência do endereço" name="complemento" id="complemento"/>
                <label class="form-label">Bairro:</label>
                <input type="text" name="bairro" id="bairro" class="form-control" />
                <label class="form-label">Cidade:</label>
                <input type="text" name="cidade" id="cidade" class="form-control" />
                <label class="form-label">Estado:</label>
                <input type="text" name="estado" id="estado" class="form-control" maxlength="2" />


                <?php 
                if(isset($_POST['txtcep'])){
                    $txtEndereco=$_POST['rua'].", nº ". $_POST['numero']." ".$_POST['complemento'].". ".$_POST['bairro'] ;
                }
                ?>
                <?php // var_dump($_POST['txtcep']);?>
                <?php // var_dump($rs);?>
<!--                <label>Endereço:</label>
                <input type="text" required="" name="endereco" id="enderecoAluno" />-->
<!--                <br>
                <label>C:</label>
                <input type="text" placeholder="Cidade-UF" required="" name="cidadeEndereco" id="cidadeEndereco"/>                 -->
                <label>CPF:</label>
                <input type="number" required="" placeholder="Apenas números" name="cpfAluno" id="cpfAluno" />
                <label>Identidade:</label>
                <input type="number" required="" placeholder="Apenas números" name="idtAluno" id="idtAluno" />
                <label>Telefone:</label>
                <input type="number" required="" placeholder="Apenas números, com DDD" name="telZap" id="telZap"/>
                <label>E-mail:</label>
                <input type="email" required="" placeholder="Melhor e-mail" name="email" id="emailAluno"/>
                <br>
                <br>
                <input type="submit" color="blue" name="cadastrarAluno" id="cadastrarAluno" value="Enviar"/>


                <?php
                    
                      if(isset($_POST['nomeAluno'])){
                        $cpfAluno=$_POST['cpfAluno'];
                        $nomeAluno=$_POST['nomeAluno'];
                        $txtEndereco=$_POST['rua'].", nº ". $_POST['numero']." ".$_POST['complemento'].". ".$_POST['bairro'] ;
//                        var_dump($txtEndereco);
                        $cidadeMoradia=$_POST['cidade'];
                        $cep=$_POST['cep'];
                        $cidadeNatal=$_POST['cidadeNatal'];
                        $dtNasc = $_POST['dtNasc'];
                        $nomePai=$_POST['nomePai'];
                        $nomeMae=$_POST['nomeMae'];
                        $idtAluno=$_POST['idtAluno'];
                        $email=$_POST['email'];
                        $telZap=$_POST['telZap'];
                        
                        $query = "SELECT * FROM tb_aluno WHERE cpfAluno = '$cpfAluno'";
//                            var_dump($_POST);
                        $result = mysqli_query($conn, $query);
                        if(mysqli_num_rows($result)>0){
                            echo "CPF: ".$cpfAluno.' já existe.';
                        }else{
                            $sql = "INSERT INTO tb_aluno (cpfAluno,nomeAluno,enderecoAluno,cidadeMoradia,"
                                    . "cep,cidadeNatAluno,dtNascAluno,nomePaiAluno,nomeMaeAluno,idtAluno,"
                                    . "emailAluno,telZapAluno)"
                            ."VALUES ('$cpfAluno','$nomeAluno','$txtEndereco','$cidadeMoradia', '$cep','$cidadeNatal',"
                                ."'$dtNasc', '$nomePai','$nomeMae','$idtAluno', '$email','$telZap')";
                            if ($conn->query($sql)==TRUE){
                                echo strstr($nomeAluno, '', true).' foi cadastrado';
                            }else{
                                echo 'Não funcionou o cadastro';
                            }
                       }                    
                    }else{
                   }

                ?>
                <script>
             function buscaCep(cep){
                 fetch('https://viacep.com.br/ws/'+cep+'/json/).then(rsponse->{
                 .then(response=>{
                    if(!response.ok){
                             console.log("erro de conexao")
                    }
                 return response.json()
                 })
            
                .then(data={
                        console.log(data)
                        txtRua.value=data.logradouro
                })
                .catch(error=>{
                    console.log("Erro: ",error)
                })                           
            }   
        </script>

            </form>
        </div>
         
        <script src="js/code.jquery.com_jquery-3.7.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
            crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"
            integrity="sha512-d4KkQohk+HswGs6A1d6Gak6Bb9rMWtxjOa0IiY49Q3TeFd5xAzjWXDCBW9RS7m86FQ4RzM2BdHmdJnnKRYknxw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>

        <script src="js/cep.js"></script>
    </body>   
</html>
