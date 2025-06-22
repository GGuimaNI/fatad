<!DOCTYPE html>
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

<html lang="pt-br">
    <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=devide-width, initial-scale=1.0"
          <h1><title>CEP brasilia  </title></h1>
    <!--<stile>*{font-family: sans-serif;}</stile>-->
    </head>
   
    <body>
        
        <!--<form class="teste" method="POST" action="teste.php">-->
            <legend>Acesso Complementar</legend><br>

            <input type="text"  placeholder="CEP" name="cep" id="cep" onblur="buscaCep(this.value)" />
            <input type="text"  placeholder="ENDERECO" name="endereco" id="endereco" />
            <input type="text"  placeholder="BAIRRO" name="bairro" id="bairro" />
            <input type="text"  placeholder="CIDADE" name="cidade" id="cidade" />
            <input type="text"  placeholder="ESTADO" name="estado" id="estado" />
            <br>
            <input type="submit" name="cadastrarCurso" id="cadastrarCurso" value="Enviar"/>
            <?php
             
           $query = "select idDisciplina,nomeDisciplina,format(valorMatDisciplina, 'C', 'pt-br') as valor from tb_disciplinas where valorMatDisciplina <20";
                    $result = mysqli_query($conn, $query);
            
                    foreach($result as $row)
                    {
                        echo $fg->brl2decimal($row['valor'],2);
                        echo ' - -';
                        echo $row['valor']; 
                        echo ' - -';
                        ECHO number_format($row['valor'], 2, ',', '.');
                    }
            ?>;
            <center>
                <h4>
                <p><a href="index.php">Voltar</a></p>
                </h4>                           
            </center>
          
        


        
        <script>
        function buscaCep(cep){
                fetch('https://viacep.com.br/ws/'+cep+'/json/');
                    .then(rsponse->{
                        if(!response.ok){
                            console.log("erro de conexao");
                        }
                    }
                return response.json();
            )
            .then(data={
                console.log(data);
                endereco.value=data.logradouro;
                bairro.value=data.bairro;
                cidade.value=data.localidade;
                estado.value=data.uf;
            })
            .catch(error=>{
                console.log("Erro: ",error);
            })
        }
        
        </script>
    </body>    

</html>

