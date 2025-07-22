<?php
require_once __DIR__ . '/../config/conexao.php';

class Presenca {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function registrarPresenca($idaluno, $presente, $data) {
        $stmt = $this->pdo->prepare("SELECT * FROM presenca WHERE idaluno = ? AND data = ?");
        $stmt->execute([$idaluno, $data]);

        if ($stmt->rowCount() > 0) {
            $update = $this->pdo->prepare("UPDATE presenca SET presente = ? WHERE idaluno = ? AND data = ?");
            $update->execute([$presente, $idaluno, $data]);
        } else {
            $insert = $this->pdo->prepare("INSERT INTO presenca (idaluno, data, presente) VALUES (?, ?, ?)");
            $insert->execute([$idaluno, $data, $presente]);
        }
    }
}
