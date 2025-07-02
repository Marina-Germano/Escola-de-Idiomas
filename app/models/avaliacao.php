<?php
require_once "config/conexao.php";

class Avaliacao {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($idaluno_turma, $tipo_avaliacao, $titulo, $data_avaliacao, $nota, $peso = 1.0, $observacao = null) {
        $result = $this->pdo->prepare("INSERT INTO avaliacao VALUES (null, ?, ?, ?, ?, ?, ?)");
        return $result->execute([$idaluno_turma, $tipo_avaliacao, $titulo, $data_avaliacao, $nota, $peso, $observacao]);
    }

    public function alterar($idavaliacao, $idaluno_turma, $tipo_avaliacao, $titulo, $data_avaliacao, $nota, $peso = 1.0, $observacao = null) {
        $result = $this->pdo->prepare("UPDATE avaliacao SET idaluno_turma = ?, tipo_avaliacao = ?, titulo = ?, data_avaliacao = ?, nota = ?, peso = ?, observacao = ? WHERE idavaliacao = ?");
        return $result->execute([$idaluno_turma, $tipo_avaliacao, $titulo, $data_avaliacao, $nota, $peso, $observacao, $idavaliacao]);
    }

    public function excluir($id) {
        $result = $this->pdo->prepare("DELETE FROM avaliacao WHERE idavaliacao = ?");
        return $result->execute([$id]);
    }

    public function listarTodos() {
        $result = $this->pdo->query("SELECT * FROM avaliacao");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarId($id) {
        $result = $this->pdo->prepare("SELECT * FROM avaliacao WHERE idavaliacao = ?");
        $result->execute([$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}
?>
