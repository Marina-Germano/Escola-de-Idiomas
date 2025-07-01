<?php
    require_once 'config/conexao.php';
    class TipoDocumento{
        private $pdo;

        public function __construct(){
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($idtipo_documento, $titulo_documento){
            $result = $this->pdo->prepare("INSERT INTO tipo_documento VALUES (null, ?, ?)");
            return $result->execute([$idtipo_documento, $titulo_documento]);
        }

        public function alterar($idtipo_documento, $titulo_documento){
            $result = $this->pdo->prepare("UPDATE tipo_documento SET tipo_documento = ?, titulo_documento = ? WHERE idtipo_documento = ?");
            return $result->execute([$idtipo_documento, $titulo_documento]);
        }

        public function excluir($id){
            $result = $this->pdo->prepare("DELETE FROM tipo_documento WHERE idtipo_documento = ?");
            return $result->execute([$id]);
        }

        public function listarTodos(){
            $result = $this->pdo->query("SELECT * FROM tipo_documento");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarId($id){
            $result = $this->pdo->prepare("SELECT * FROM tipo_documento WHERE idtipo_documento = ?");
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }
?>