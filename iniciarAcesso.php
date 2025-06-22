<?php
session_start(); 
// Incluir a conexão com o banco de dados
include_once 'config.php';
$pdo = new Config();
include_once './fatadgestaoControler.php';
$fg = new fatadgestaoControler;
$usuarioSelecionado=null;
$retorno="";


if (isset($_SESSION['usuario_autenticado'])) { 
    $privilegio = $_SESSION['privilegio'];
    $usuario=$_SESSION['usuario']; 
    //Adicione lógica baseada no privilégio do usuário 
    if($privilegio=="opFatad"){
        include('./index.html');
        $rsNucleos = $fg->findNucleo();
    }elseif($privilegio=="admFatad"){
        include('./index.html');
    }elseif($privilegio=="opNuc"){
        include('./barOpNuc.php');
        $rsNucleos = $fg->findNucleoCpf($usuario);
    }elseif($privilegio=='Visitante'){
        include('./barVisitante.html');    
    }else { 
        header('Location: logout.php'); 
    exit(); 
} 
}
?>
<!-- //Início do Modal -->
<!DOCTYPE html>
<br><br>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Alterar Nível de Acesso</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

<body>
    <?php
    $cpf = $fg->getCpfId();
    // Verifica se aluno já está matriculado.  Se não estiver, abre o Modal.
    $stmt = $pdo->prepare("SELECT 1 FROM tb_matricula AS m 
                    JOIN tb_aluno AS a ON m.idAluno = a.idAluno
                    WHERE a.cpfAluno = :cpfAluno
                    ");
            $stmt->execute(['cpfAluno' => $cpf]);
    if (!$stmt->fetch()) {?>
        <div id="minhaModal" class="modal">
            <div class="modal-content">
                <h5 style="color: blue;" class="modal-title">Atenção:</h5>
                <p>- O aluno ainda não foi matriculado em nenhum curso. <br> 
                - É recomendável fazer a matrícula primeiro, para evitar esquecimento, pois o aluno deixará de constar da página inicial, embora isto possa ser adiado. <br>- Em caso de adiameno, a matrícula poderá ser realizada pelo menu Alunos/Matricular. <br> 
                - Escolha sua opção!</p>
                <button class="botao" onclick="prosseguir()">Alterar Nível de Acesso e Concluir, mesmo assim.</button>
                <button class="botao" onclick="irParaOutraPagina()">Matricular Aluno antes, e voltar à página de abertura para Concluir.</button>
            </div>
        </div>
    <?php
    }
    ?>




<!-- //Fim Modal -->
</head>
<?php



if ($cpf !== null) {
    $usu=$fg->findUsuarioCpf($cpf);
    if($usu){
        $usuarioSelecionado=$usu['idUsuario'];
        $retorno="ListVisitantes";
    }
}

    // Query de usuários (modo administrativo)
    $query_usuarios = "SELECT idUsuario, nomeUsuario FROM tb_usuarios ORDER BY nomeUsuario";
    $stmt = $pdo->query($query_usuarios);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // Query para obter núcleos
    $query_nucleos = "SELECT idNucleo, descNucleo FROM tb_nucleofatad ORDER BY descNucleo";
    $stmt_nucleos = $pdo->query($query_nucleos);
    $nucleos = $stmt_nucleos->fetchAll(PDO::FETCH_ASSOC);

?>


<body>
<div class="container mt-5">
       <?php 
       if (isset($_SESSION['message'])) {
           echo '<div class="alert alert-success">' . $_SESSION['message'] . '</div>';
           unset($_SESSION['message']); // Limpa a mensagem após exibição
       }
       ?>
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
                <h4 style="color: blue" >Alterar Nível de Acesso
                <!-- <a href="criarTurmasGRUD.php" class="btn btn-danger float-end">Voltar</a> -->
              </h4>
            </div>
            <div class="card-body">
    <form action="iniciarAcessoPro.php" method="post">
           <!-- //usuário -->
        <div class="form-group">
            <label for="usuario">Selecione o Usuário:</label>
            <select name="usuario" id="usuario" class="form-control" required>
                <option value="" disabled <?= empty($usuarioSelecionado) ? 'selected' : '' ?>>Selecione um usuário</option>
                <?php foreach ($usuarios as $usuario): ?>
                    <option value="<?= $usuario['idUsuario'] ?>" 
                        <?= ($usuarioSelecionado == $usuario['idUsuario']) ? 'selected' : '' ?>>
                        <?= $usuario['nomeUsuario'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- //Nível de acesso -->
        <div class="form-group">
            <label>Tipo de Operador:</label>
            <select name="varPrivilegio" id="varPrivilegio" class="form-control" required onchange="toggleNivelAcesso2()">
                <option value="" disabled selected>Selecione o tipo de operador:</option>
                <option value="opFatad">OpFatad</option>
                <option value="opNuc">OpNuc</option>
                <option value="opAluno">OpAluno</option>
                <option value="visitante">Visitante</option>
            </select>
        </div>
        
        <div class="form-group" id="nivel_acesso_2_div" style="display: none;">
            <label>Escolha um Núcleo:</label>
            <select name="idNucleo" id="idNucleo" class="form-control">
                <option value="" disabled selected>Selecione o Núcleo</option>
                <?php foreach ($nucleos as $nucleo): ?>
                    <option value="<?php echo $nucleo['idNucleo']; ?>"><?php echo $nucleo['descNucleo']; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <input type='hidden' name='retorno' value="<?= $retorno ?>">
        <button type="submit" class="btn btn-primary">Alterar Nível de Acesso</button>
    </form>
</div>
</div>
</div>
</div>
</div>
</div>
<style>
    .modal {
      display: block; /* Exibe a modal logo no início */
      position: fixed;
      z-index: 999;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.5);
    }
    .modal-content {
      background-color: #fff;
      margin: 8% auto;
      padding: 10px;
      width: 600px;
      border-radius: 5px;
      text-align: left;
    }

  .botao {
    background-color: #0069d9;
    color: white;
    border: none;
    padding: 12px 24px;
    margin: 10px;
    border-radius: 6px;
    font-size: 16px;
    font-weight: 500;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    transition: background-color 0.3s, transform 0.2s;
  }

  .botao:hover {
    background-color: #004ea8;
    transform: scale(1.03);
  }

  .botao:active {
    transform: scale(0.98);
  }


  </style>
<script>
        function toggleNivelAcesso2() {
            const nivelAcesso1 = document.getElementById('varPrivilegio').value;
            const nivelAcesso2Div = document.getElementById('nivel_acesso_2_div');
            const nivelAcesso2 = document.getElementById('idNucleo');
            
            if (nivelAcesso1 === 'opNuc') {
                nivelAcesso2Div.style.display = 'block';
                nivelAcesso2.setAttribute('required', 'required');
            } else {
                nivelAcesso2Div.style.display = 'none';
                nivelAcesso2.removeAttribute('required');
                nivelAcesso2.value = ''; // Resetar o valor
            }
        }
    </script>
    <script>
        function prosseguir() {
            document.getElementById("minhaModal").style.display = "none";
            document.getElementById("meuForm").style.display = "block";
        }

        function irParaOutraPagina() {
            const cpf = "<?php echo htmlspecialchars($cpf, ENT_QUOTES, 'UTF-8'); ?>";
            window.location.href = "cadastroMatricularAluno.php?cpf=" + cpf;
        }
    </script>

</body>
</html>
