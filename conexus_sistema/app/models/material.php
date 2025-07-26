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
        $sql = "SELECT m.*, tm.descricao AS tipo_material, i.descricao AS idioma, n.descricao AS nivel, t.descricao AS turma
                FROM material m
                JOIN tipo_material tm ON m.idtipo_material = tm.idtipo_material
                JOIN idioma i ON m.ididioma = i.ididioma
                JOIN nivel n ON m.idnivel = n.idnivel
                JOIN turma t ON m.idturma = t.idturma
                ORDER BY m.data_cadastro DESC";
        return $this->pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM material WHERE idmaterial = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $dados) {
        $sql = "UPDATE material SET
                    idtipo_material = :idtipo_material,
                    ididioma = :ididioma,
                    idnivel = :idnivel,
                    idturma = :idturma,
                    titulo = :titulo,
                    descricao = :descricao,
                    quantidade = :quantidade,
                    formato_arquivo = :formato_arquivo,
                    arquivo = :arquivo,
                    idfuncionario = :idfuncionario
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
}
