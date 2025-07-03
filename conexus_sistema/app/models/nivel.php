<?php
    require_once "config/conexao.php";

    class Nivel {
        private $pdo;

        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($descricao) {
            $result = $this->pdo->prepare("INSERT INTO nivel VALUES (null, ?)");
            return $result->execute([$descricao]);
        }

        public function alterar($idnivel, $descricao) {
            $result = $this->pdo->prepare("UPDATE nivel SET descricao = ? WHERE idnivel = ?");
            return $result->execute([$descricao, $idnivel]);
        }

        public function excluir($id) {
            $result = $this->pdo->prepare("DELETE FROM nivel WHERE idnivel = ?");
            return $result->execute([$id]);
        }

        public function listarTodos() {
            $result = $this->pdo->query("SELECT * FROM nivel");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarId($id) {
            $result = $this->pdo->prepare("SELECT * FROM nivel WHERE idnivel = ?");
            $result->execute([$id]);
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }
?>
