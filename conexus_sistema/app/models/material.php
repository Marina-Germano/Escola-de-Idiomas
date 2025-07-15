<?php
    require_once "../config/conexao.php";

    class Material {
        private $pdo;

        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($idtipo_material, $ididioma, $idnivel, $idturma, $titulo, $descricao, $quantidade, $formato_arquivo, $arquivo, $idprofessor) {
            $result = $this->pdo->prepare("INSERT INTO material VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?, DEFAULT, ?)");
            return $result->execute([$idtipo_material, $ididioma, $idnivel, $idturma, $titulo, $descricao, $quantidade, $formato_arquivo, $arquivo, $idprofessor]);
        }

        public function alterar($idmaterial, $idtipo_material, $ididioma, $idnivel, $idturma, $titulo, $descricao, $quantidade, $formato_arquivo, $arquivo, $idprofessor) {
            $result = $this->pdo->prepare("UPDATE material SET idtipo_material = ?, ididioma = ?, idnivel = ?, idturma = ?, titulo = ?, descricao = ?, quantidade = ?, formato_arquivo = ?, arquivo = ?, idprofessor = ? WHERE idmaterial = ?");
            return $result->execute([$idtipo_material, $ididioma, $idnivel, $idturma, $titulo, $descricao, $quantidade, $formato_arquivo, $arquivo, $idprofessor, $idmaterial]);
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

        //Adicionando busca por aluno
        public function buscarPorAluno($idAluno) {
        $sql = "SELECT m.*, p.nome AS professor, p.foto AS foto_professor
                FROM material m
                JOIN professor p ON m.idprofessor = p.idprofessor
                JOIN turma t ON m.idturma = t.idturma
                JOIN aluno_turma at ON at.idturma = t.idturma
                WHERE at.idaluno = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$idAluno]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // No Model Material.php
        public function listarMateriaisPorAluno($idaluno) {
            $sql = "SELECT m.* FROM material m JOIN aluno_turma at ON m.idturma = at.idturma WHERE at.idaluno = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$idaluno]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>
