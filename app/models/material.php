<?php
    require_once "config/conexao.php";

    class Material {
        private $pdo;

        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($idtipo_material, $ididioma, $idnivel, $titulo, $descricao, $quantidade, $formato_arquivo, $link_download, $idprofessor = null) {
            $result = $this->pdo->prepare("INSERT INTO material VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?, DEFAULT, ?)");
            return $result->execute([$idtipo_material, $ididioma, $idnivel, $titulo, $descricao, $quantidade, $formato_arquivo, $link_download, $idprofessor]);
        }

        public function alterar($idmaterial, $idtipo_material, $ididioma, $idnivel, $titulo, $descricao, $quantidade, $formato_arquivo, $link_download, $idprofessor = null) {
            $result = $this->pdo->prepare("UPDATE material SET idtipo_material = ?, ididioma = ?, idnivel = ?, titulo = ?, descricao = ?, quantidade = ?, formato_arquivo = ?, link_download = ?, idprofessor = ? WHERE idmaterial = ?");
            return $result->execute([$idtipo_material, $ididioma, $idnivel, $titulo, $descricao, $quantidade, $formato_arquivo, $link_download, $idprofessor, $idmaterial]);
        }

        public function excluir($id) {
            $result = $this->pdo->prepare("DELETE FROM material WHERE idmaterial = ?");
            return $result->execute([$id]);
        }

        public function listarTodos() {
            $result = $this->pdo->query("SELECT * FROM material");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarId($id) {
            $result = $this->pdo->prepare("SELECT * FROM material WHERE idmaterial = ?");
            $result->execute([$id]);
            return $result->fetch(PDO::FETCH_ASSOC);
        }

        // Lista materiais de um professor específico
        public function listarPorProfessor($idprofessor) {
            $result = $this->pdo->prepare("SELECT * FROM material WHERE idprofessor = ?");
            $result->execute([$idprofessor]);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        // Busca materiais com filtro por idioma, nível e tipo (nível e tipo são opcionais)
        public function buscarMateriais($ididioma, $idnivel = null, $idtipo_material = null) {
            $query = "SELECT * FROM material WHERE ididioma = ?";
            $params = [$ididioma];

            if ($idnivel !== null) {
                $query .= " AND idnivel = ?";
                $params[] = $idnivel;
            }

            if ($idtipo_material !== null) {
                $query .= " AND idtipo_material = ?";
                $params[] = $idtipo_material;
            }

            $result = $this->pdo->prepare($query);
            $result->execute($params);
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>
