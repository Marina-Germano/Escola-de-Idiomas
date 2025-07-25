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

public function cadastrar($idtipo_material, $ididioma, $idnivel, $idturma, $titulo, $descricao, $quantidade, $formato_arquivo, $idfuncionario) {
    $sql = "INSERT INTO material (
                idtipo_material, ididioma, idnivel, idturma, titulo,
                descricao, quantidade, formato_arquivo, idfuncionario
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        $idtipo_material, $ididioma, $idnivel, $idturma,
        $titulo, $descricao, $quantidade, $formato_arquivo, $idfuncionario
    ]);
}


        // public function cadastrar($idtipo_material, $ididioma, $idnivel, $idturma, $idtitulo, $descricao, $quantidade, $formato_arquivo, $idfuncionario) {
        //     $result = $this->pdo->prepare("INSERT INTO material VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        //     return $result->execute([$idtipo_material, $ididioma, $idnivel, $idturma, $idtitulo, $descricao, $quantidade, $formato_arquivo, $idfuncionario]);
        // }

        public function alterar($idmaterial, $idtipo_material, $ididioma, $idnivel, $idturma, $idtitulo, $descricao, $quantidade, $formato_arquivo, $idfuncionario) {
            $result = $this->pdo->prepare("UPDATE material SET idtipo_material = ?, ididioma = ?, idnivel = ?, idturma = ?, idtitulo = ?, descricao = ?, quantidade = ?, formato_arquivo, $idfuncionario = ? WHERE idmaterial = ?");
            return $result->execute([$idtipo_material, $ididioma, $idnivel, $idturma, $idtitulo, $descricao, $quantidade, $formato_arquivo, $idfuncionario, $idmaterial]);
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
    }
?>
