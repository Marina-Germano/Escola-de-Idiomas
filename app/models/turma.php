<?php
    require_once "config/conexao.php";

    class Turma {
        private $pdo;

        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($ididioma, $data_aula, $hora_aula, $capacidade_maxima, $sala, $idprofessor, $tipo_recorrencia = null) {
            $result = $this->pdo->prepare("INSERT INTO turma VALUES (null, ?, ?, ?, ?, ?, ?, ?)");
            return $result->execute([$ididioma, $data_aula, $hora_aula, $capacidade_maxima, $sala, $idprofessor, $tipo_recorrencia]);
        }

        public function alterar($idturma, $ididioma, $data_aula, $hora_aula, $capacidade_maxima, $sala, $idprofessor, $tipo_recorrencia = null) {
            $result = $this->pdo->prepare("UPDATE turma SET ididioma = ?, data_aula = ?, hora_aula = ?, capacidade_maxima = ?, sala = ?, idprofessor = ?, tipo_recorrencia = ? WHERE idturma = ?");
            return $result->execute([$ididioma, $data_aula, $hora_aula, $capacidade_maxima, $sala, $idprofessor, $tipo_recorrencia, $idturma]);
        }

        public function excluir($id) {
            $result = $this->pdo->prepare("DELETE FROM turma WHERE idturma = ?");
            return $result->execute([$id]);
        }

        public function listarTodos() {
            $result = $this->pdo->query("SELECT * FROM turma");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarId($id) {
            $result = $this->pdo->prepare("SELECT * FROM turma WHERE idturma = ?");
            $result->execute([$id]);
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }
?>
