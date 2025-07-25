<?php
require_once __DIR__ . '/../config/conexao.php';

class Presenca {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function registrarPresenca($idaluno_turma, $idfuncionario, $presente) {
        // Verifica se já existe presença para o mesmo aluno_turma e data
        $stmt = $this->pdo->prepare("SELECT * FROM presenca WHERE idaluno_turma = ? AND data = CURDATE()");
        $stmt->execute([$idaluno_turma]);

        if ($stmt->rowCount() > 0) {
            // Atualiza se já existir
            $update = $this->pdo->prepare("UPDATE presenca SET presente = ?, idfuncionario = ? WHERE idaluno_turma = ? AND data = CURDATE()");
            $update->execute([$presente, $idfuncionario, $idaluno_turma]);
        } else {
            // Insere nova presença
            $insert = $this->pdo->prepare("INSERT INTO presenca (idaluno_turma, idfuncionario, presente, data) VALUES (?, ?, ?, CURDATE())");
            $insert->execute([$idaluno_turma, $idfuncionario, $presente]);
        }
    }

    public function alterar($idpresenca, $presente) {
        $stmt = $this->pdo->prepare("UPDATE presenca SET presente = ?, data = CURDATE() WHERE idpresenca = ?");
        return $stmt->execute([$presente, $idpresenca]);
    }

    public function excluir($idpresenca) {
        $stmt = $this->pdo->prepare("DELETE FROM presenca WHERE idpresenca = ?");
        return $stmt->execute([$idpresenca]);
    }

    public function listarPresencasPorTurma($idturma) {
        $stmt = $this->pdo->prepare("SELECT
                p.idpresenca,
                p.data,
                p.presente,
                u.nome AS aluno,
                t.descricao AS turma
            FROM presenca p
            JOIN aluno_turma at ON at.idaluno_turma = p.idaluno_turma
            JOIN aluno a ON a.idaluno = at.idaluno
            JOIN usuario u ON u.idusuario = a.idusuario
            JOIN turma t ON t.idturma = at.idturma
            WHERE t.idturma = ?
            ORDER BY p.data DESC, u.nome");
        $stmt->execute([$idturma]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarTodos() {
        $stmt = $this->pdo->prepare("SELECT
                p.idpresenca,
                p.data,
                p.presente,
                u.nome AS aluno,
                t.descricao AS turma
            FROM presenca p
            JOIN aluno_turma at ON at.idaluno_turma = p.idaluno_turma
            JOIN aluno a ON a.idaluno = at.idaluno
            JOIN usuario u ON u.idusuario = a.idusuario
            JOIN turma t ON t.idturma = at.idturma
            ORDER BY p.data DESC, u.nome");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
