<?php
require_once "config/conexao.php";

class AlunoTurma {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($idaluno, $idturma, $data_matricula) {
        $result = $this->pdo->prepare("INSERT INTO aluno_turma VALUES (null, ?, ?, ?)");
        return $result->execute([$idaluno, $idturma, $data_matricula]);
    }

    public function alterar($idaluno_turma, $idaluno, $idturma, $data_matricula) {
        $result = $this->pdo->prepare("UPDATE aluno_turma SET idaluno = ?, idturma = ?, data_matricula = ? WHERE idaluno_turma = ?");
        return $result->execute([$idaluno, $idturma, $data_matricula, $idaluno_turma]);
    }

    public function excluir($id) {
        $result = $this->pdo->prepare("DELETE FROM aluno_turma WHERE idaluno_turma = ?");
        return $result->execute([$id]);
    }

    public function listarTodos() {
        $result = $this->pdo->query("SELECT * FROM aluno_turma");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarId($id) {
        $result = $this->pdo->prepare("SELECT * FROM aluno_turma WHERE idaluno_turma = ?");
        $result->execute([$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}
?>
