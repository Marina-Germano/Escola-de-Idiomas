<?php
require_once __DIR__ . '/../config/conexao.php';

class CalendarioAula {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($data_aula, $hora_inicio, $hora_fim, $idprofessor, $idturma, $idmaterial, $sala = null, $observacoes = null, $link_reuniao = null,$aula_extra = false) {
        $result = $this->pdo->prepare("INSERT INTO calendario_aula
            (data_aula, hora_inicio, hora_fim, idprofessor, idturma, idmaterial, sala, observacoes, link_reuniao, aula_extra)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $result->execute([$data_aula, $hora_inicio, $hora_fim, $idprofessor, $idturma, $idmaterial, $sala, $observacoes, $link_reuniao, $aula_extra]);
    }

    public function alterar($idaula, $data_aula, $hora_inicio, $hora_fim, $idprofessor, $idturma, $idmaterial, $sala = null, $observacoes = null, $link_reuniao = null, $aula_extra = false
    ) {
        $result = $this->pdo->prepare("UPDATE calendario_aula SET
            data_aula = ?, hora_inicio = ?, hora_fim = ?, idprofessor = ?, idturma = ?, idmaterial = ?, sala = ?, observacoes = ?, link_reuniao = ?, aula_extra = ? WHERE idaula = ?");
        return $result->execute([$data_aula, $hora_inicio, $hora_fim, $idprofessor, $idturma, $idmaterial, $sala, $observacoes, $link_reuniao, $aula_extra, $idaula]);
    }

    public function excluir($idaula) {
        $result = $this->pdo->prepare("DELETE FROM calendario_aula WHERE idaula = ?");
        return $result->execute([$idaula]);
    }

    public function listarTodos() {
        $result = $this->pdo->query("SELECT * FROM calendario_aula");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarId($idaula) {
        $result = $this->pdo->prepare("SELECT * FROM calendario_aula WHERE idaula = ?");
        $result->execute([$idaula]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
    // evetos aluno aula 
    public function getEventsAlunos($idusuario) {
        $sql = "SELECT ca.data_aula, ca.observacoes as title, ca.hora_inicio, ca.hora_fim FROM calendario_aula ca
                JOIN turma t ON ca.idturma = t.idturma
                JOIN aluno_turma at ON t.idturma = at.idturma
                JOIN aluno a ON at.idaluno = a.idaluno
                WHERE a.idusuario = :idusuario";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':idusuario' => $idusuario
        ]);

        $events = [];
        $count = 0;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $events[] = [
                'date' => $row['data_aula'],
                'title' => substr($row['hora_inicio'], 0, 5) . ' - ' . substr($row['hora_fim'], 0, 5) . ' | ' . $row['title']
            ];
            $count++;
        }
        return $events;
    }
    
    // buscar a próxima aula de um aluno específico  no home
    public function getProximaAulaPorAluno($idaluno) {
        $currentDateTime = date('Y-m-d H:i:s'); // pega a data e hora atual do servidor
        try {
            $sql = "SELECT
                        ca.data_aula, ca.hora_inicio, ca.hora_fim, ca.observacoes, ca.sala, 
                        t.descricao AS nome_turma, i.descricao AS nome_idioma, 
                        u.nome AS professor_nome, ca.link_reuniao
                    FROM
                        calendario_aula ca
                    JOIN
                        turma t ON ca.idturma = t.idturma
                    JOIN
                        aluno_turma at ON t.idturma = at.idturma
                    JOIN
                        idioma i ON t.ididioma = i.ididioma
                    JOIN
                        funcionario f ON ca.idfuncionario = f.idfuncionario
                    JOIN
                        usuario u ON f.idusuario = u.idusuario
                    WHERE
                        at.idaluno = :idaluno 
                        AND CONCAT(ca.data_aula, ' ', ca.hora_fim) > :current_datetime -- compara com a data e hora atual
                    ORDER BY ca.data_aula ASC, ca.hora_inicio ASC 
                    LIMIT 1;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':idaluno' => $idaluno,
                ':current_datetime' => $currentDateTime
            ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result;
        } catch (PDOException $e) {
            error_log("Erro ao buscar próxima aula: " . $e->getMessage()); 
            return null;
        }
    }
}
?>
