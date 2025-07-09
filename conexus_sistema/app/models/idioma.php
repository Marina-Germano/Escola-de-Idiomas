<?php
require_once "config/conexao.php";

class Idioma {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($descricao) {
        $result = $this->pdo->prepare("INSERT INTO idioma (descricao) VALUES (?)");
        return $result->execute([$descricao]);
    }

    public function alterar($ididioma, $descricao) {
        $result = $this->pdo->prepare("UPDATE idioma SET descricao = ? WHERE ididioma = ?");
        return $result->execute([$descricao, $ididioma]);
    }

    public function excluir($ididioma) {
        $result = $this->pdo->prepare("DELETE FROM idioma WHERE ididioma = ?");
        return $result->execute([$ididioma]);
    }

    public function listarTodos() {
        $result = $this->pdo->query("SELECT * FROM idioma");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarId($ididioma) {
        $result = $this->pdo->prepare("SELECT * FROM idioma WHERE ididioma = ?");
        $result->execute([$ididioma]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}
?>
