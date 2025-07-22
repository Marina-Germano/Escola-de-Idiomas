<?php
require_once __DIR__ . '/../config/conexao.php';

class AlunoTurma {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($idaluno, $idturma, $data_matricula = null) {
        if ($data_matricula) {
            $result = $this->pdo->prepare("INSERT INTO aluno_turma (idaluno, idturma, data_matricula) VALUES (?, ?, ?)");
            return $result->execute([$idaluno, $idturma, $data_matricula]);
        } else {
            $result = $this->pdo->prepare("INSERT INTO aluno_turma (idaluno, idturma) VALUES (?, ?)");
            return $result->execute([$idaluno, $idturma]);
        }
    }

    public function alterar($idaluno_turma, $idaluno, $idturma, $data_matricula) {
        $result = $this->pdo->prepare("UPDATE aluno_turma SET idaluno = ?, idturma = ?, data_matricula = ? WHERE idaluno_turma = ?");
        return $result->execute([$idaluno, $idturma, $data_matricula, $idaluno_turma]);
    }

    public function excluir($idaluno_turma) {
        $result = $this->pdo->prepare("DELETE FROM aluno_turma WHERE idaluno_turma = ?");
        return $result->execute([$idaluno_turma]);
    }

    public function listarTodos($idturma) {
    $sql = "SELECT at.*, a.nome AS nome_aluno, t.descricao AS nome_turma
            FROM aluno_turma at
            JOIN aluno a ON a.idaluno = at.idaluno
            JOIN turma t ON t.idturma = at.idturma
            WHERE at.idturma = :idturma";

    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':idturma', $idturma, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

    public function listarId($idaluno_turma) {
        $result = $this->pdo->prepare("SELECT * FROM aluno_turma WHERE idaluno_turma = ?");
        $result->execute([$idaluno_turma]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}
?>
