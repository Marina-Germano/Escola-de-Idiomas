<?php
    require_once 'config/conexao.php';
    class Documento{
        private $pdo;

        public function __construct(){
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($idaluno, $idtipo_documento, $nome_arquivo, $caminho_arquivo, $observacoes, $status_documento){
            $result = $this->pdo->prepare("INSERT INTO documento VALUES (null, ?, ?)");
            return $result->execute([$idaluno, $idtipo_documento, $nome_arquivo, $caminho_arquivo, $observacoes, $status_documento]);
        }

        public function alterar($iddocumento, $titulo_documento){
            $result = $this->pdo->prepare("UPDATE documento SET documento = ?, titulo_documento = ? WHERE iddocumento = ?");
            return $result->execute([$iddocumento, $titulo_documento]);
        }

        public function excluir($id){
            $result = $this->pdo->prepare("DELETE FROM documento WHERE iddocumento = ?");
            return $result->execute([$id]);
        }

        public function listarTodos(){
            $result = $this->pdo->query("SELECT * FROM documento");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarId($id){
            $result = $this->pdo->prepare("SELECT * FROM documento WHERE iddocumento = ?");
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }
?>