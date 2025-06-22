
<?php
//var_dump($_SESSION);
include_once './config.php';
/**
 * Todo preparo de conexão ocorrerá aqui
 *
 * @author GGuima
 */
class fatadgestaoModel {

    private $ativo;

    function listUsuario(){
        $pdo = new Config();
        $select = $pdo->prepare("Select cpfUsuario as cpf, nomeUsuario as nome, emailUsuario as email FROM tb_usuarios");
        $select->execute();
        return $select->fetchAll(PDO::FETCH_OBJ); 
    }

    function carregarAlunos($curso){
        $pdo = new Config();
        $select = $pdo->prepare("Select idAluno, nomeAluno from tb_aluno WHERE nomeAluno like '%$curso%'");
        $select->execute();
        return $select->fetchAll(PDO::FETCH_OBJ); 
    }
    
    function findCurriculo($curso) {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT idCursoCurriculo as idc,idDisciplinaCurriculo as idd " 
                       ." FROM tb_curriculo_Disciplinar"
                       ." WHERE idCursoCurriculo='$curso'");

            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    
    function findInicioFimCurso($idTurma) {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT dtInicioCurso,dtTerminoCurso FROM tb_turma " 
                            ."WHERE idTurma='$idTurma'");

            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    
    function findAlunoEspecifico($idAluno) {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT  idAluno,nomeAluno,cpfAluno FROM tb_aluno WHERE idAluno=:id");
            $select->bindParam(':id', $idAluno, PDO::PARAM_INT);
            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ); 
    }
    function findAlunoComTurma($cpf) {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT  idAluno,nomeAluno,cpfAluno FROM tb_aluno WHERE cpfAluno=:cpf");
            $select->bindParam(':cpf', $cpf, PDO::PARAM_INT);
            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ); 
    }
    
    function findUsuarioEspecifico($cpfUsuario) {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT * FROM tb_usuarios WHERE cpfUsuario=:cpf");
            $select->bindParam(':cpf', $cpfUsuario, PDO::PARAM_STR);
            $select->execute();
            return $select->fetch(PDO::FETCH_OBJ); 
    }
    function findMatrAlunoEspecifico($idAluno, $idTurma) {

            $pdo = new Config();
            $select = $pdo->prepare("SELECT * FROM tb_matricula WHERE idAluno=$idAluno and idTurma=$idTurma");

            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ); 
    }
    
    function findAluno() {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT  idAluno,nomeAluno FROM tb_aluno ORDER BY nomeAluno ");

            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ);            
    }

    function findAlunosNucleo($idNucleo) {
        $pdo = new Config();
        $select = $pdo->prepare("SELECT  idAluno,nomeAluno FROM tb_aluno WHERE idCadastro=$idNucleo  ORDER BY nomeAluno ");

        $select->execute();
        return $select->fetchAll(PDO::FETCH_OBJ);            
    }

    function findAlunoCpf($cpf) {
        $pdo = new Config();
        $cpf = preg_replace('/[^0-9]/', '', $cpf); // Mantém apenas números

        $select = $pdo->prepare("SELECT * FROM tb_aluno WHERE cpfAluno = :cpf");
        $select->bindParam(':cpf', $cpf, PDO::PARAM_STR);
        $select->execute();

        return $select->fetchAll(PDO::FETCH_OBJ);
    }
  
    function findAlunoCurso() {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT distinct ha.idAluno, ha.idCurso, " 
                ."(SELECT  nomeAluno FROM tb_aluno where idAluno=ha.idAluno) as nomeAluno, "
                ."(SELECT  nomeCurso FROM tb_cursos where idCurso=ha.idCurso) as nomeCurso "
                ."FROM tb_historico_aluno as ha;");

            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ);            
    }

    function findAlunoRecebeMaterial($idAluno,$idTurma) {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT opcao FROM tb_matricula WHERE idAluno=:idAluno  AND idTurma= :idTurma");
            $select->bindParam(':idAluno', $idAluno, PDO::PARAM_STR);
            $select->bindParam(':idTurma', $idTurma, PDO::PARAM_STR);
            $select->execute();
            return $select->fetch(PDO::FETCH_OBJ);            
    }
    
    function findCursoTurma() {
                $pdo = new Config();
                $select = $pdo->prepare("select distinctrow idCursoCurriculo as idCurso, "
                        ."(select tb_cursos.nomeCurso from tb_cursos "
                        ."where tb_cursos.idCurso=tb_curriculo_disciplinar.idCursoCurriculo) as nomeCurso "
                        ."from tb_curriculo_disciplinar");

                $select->execute();
                return $select->fetchAll(PDO::FETCH_OBJ);            
    }
        
    function findCursosNivelCpf($cpfResp) {
            $pdo = new Config();
                $select = $pdo->prepare("SELECT c.idCurso, CONCAT(c.nomeCurso, ' (', c.nivelCurso, ')') AS cursoNivel, c.nivelCurso "
                  ."FROM tb_turma AS t JOIN tb_nucleofatad AS n ON t.idNucleo = n.idNucleo "
                  ."JOIN tb_cursos AS c ON t.idCursoCurriculo = c.idCurso "
                ."WHERE n.cpfResp = '$cpfResp'");
            
            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    function findCursosNivel() {
        $pdo = new Config();
            $select = $pdo->prepare("SELECT idCurso, CONCAT(nomeCurso,' (',nivelCurso,')') as cursoNivel,nivelCurso FROM tb_cursos ");
        $select->execute();
        return $select->fetchAll(PDO::FETCH_OBJ);            
    }

    function findDisciplinasPorCurso($idCurso){
        $pdo = new Config();
                    $select = $pdo->prepare("SELECT *
                        FROM tb_disciplinas 
                        WHERE idCurso = :idCurso ORDER BY nomeDisciplina");

                        // Substitua o marcador de parâmetro pelo valor real de forma segura
        $select->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
        $select->execute();
        return $select->fetchAll(PDO::FETCH_OBJ);  
        
    }
    
    function findNivelCurso($idHistorico) {
        $pdo = new Config();
        $select = $pdo->prepare("SELECT h.idHistorico, h.idCurso, c.nivelCurso FROM tb_historico_aluno as h, tb_cursos as c "
                                ."WHERE h.idCurso=c.idCurso AND h.idHistorico=$idHistorico ");

        $select->execute();
        return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    
    function findCursoEspecifico($idCurso) {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT nomeCurso,nivelCurso "
                    ."FROM tb_cursos WHERE idCurso=$idCurso");

            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    
    function findDisciplinas($nivelDisciplina) {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT * FROM tb_disciplinas WHERE nivelDisciplina='$nivelDisciplina' ");

            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    function findDisciplinaEspecifica($idDisciplina) {
        $pdo = new Config();
    
        // Preparar a instrução SQL com o operador '=' para o parâmetro
        $select = $pdo->prepare("SELECT d.*, c.* 
        FROM tb_disciplinas AS d JOIN tb_cursos AS c ON d.idCurso = c.idCurso 
        WHERE d.idDisciplina = :idDisciplina");
    
        // Substituir o marcador de parâmetro pelo valor real de forma segura
        $select->bindParam(':idDisciplina', $idDisciplina, PDO::PARAM_INT);
    
        // Executar a instrução
        $select->execute();
    
        // Obter todos os resultados
        $result = $select->fetchAll(PDO::FETCH_ASSOC);
    
        return $result;
    }
    
    function findCaminhoDisciplinaEspecifica($idCurso, $idDisciplina) {
        $pdo = new Config();
        // Prepare a consulta usando um marcador de parâmetro
        $select = $pdo->prepare("SELECT * FROM tb_downdocs WHERE idCurso=:idCurso AND idDisciplina = :idDisciplina");

        // Substitua o marcador de parâmetro pelo valor real de forma segura
        $select->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
        $select->bindParam(':idDisciplina', $idDisciplina, PDO::PARAM_INT);


        // Execute a consulta
        $select->execute();

        // Recupere os resultados
        $result = $select->fetchAll(PDO::FETCH_ASSOC);

        return $result;          
    }
    function findCaminhoNomeDisciplinaEspecifica($idDowndocs) {
        $pdo = new Config();
        // Prepare a consulta usando um marcador de parâmetro
        $select = $pdo->prepare("SELECT * FROM tb_downdocs WHERE idDowndocs = :idDowndocs");

        // Substitua o marcador de parâmetro pelo valor real de forma segura
        $select->bindParam(':idDowndocs', $idDowndocs, PDO::PARAM_INT);

        // Execute a consulta
        $select->execute();

        // Recupere os resultados
        $result = $select->fetchAll(PDO::FETCH_ASSOC);

        return $result;          
    }
    function findMediaDisciplina($idTurma,$idCurso,$idNucleo,$idDisciplina) {

        $pdo = new Config();

        // Prepare a consulta com marcadores de parâmetros
        $select = $pdo->prepare("SELECT AVG(nota) AS media FROM tb_historico_aluno 
                                 WHERE idTurma = :idTurma AND idCurso = :idCurso AND idNucleo = :idNucleo AND idDisciplina = :idDisciplina");
        
        // Vincula os parâmetros às variáveis
        $select->bindParam(':idTurma', $idTurma, PDO::PARAM_INT);
        $select->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
        $select->bindParam(':idNucleo', $idNucleo, PDO::PARAM_INT);
        $select->bindParam(':idDisciplina', $idDisciplina, PDO::PARAM_INT);
        
        // Executa a consulta
        $select->execute();
        
        // Recupera os resultados
        $result = $select->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
                  
    }
    function findMediaAluno($cpfAluno){

        $pdo = new Config();

        // Prepare a consulta com marcadores de parâmetros
        $select = $pdo->prepare("SELECT AVG(nota) AS media 
        FROM tb_historico_aluno as h, tb_aluno as a 
        WHERE cpfAluno=:cpfAluno");
        
        // Vincula os parâmetros às variáveis
        $select->bindParam(':cpfAluno', $cpfAluno, PDO::PARAM_STR);

        
        // Executa a consulta
        $select->execute();
        
        // Recupera os resultados
        $result = $select->fetch(PDO::FETCH_ASSOC);
        
        return $result;
                  
    }

    function findNotaAluno($cpfAluno,$idCurso,$idDisciplina){

        $pdo = new Config();

        // Prepare a consulta com marcadores de parâmetros
        $select = $pdo->prepare("
            SELECT h.nota 
            FROM tb_historico_aluno AS h
            JOIN tb_aluno AS a ON h.idAluno = a.idAluno
            WHERE a.cpfAluno = :cpfAluno 
            AND h.idCurso = :idCurso 
            AND h.idDisciplina = :idDisciplina
        ");
        
        // Vincula os parâmetros às variáveis
        $select->bindParam(':cpfAluno', $cpfAluno, PDO::PARAM_STR);
        $select->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
        $select->bindParam(':idDisciplina', $idDisciplina, PDO::PARAM_INT);

        
        // Executa a consulta
        $select->execute();
        
        // Recupera os resultados
        $result = $select->fetch(PDO::FETCH_ASSOC);
        
        return $result;
                  
    }

    function findInadimplencia($idNucleo) {
        $pdo = new Config();
        $select = $pdo->prepare("SELECT COUNT(idOp) as total FROM tb_op_financeira
                                WHERE idResp=$idNucleo  AND dtPagamento IS null ");
        $select->execute();
        return $select->fetchAll(PDO::FETCH_ASSOC);            
    }
    
    function findNucleo($idNucleo=0) {
            $pdo = new Config();
            if($idNucleo=0){ 
                $select = $pdo->prepare("SELECT * FROM tb_nucleofatad Where idNucleo=$idNucleo ");
                
            }else{
               $select = $pdo->prepare("SELECT * FROM tb_nucleofatad ORDER BY descNucleo");
            }

            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    function findNrNucleo($idNucleo) {
        $pdo = new Config();
        
        $select = $pdo->prepare("SELECT nrNucleo FROM tb_nucleofatad WHERE idNucleo = :idNucleo");
        $select->bindParam(':idNucleo', $idNucleo, PDO::PARAM_INT);
        $select->execute();
        
        // Buscar um único registro e retornar apenas o valor numerico
        $result = $select->fetch(PDO::FETCH_ASSOC);
        
        return $result['nrNucleo']; // Retornar apenas o valor de nrNucleo
    }
    function findCPFusuario($idUsuario) {
        $pdo = new Config();
        
        $select = $pdo->prepare("SELECT idUsuario,cpfUsuario,nomeUsuario FROM tb_usuarios WHERE idUsuario = :idUsuario");
        $select->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
        $select->execute();
        
        // Buscar um único registro e retornar apenas o valor numerico
        $result = $select->fetch(PDO::FETCH_ASSOC);
        
        return $result;
    }

    function findUsuarioCpf($cpf) {
        $pdo = new Config();
        
        $select = $pdo->prepare("SELECT idUsuario,nomeUsuario FROM tb_usuarios WHERE cpfUsuario = :cpfUsuario");
        $select->bindParam(':cpfUsuario', $cpf, PDO::PARAM_INT);
        $select->execute();
        
        // Buscar um único registro e retornar apenas o valor numerico
        $result = $select->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    function findListVisitantes() {
        $pdo = new Config();
        $privilegio = "Visitante";
        $select = $pdo->prepare("SELECT * FROM tb_usuarios WHERE varPrivilegio = :varPrivilegio");
        $select->bindParam(':varPrivilegio', $privilegio, PDO::PARAM_STR);
        $select->execute();

        // Buscar todos de perfil Visitante
        $result = $select->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    function findListOpFinanceira($tipoOp) {
        $pdo = new Config();
        $select = $pdo->prepare("SELECT 
            a.cpfAluno, a.idAluno, a.nomeAluno, a.telZapAluno, 
            f.idOp, f.idMaterial, f.idTurma, f.idResp, f.tipoOp, 
            t.nomeDisciplina, h.idHistorico, m.opcao 
        FROM 
            tb_aluno AS a
            JOIN tb_op_financeira AS f ON a.cpfAluno = f.idResp
            JOIN tb_disciplinas AS t ON f.idMaterial = t.idDisciplina
            JOIN tb_historico_aluno AS h ON a.idAluno = h.idAluno AND h.idDisciplina = f.idMaterial
            LEFT JOIN tb_matricula AS m ON m.idAluno = a.idAluno
        WHERE f.tipoOp = :tipoOp");
        
        $select->bindParam(':tipoOp', $tipoOp, PDO::PARAM_STR);
        $select->execute();

        // Buscar todos de perfil Visitante
        $result = $select->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }

    function findNucleoCpf($cpfResp) {
            $pdo = new Config();
                $select = $pdo->prepare("SELECT * FROM tb_nucleofatad Where cpfResp='$cpfResp' ");

            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    
    function findNucleoEspecifico($idTurma) {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT idNucleo FROM fatadgestao.tb_turma where idTurma=$idTurma");

            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    function findDescNucleoEspecifico($idNucleo) {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT descNucleo FROM tb_nucleofatad where idNucleo=$idNucleo");

            $select->execute();
            return $select->fetchAll(PDO::FETCH_ASSOC);            
    }
    function findQtdAlunosNucleo($idNucleo) {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT distinct idAluno FROM tb_historico_aluno WHERE idNucleo=$idNucleo");

            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    
    function findSalaTurma($idCurso) {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT distinctrow t.idTurma,t.idNucleo, t.nomeSala, "
                    ."(SELECT nomeCurso FROM tb_cursos WHERE idCurso=t.idCursoCurriculo) as nomeCurso, "
                    ."(SELECT descNucleo FROM tb_nucleofatad WHERE idNucleo=t.idNucleo) as nomeNucleo "
            ."FROM tb_turma as t "
            ."WHERE ativo=0 and t.idCursoCurriculo=$idCurso");

            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ);            
    }

    function findSalaEspecifica($idNucleo, $nmSala) {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT nomeSala FROM tb_turma
                            WHERE idNucleo=$idNucleo and nomeSala='$nmSala'");
            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    
     function findCursoNucleo($idNucleo, $idCurso) {
            $pdo = new Config();
            $select = $pdo->prepare("SELECT *,(Select nomeCurso from tb_cursos where idCurso=t.idCursoCurriculo) as nomeCurso "
                                    . "FROM fatadgestao.tb_turma as t "
                                    . "WHERE idNucleo=$idNucleo and idCursoCurriculo=$idCurso");
            $select->execute();
            return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    
    
    function excluirCurriculoCurso($idCurso) {
            $pdo = new Config();

            $select = $pdo->prepare("DELETE FROM tb_curriculo_disciplinar where idCursoCurriculo=$idCurso");
               $select->execute();                

            return $select->fetchAll(PDO::FETCH_OBJ);
     }
     
     function transferirHisoricoAluno($idAluno,$idTurma,$idCurso) {
            $pdo = new Config();
            $select =$pdo->prepare("INSERT INTO tb_historico_aluno_excluido(idHistorico,idAluno,idNucleo,idTurma,dtIniEstudo,dtTerEstudo,idDisciplina,nota,situacao,dtExclusao) "
                    ."SELECT idHistorico,idAluno,idNucleo,idTurma,dtIniEstudo,dtTerEstudo,idDisciplina,nota,situacao,now() "
                    ."FROM tb_historico_aluno "
                    ."WHERE idAluno=$idAluno and idTurma=$idTurma and idCurso=$idCurso");
            $select->execute(); 
            return $select->fetchAll(PDO::FETCH_OBJ);
     }
    
     function excluirHisoricoAluno($idAluno,$idTurma,$idCurso) {
            $pdo = new Config();
            
            $select = $pdo->prepare("DELETE FROM tb_historico_aluno "
                        ."where idAluno=$idAluno and idTurma=$idTurma and idCurso=$idCurso");
            $select->execute();                

            return $select->fetchAll(PDO::FETCH_OBJ);
     }
    
     
     function criarCurriculoCurso($idCurso) {
            $pdo = new Config();

            $select = $pdo->prepare("INSERT INTO tb_curriculo_disciplinar (idCursoCurriculo,idDisciplinaCurriculo) "
                ."SELECT idCurso as idCursoCurriculo,idDisciplina as idDisciplinaCurriculo "
                ."FROM tb_disciplinas  WHERE idCurso =$idCurso and idDisciplina not in "
                    ."(SELECT idDisciplinaCurriculo FROM tb_curriculo_disciplinar  WHERE idCursoCurriculo=$idCurso)");

            $select->execute();

            return $select->fetchAll(PDO::FETCH_OBJ);
     }
     
    function findFichaHistorico($idAluno, $idTurma) {
        $pdo = new Config();

        $select = $pdo->prepare("SELECT tha.idTurma, tha.idHistorico, tha.idAluno,tha.idCurso,tha.idDisciplina,tha.situacao, "
                ."(SELECT nomeAluno from tb_aluno where idAluno=tha.idAluno) as nomeAluno, d.nomeDisciplina, "
                ."d.codigoDisciplina, d.creditoDisciplina, d.cargaHorariaDisciplina as cargaHoraria, "
               ."DATE_FORMAT (tha.dtIniEstudo, '%d/%m/%Y') as dtIniEstudo, "
               ."DATE_FORMAT (tha.dtTerEstudo, '%d/%m/%Y') as dtTerEstudo,tha.nota "
               ."FROM tb_historico_aluno as tha, tb_disciplinas as d "
               ."WHERE tha.idDisciplina=d.idDisciplina and idAluno=$idAluno AND idTurma=$idTurma "
               ."ORDER BY d.ordenacao");
               
        $select->execute();
        return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    function findFichaMatricula($idAluno) {
        $pdo = new Config(); // Certifique-se de que Config retorna um objeto PDO válido
        
        $select = $pdo->prepare("SELECT * FROM tb_aluno WHERE idAluno = :idAluno");
        $select->bindParam(':idAluno', $idAluno, PDO::PARAM_INT);
        
        $select->execute();
        return $select->fetch(PDO::FETCH_OBJ); // Usando fetch() para retornar um único objeto
    }
    
   function findCabecalhoFichaHistorico($idAluno, $idTurma) {
        $pdo = new Config();

         $select = $pdo->prepare("SELECT distinctrow t.idTurma, a.idAluno,m.idMatricula,c.idCurso, "
                        ."a.nomeAluno,a.dtNascAluno,a.nomePaiAluno,a.nomeMaeAluno,a.cidadeNatAluno,c.nomeCurso, "
                        ."(Select MAX(dtTerEstudo) from tb_historico_aluno where idAluno=m.idAluno and idCurso=c.idCurso) as dtTermCurso, "
                        ."(Select MIN(dtIniEstudo) from tb_historico_aluno where idAluno=m.idAluno and idCurso=c.idCurso) as dtIniCurso "
                        ."FROM tb_aluno as a, tb_turma as t, tb_matricula as m, tb_cursos as c "
                        ."WHERE a.idAluno=m.idAluno and m.idTurma=t.idTurma and t.idTurma=m.idTurma and c.idCurso=t.idCursoCurriculo "
                          ."and m.idAluno=$idAluno and t.idTurma=$idTurma");

        $select->execute();
        return $select->fetchAll(PDO::FETCH_OBJ);            
    } 

    function findBoletimNucleo($idTurma,$idCurso,$idNucleo,$idDisciplina){
        $pdo = new Config();

        $select = $pdo->prepare("SELECT idHistorico,idNucleo, idTurma, idAluno, situacao, "
                ."(SELECT nivelCurso FROM tb_cursos WHERE idCurso=h.idCurso) as nivelCurso, "
                ."(SELECT nomeAluno FROM tb_aluno WHERE idAluno = h.idAluno ) as nomeAluno, "
                ."(SELECT codigoDisciplina FROM tb_disciplinas WHERE idDisciplina = h.idDisciplina ) as codigoDisciplina, nota "
                ."FROM tb_historico_aluno AS h "
                ."WHERE h.idTurma=$idTurma and h.idCurso=$idCurso and h.idNucleo=$idNucleo and idDisciplina=$idDisciplina");
                
                $select->execute();
                return $select->fetchAll(PDO::FETCH_OBJ);  
    }


    function findOpFinanceiraRecibo($idMaterial, $idTurma,$idCurso) {
        $pdo = new Config();
       
            $select = $pdo->prepare("SELECT *, "
                ."(SELECT nomeCurso  FROM tb_turma,tb_cursos  WHERE idTurma=$idTurma and idCurso=$idCurso) as nomeCurso, "
                ."(SELECT nomeRespNucleo from tb_nucleofatad WHERE idNucleo =idResp and perfil='Núcleo') as nomeResponsavel, "
                ."(SELECT descNucleo from tb_nucleofatad WHERE idNucleo =idResp and perfil='Núcleo') as descNucleo "
                ."FROM tb_op_financeira "
                ."WHERE idMaterial=$idMaterial and  idTurma=$idTurma");
        

        $select->execute();
        return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    
    function findOpFinReciboNucleo($idOp) {
        $pdo = new Config();
        
            $select = $pdo->prepare("SELECT * "
                        ."FROM qry_funcfinanceirarecibonucleo "
                        ."WHERE idOp=$idOp");

        $select->execute();
        return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    function findidOpMax($perfil){
        $pdo = new Config();
        
            $select = $pdo->prepare("SELECT * FROM fatadgestao.tb_op_financeira "
                        ."WHERE idOp=(Select MAX(idOp) FROM fatadgestao.tb_op_financeira "
                        ."WHERE perfil='$perfil')");

        $select->execute();
        return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    
    function findOpFinReciboAluno($idOp) {
        $pdo = new Config();

            $select = $pdo->prepare("SELECT * "
                        ."FROM qry_funcfinanceirareciboaluno "
                        ."WHERE idOp=$idOp");

        $select->execute();
        return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    
    function findTudoDeAlunoEspecifico($idAluno) {
        $pdo = new Config();
        
            $select = $pdo->prepare("SELECT * FROM tb_aluno where idAluno=$idAluno");

        $select->execute();
        return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    
    function findTudoDeNucleoEspecifico($idNucleo) {
        $pdo = new Config();
        
            $select = $pdo->prepare("SELECT * FROM tb_nucleofatad where idNucleo=$idNucleo");

        $select->execute();
        return $select->fetchAll(PDO::FETCH_OBJ);            
    }
    
}