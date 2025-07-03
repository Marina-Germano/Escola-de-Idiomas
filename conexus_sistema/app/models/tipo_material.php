<?php
    require_once "config/conexao.php";

    class TipoMaterial {
        private $pdo;

        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($tipo) {
            $result = $this->pdo->prepare("INSERT INTO tipo_material VALUES (null, ?)");
            return $result->execute([$tipo]);
        }

        public function alterar($idtipo_material, $tipo) {
            $result = $this->pdo->prepare("UPDATE tipo_material SET tipo = ? WHERE idtipo_material = ?");
            return $result->execute([$tipo, $idtipo_material]);
        }

        public function excluir($id) {
            $result = $this->pdo->prepare("DELETE FROM tipo_material WHERE idtipo_material = ?");
            return $result->execute([$id]);
        }

        public function listarTodos() {
            $result = $this->pdo->query("SELECT * FROM tipo_material");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarId($id) {
            $result = $this->pdo->prepare("SELECT * FROM tipo_material WHERE idtipo_material = ?");
            $result->execute([$id]);
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }
?>
