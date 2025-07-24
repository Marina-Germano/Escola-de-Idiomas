<?php
    require_once __DIR__ . '/../config/conexao.php';

    class Material {
        private $pdo;

        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

        public function getPDO() {
            return $this->pdo;
        }


        public function cadastrar($idtipo_material, $ididioma, $idnivel, $idturma, $titulo, $descricao, $quantidade, $formato_arquivo, $arquivo, $idfuncionario) {
            $result = $this->pdo->prepare("INSERT INTO material VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?, DEFAULT, ?)");
            return $result->execute([$idtipo_material, $ididioma, $idnivel, $idturma, $titulo, $descricao, $quantidade, $formato_arquivo, $arquivo, $idfuncionario]);
        }

        public function alterar($idmaterial, $idtipo_material, $ididioma, $idnivel, $idturma, $titulo, $descricao, $quantidade, $formato_arquivo, $arquivo, $idfuncionario) {
            $result = $this->pdo->prepare("UPDATE material SET idtipo_material = ?, ididioma = ?, idnivel = ?, idturma = ?, titulo = ?, descricao = ?, quantidade = ?, formato_arquivo = ?, arquivo = ?, idfuncionario = ? WHERE idmaterial = ?");
            return $result->execute([$idtipo_material, $ididioma, $idnivel, $idturma, $titulo, $descricao, $quantidade, $formato_arquivo, $arquivo, $idfuncionario, $idmaterial]);
        }

        public function excluir($id) {
            $result = $this->pdo->prepare("DELETE FROM material WHERE idmaterial = ?");
            return $result->execute([$id]);
        }

        public function listarTodos() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM material");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar todos os materiais: " . $e->getMessage());
            return [];
        }
    }
        public function listarId($id) {
            $result = $this->pdo->prepare("SELECT * FROM material WHERE idmaterial = ?");
            $result->execute([$id]);
            return $result->fetch(PDO::FETCH_ASSOC);
        }

        // Lista materiais de um funcionário específico
        public function listarPorFuncionario($idfuncionario) {
        try {
            $sql = "SELECT * FROM material WHERE idfuncionario = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$idfuncionario]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar materiais por funcionário: " . $e->getMessage());
            return [];
        }
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
        public function buscarPorAluno($idAluno) {
        $sql = "SELECT m.*, f.nome AS professor, f.foto AS foto_professor, u.nome AS prodessor_nome, t.imagem AS turma_imagem, i.descricao AS nome_idioma, n.descricao AS nome_nivel, tm.descricao AS tipo_material
                FROM material m
                JOIN funcionario f ON m.idfuncionario = f.idfuncionario
                JOIN turma t ON m.idturma = t.idturma
                JOIN aluno_turma at ON at.idturma = t.idturma
                WHERE at.idaluno = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$idAluno]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // No Model Material.php
        public function listarMateriaisPorAluno($idaluno) {
        try {
            $sql = "SELECT m.*, DATE_FORMAT(m.data_cadastro, '%d/%m/%Y') AS data_cadastro,
                    u.nome AS professor_nome,
                    u.foto AS professor_foto,
                    t.imagem AS turma_imagem,
                    t.ididioma AS turma_ididioma,
                    i.descricao AS idioma,
                    n.descricao AS nivel,
                    tm.descricao AS tipo_material
                FROM material m
                JOIN turma t ON m.idturma = t.idturma
                JOIN aluno_turma at ON t.idturma = at.idturma
                JOIN usuario u ON m.idfuncionario = u.idusuario
                JOIN idioma i ON m.ididioma = i.ididioma
                JOIN nivel n ON m.idnivel = n.idnivel
                JOIN tipo_material tm ON m.idtipo_material = tm.idtipo_material
                WHERE at.idaluno = :idaluno
                GROUP BY m.idmaterial, u.nome, u.foto, t.imagem, t.ididioma, i.descricao, n.descricao, tm.descricao
                ORDER BY m.data_cadastro DESC;";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idaluno', $idaluno, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("DEBUG Material::listarMateriaisPorAluno - Materiais encontrados para idaluno " . $idaluno . ": " . count($results));
            return $results;

        } catch (PDOException $e) {
            error_log("Erro ao listar materiais por aluno: " . $e->getMessage());
            return ['error' => "Erro ao carregar os materiais. Por favor, tente novamente mais tarde."];
        }
    }

        public function getUltimosMateriaisPorAluno($idaluno, $limit = 6) {
        try {
            $sql = "SELECT m.*, DATE_FORMAT(m.data_cadastro, '%d/%m/%Y') AS data_cadastro,
                    u.nome AS professor_nome, u.foto AS professor_foto, t.imagem AS turma_imagem, t.ididioma AS turma_ididioma, i.descricao AS nome_idioma, n.descricao AS nome_nivel, tm.descricao AS tipo_material
                FROM material m
                JOIN turma t ON m.idturma = t.idturma
                JOIN aluno_turma at ON t.idturma = at.idturma
                JOIN usuario u ON m.idfuncionario = u.idusuario
                JOIN idioma i ON m.ididioma = i.ididioma
                JOIN nivel n ON m.idnivel = n.idnivel
                JOIN tipo_material tm ON m.idtipo_material = tm.idtipo_material
                WHERE at.idaluno = :idaluno
                GROUP BY m.idmaterial, u.nome, u.foto, t.imagem, t.ididioma, i.descricao, n.descricao, tm.descricao
                ORDER BY m.data_cadastro DESC
                LIMIT :limit;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idaluno', $idaluno, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("DEBUG Material::getUltimosMateriaisPorAluno - " . count($results) . " últimos materiais encontrados para idaluno " . $idaluno);
            return $results;
        } catch (PDOException $e) {
            error_log("ERRO Material::getUltimosMateriaisPorAluno: " . $e->getMessage());
            return [];
        }
    }
        public function listarPorTurma($idturma) {
        $sql = "SELECT * FROM material WHERE idturma = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$idturma]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
