<?php
require_once "config/conexao.php";

class DocumentoAluno {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($idaluno, $idtipo_documento, $caminho_arquivo, $observacoes, $status_documento) {
        $result = $this->pdo->prepare("INSERT INTO documento_aluno
            (idaluno, idtipo_documento, caminho_arquivo, observacoes, status_documento)
            VALUES (?, ?, ?, ?, ?)");
        return $result->execute([$idaluno, $idtipo_documento, $caminho_arquivo, $observacoes, $status_documento]);
    }

    public function alterar($iddocumento, $caminho_arquivo, $observacoes, $status_documento) {
        $result = $this->pdo->prepare("UPDATE documento_aluno
            SET caminho_arquivo = ?, observacoes = ?, status_documento = ? WHERE iddocumento = ?");
        return $result->execute([$caminho_arquivo, $observacoes, $status_documento, $iddocumento]);
    }

    public function excluir($iddocumento) {
        $result = $this->pdo->prepare("DELETE FROM documento_aluno WHERE iddocumento = ?");
        return $result->execute([$iddocumento]);
    }

    public function listarTodos() {
        $result = $this->pdo->query("SELECT da.*, a.idusuario, td.descricao AS tipo FROM documento_aluno da
            JOIN aluno a ON a.idaluno = da.idaluno JOIN tipo_documento td ON td.idtipo_documento = da.idtipo_documento");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarId($iddocumento) {
        $result = $this->pdo->prepare("SELECT da.*, a.idusuario, td.descricao AS tipo FROM documento_aluno da
            JOIN aluno a ON a.idaluno = da.idaluno JOIN tipo_documento td ON td.idtipo_documento = da.idtipo_documento WHERE da.iddocumento = ?");
        $result->execute([$iddocumento]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}
?>
