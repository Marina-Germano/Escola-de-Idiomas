<?php
require_once "config/conexao.php";

class Presenca {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($idaula, $idaluno, $presente = null, $observacao = null) {
        $result = $this->pdo->prepare("INSERT INTO presenca VALUES (null, ?, ?, ?, ?)");
        return $result->execute([$idaula, $idaluno, $presente, $observacao]);
    }

    public function alterar($idpresenca, $idaula, $idaluno, $presente = null, $observacao = null) {
        $result = $this->pdo->prepare("UPDATE presenca SET idaula = ?, idaluno = ?, presente = ?, observacao = ? WHERE idpresenca = ?");
        return $result->execute([$idaula, $idaluno, $presente, $observacao, $idpresenca]);
    }

    public function excluir($id) {
        $result = $this->pdo->prepare("DELETE FROM presenca WHERE idpresenca = ?");
        return $result->execute([$id]);
    }

    public function listarTodos() {
        $result = $this->pdo->query("SELECT * FROM presenca");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarId($id) {
        $result = $this->pdo->prepare("SELECT * FROM presenca WHERE idpresenca = ?");
        $result->execute([$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}
?>
