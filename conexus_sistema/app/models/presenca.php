<?php
require_once __DIR__ . '/../config/conexao.php';

class Presenca {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function registrarPresenca($idaluno_turma, $presente) {
        $stmt = $this->pdo->prepare("SELECT * FROM presenca WHERE idaluno_turma = ?");
        $stmt->execute([$idaluno_turma]);

        if ($stmt->rowCount() > 0) {
            $update = $this->pdo->prepare("UPDATE presenca SET presente = ? WHERE idaluno_turma = ?");
            $update->execute([$presente, $idaluno_turma]);
        } else {
            $insert = $this->pdo->prepare("INSERT INTO presenca (idaluno_turm, presente) VALUES (?, ?, ?)");
            $insert->execute([$idaluno_turma, $presente]);
        }
    }
}
