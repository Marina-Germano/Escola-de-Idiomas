<?php
require_once "config/conexao.php";

class Contato {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($idusuario, $nome, $email, $motivo_contato = null, $observacoes = null) {
        $result = $this->pdo->prepare("INSERT INTO contato VALUES (?, ?, ?, ?, ?)");
        return $result->execute([$idusuario, $nome, $email, $motivo_contato, $observacoes]);
    }

    public function alterar($idusuario, $nome, $email, $motivo_contato = null, $observacoes = null) {
        $result = $this->pdo->prepare("UPDATE contato SET nome = ?, email = ?, motivo_contato = ?, observacoes = ? WHERE idusuario = ?");
        return $result->execute([$nome, $email, $motivo_contato, $observacoes, $idusuario]);
    }

    public function excluir($idusuario) {
        $result = $this->pdo->prepare("DELETE FROM contato WHERE idusuario = ?");
        return $result->execute([$idusuario]);
    }

    public function listarTodos() {
        $result = $this->pdo->query("SELECT * FROM contato");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarId($idusuario) {
        $result = $this->pdo->prepare("SELECT * FROM contato WHERE idusuario = ?");
        $result->execute([$idusuario]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}
?>
