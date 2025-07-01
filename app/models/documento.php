<?php
    require_once "config/conexao.php";

    class DocumentoAluno {
        private $pdo;

        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($idtipo_documento, $nome_arquivo, $caminho_arquivo, $observacoes, $status_documento) {
            $result = $this->pdo->prepare("INSERT INTO documento_aluno VALUES (null, ?)");
            return $result->execute([$idtipo_documento,$nome_arquivo, $caminho_arquivo, $observacoes, $status_documento]);
        }

        public function alterar($idaluno, $idtipo_documento, $nome_arquivo, $caminho_arquivo, $observacoes, $status_documento) {
            $result = $this->pdo->prepare("UPDATE documento_aluno SET  nome_arquivo = ?, caminho_arquivo = ?, observacoes = ?,
            status_documento = ? WHERE iddocumento_aluno = ?");
            return $result->execute([$idaluno, $idtipo_documento, $nome_arquivo, $caminho_arquivo, $observacoes, $status_documento]);
        }

        public function excluir($id) {
            $result = $this->pdo->prepare("DELETE FROM documento_aluno WHERE iddocumento_aluno = ?");
            return $result->execute([$id]);
        }

        public function listarTodos() {
            $result = $this->pdo->query("SELECT * FROM documento_aluno");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarId($id) {
            $result = $this->pdo->prepare("SELECT * FROM documento_aluno WHERE iddocumento_aluno = ?");
            $result->execute([$id]);
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }
?>
