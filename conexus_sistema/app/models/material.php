<?php
require_once __DIR__ . '/../config/conexao.php';

class Material {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($dados) {
        $sql = "INSERT INTO material 
        (idtipo_material, ididioma, idnivel, idturma, titulo, descricao, quantidade, formato_arquivo, arquivo) 
        VALUES 
        (:idtipo_material, :ididioma, :idnivel, :idturma, :titulo, :descricao, :quantidade, :formato_arquivo, :arquivo)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute($dados);
    }

    public function listar() {
        $sql = "SELECT m.*, tm.descricao AS tipo_material, i.descricao AS idioma, n.descricao AS nivel
                FROM material m
                JOIN tipo_material tm ON m.idtipo_material = tm.idtipo_material
                JOIN idioma i ON m.ididioma = i.ididioma
                JOIN nivel n ON m.idnivel = n.idnivel
                LEFT JOIN turma t ON m.idturma = t.idturma
                ORDER BY m.data_cadastro DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM material WHERE idmaterial = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function alterar($id, $dados) {
        $sql = "UPDATE material SET
                    idtipo_material = :idtipo_material,
                    ididioma = :ididioma,
                    idnivel = :idnivel,
                    idturma = :idturma,
                    titulo = :titulo,
                    descricao = :descricao,
                    quantidade = :quantidade,
                    formato_arquivo = :formato_arquivo,
                    arquivo = :arquivo
                WHERE idmaterial = :idmaterial";
        $stmt = $this->pdo->prepare($sql);
        $dados['idmaterial'] = $id;
        return $stmt->execute($dados);
    }

    public function excluir($id) {
        $sql = "DELETE FROM material WHERE idmaterial = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    public function listarMateriaisPorAluno($idaluno) {
        try {
            $sql = "SELECT
                    m.idmaterial,
                    m.titulo,
                    m.descricao,
                    m.quantidade,
                    m.formato_arquivo,
                    m.arquivo,
                    DATE_FORMAT(m.data_cadastro, '%d/%m/%Y') AS data_cadastro,
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
            $sql = "SELECT
                    m.idmaterial,
                    m.titulo,
                    m.descricao,
                    m.quantidade,
                    m.formato_arquivo,
                    m.arquivo,
                    DATE_FORMAT(m.data_cadastro, '%d/%m/%Y') AS data_cadastro,
                    u.nome AS professor_nome,
                    u.foto AS professor_foto,
                    t.imagem AS turma_imagem,
                    t.ididioma AS turma_ididioma,
                    i.descricao AS nome_idioma,
                    n.descricao AS nome_nivel,
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
                ORDER BY m.data_cadastro DESC
                LIMIT " . intval($limit);
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idaluno', $idaluno, PDO::PARAM_INT);
            //$stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
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
        try {
            $sql = "SELECT * FROM material WHERE idturma = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$idturma]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar materiais por turma: " . $e->getMessage());
            return [];
        }
    }

    public function vincularTurma($idmaterial, $idturma) {
    try {
        $sql = "UPDATE material SET idturma = :idturma WHERE idmaterial = :idmaterial";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':idturma', $idturma, PDO::PARAM_INT);
        $stmt->bindParam(':idmaterial', $idmaterial, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Erro: " . $e->getMessage();
        return false;
    }
}

public function listarNaoVinculados() {
    $sql = "SELECT * FROM material WHERE idturma IS NULL";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
