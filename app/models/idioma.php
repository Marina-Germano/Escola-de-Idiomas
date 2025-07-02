<?php
    require_once "config/conexao.php";

    class Idioma {
        private $pdo;

        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($idioma) {
            $result = $this->pdo->prepare("INSERT INTO idioma VALUES (null, ?)");
            return $result->execute([$idioma]);
        }

        public function alterar($ididioma, $idioma) {
            $result = $this->pdo->prepare("UPDATE idioma SET idioma = ? WHERE ididioma = ?");
            return $result->execute([$idioma, $ididioma]);
        }

        public function excluir($id) {
            $result = $this->pdo->prepare("DELETE FROM idioma WHERE ididioma = ?");
            return $result->execute([$id]);
        }

        public function listarTodos() {
            $result = $this->pdo->query("SELECT * FROM idioma");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarId($id) {
            $result = $this->pdo->prepare("SELECT * FROM idioma WHERE ididioma = ?");
            $result->execute([$id]);
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }
?>
