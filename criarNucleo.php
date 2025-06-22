
<?php
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
include_once './conexao.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">        
        <title>FATADGestão</title>
        <link rel="stylesheet" href="./css/styles.css">
<style>
        body{
            font-family: Arial, sans-sarif;
            background-image: url(./imagens/logofatad.png);
            background-size: cover;
        }
        .criarNucleo{    
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
        
        input[type='SELECT']{
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

    
    <body>
        <nav>
            <div class="logo">
                <h1>                
                    <a><img src="./imagens/LogoFatadSF.png" width="25px" height="alt"></a>              
                    Criando Núcleos FATAD
                </h1>
            </div>
            <button onclick="abrirMenu()">&#9776;</button>
            <ul id="botoesMenu">
                <a href="./htmlCursos.html">
                    <li>
                        <p>Voltar</p>
                    </li>
                </a>
                <a href="./index.html">
                    <li>
                        <p>Menu</p>
                    </li>
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
            <form class="criarNucleo" method="post" action="criarNucleo.php" >
                <label class="form-label">CEP:</label>
                <input type="text" name="cep" id="cep" class="form-control" />
                <label class="form-label">Nº:</label>
                <input type="number" name="numero" id="numero" class="form-control" placeholder="Número da casa ou apartamento." />
                <label class="form-label">Complemento:</label>
                <input type="text" name="complemento" id="complemento" class="form-control" placeholder="Ponto de referência, se for o caso." maxwidth="10"/>
                <label class="form-label">Rua:</label>
                <input type="text" name="rua" id="rua" class="form-control"/>

                <label class="form-label">Bairro:</label>
                <input type="text" name="bairro" id="bairro" class="form-control"" />
                <label class="form-label">Cidade:</label>
                <input type="text" name="cidade" id="cidade" class="form-control" />
                <label class="form-label">Estado:</label>
                <input type="text" name="estado" id="estado" class="form-control" />
                 
                <label>Núcleo:</label>
                <input type="text" placeholder="Coloque o nome do núcleo" required="" name="descNucleo" id="descNucleo"/>
                <br>
                <label>Responsável:</label>
                <input type="text" placeholder="Informe o nome do responsával" required="" name="responsavalNucleo" id="responsavalNucleo"/>
                <br>
                <label>CPF Resp:</label>
                <input type="text" placeholder="Informe o cpf do responsával" required="" name="cpfResp" id="cpfResp"/>
                <br>
                <label>Telefone:</label>
                <input type="text" placeholder="Somente números" required="" name="telZap" id="telZap"/>
                <br>
                <label>E-mail:</label>
                <input type="email" required="" placeholder="Melhor e-mail" name="email" id="email"/>
                <br>
                <br>
                
                <input type="submit" color="blue" name="criarNucleo" id="criarNucleo" value="Enviar"/>


                    <?php
                    
                      if(isset($_POST['descNucleo'])){
                        $descNucleo=$_POST['descNucleo'];
                        $cepNucleo= $_POST['cep'];
                        $enderecoNucleo=$_POST['rua']." Nr: ".$_POST['numero']." ".$_POST['complemento'].".  ".$_POST['bairro']  ;
                        $cidadeUF=$_POST['cidade']."-".$_POST['estado'];
                        $responsavalNucleo=$_POST['responsavalNucleo'];
                        $telZap=$_POST['telZap'];
                        $email=$_POST['email'];
                                  
                            $sql = "INSERT INTO tb_nucleofatad(descNucleo, enderecoNucleo, cidadeUF, nomeRespNucleo, telZap,email)"
                            ."VALUES ('$descNucleo','$enderecoNucleo','$cidadeUF','$responsavalNucleo','$telZap','$email')";

//                          var_dump($sql);
                            if ($conn->query($sql)==TRUE){
                                echo "Incluído ".strstr($descNucleo, ' ', true);
                            }else{
                                echo 'Não funcionou o cadastro';
                            }
                       }                    
                    ?>

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
