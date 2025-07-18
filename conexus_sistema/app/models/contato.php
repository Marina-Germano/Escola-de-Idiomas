<?php
require_once __DIR__ . '/../config/conexao.php';

class Contato {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($idusuario, $nome, $email, $telefone, $arquivo = null, $motivo_contato, $mensagem) {
        $result = $this->pdo->prepare("INSERT INTO contato (idusuario, nome, email, telefone, arquivo, motivo_contato, mensagem) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $result->execute([$idusuario, $nome, $email, $telefone, $arquivo, $motivo_contato, $mensagem]);
    }

    public function alterar($idcontato, $idusuario, $nome, $email, $telefone, $arquivo = null, $motivo_contato, $mensagem) {
        $result = $this->pdo->prepare("UPDATE contato SET idusuario = ?, nome = ?, email = ?, telefone = ?, arquivo = ?, motivo_contato = ?, mensagem = ? WHERE idcontato = ?");
        return $result->execute([$idusuario, $nome, $email, $telefone, $arquivo, $motivo_contato, $mensagem, $idcontato]);
    }

    public function excluir($idcontato) {
        $result = $this->pdo->prepare("DELETE FROM contato WHERE idcontato = ?");
        return $result->execute([$idcontato]);
    }

    public function listarTodos() {
        $result = $this->pdo->query("SELECT * FROM contato");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarId($idcontato) {
        $result = $this->pdo->prepare("SELECT * FROM contato WHERE idcontato = ?");
        $result->execute([$idcontato]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}
?>
