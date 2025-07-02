<?php
require_once "config/conexao.php";

class Nota {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($idaluno, $idturma, $nota) {
        $result = $this->pdo->prepare("INSERT INTO nota VALUES (null, ?, ?, ?)");
        return $result->execute([$idaluno, $idturma, $nota]);
    }

    public function alterar($idnota, $idaluno, $idturma, $nota) {
        $result = $this->pdo->prepare("UPDATE nota SET idaluno = ?, idturma = ?, nota = ? WHERE idnota = ?");
        return $result->execute([$idaluno, $idturma, $nota, $idnota]);
    }

    public function excluir($id) {
        $result = $this->pdo->prepare("DELETE FROM nota WHERE idnota = ?");
        return $result->execute([$id]);
    }

    public function listarTodos() {
        $result = $this->pdo->query("SELECT * FROM nota");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarId($id) {
        $result = $this->pdo->prepare("SELECT * FROM nota WHERE idnota = ?");
        $result->execute([$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}
?>
