<?php
    require_once __DIR__ . '/../config/conexao.php';

    class TipoMaterial {
        private $pdo;

        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($descricao) {
            $result = $this->pdo->prepare("INSERT INTO tipo_material VALUES (null, ?)");
            return $result->execute([$descricao]);
        }

        public function alterar($idtipo_material, $descricao) {
            $result = $this->pdo->prepare("UPDATE tipo_material SET descricao = ? WHERE idtipo_material = ?");
            return $result->execute([$descricao, $idtipo_material]);
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

        public function buscarOuCriar($descricao) {
            $stmt = $this->pdo->prepare("SELECT idtipo_material FROM tipo_material WHERE descricao = ?");
            $stmt->execute([$descricao]);
            $res = $stmt->fetch();
            if ($res) {
                return $res['idtipo_material'];
            } else {
                $stmt = $this->pdo->prepare("INSERT INTO tipo_material (descricao) VALUES (?)");
                $stmt->execute([$descricao]);
                return $this->pdo->lastInsertId();
            }
        }

    }
?>
