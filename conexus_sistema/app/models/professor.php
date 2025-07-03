<?php
    require_once "config/conexao.php";

    class Professor {
        private $pdo;

        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($nome, $cpf, $datanascimento, $email, $telefone, $especialidade) {
            $result = $this->pdo->prepare("INSERT INTO professor VALUES (null, ?, ?, ?, ?, ?, ?)");
            return $result->execute([$nome, $cpf, $datanascimento, $email, $telefone, $especialidade]);
        }

        public function alterar($idprofessor, $nome, $cpf, $datanascimento, $email, $telefone, $especialidade) {
            $result = $this->pdo->prepare("UPDATE professor SET nome = ?, cpf = ?, datanascimento = ?, email = ?, telefone = ?, especialidade = ? WHERE idprofessor = ?");
            return $result->execute([$nome, $cpf, $datanascimento, $email, $telefone, $especialidade, $idprofessor]);
        }

        public function excluir($id) {
            $result = $this->pdo->prepare("DELETE FROM professor WHERE idprofessor = ?");
            return $result->execute([$id]);
        }

        public function listarTodos() {
            $result = $this->pdo->query("SELECT * FROM professor");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarId($id) {
            $result = $this->pdo->prepare("SELECT * FROM professor WHERE idprofessor = ?");
            $result->execute([$id]);
            return $result->fetch(PDO::FETCH_ASSOC);
        }

        public function buscarIdPorCpf($cpf) {
            $result = $this->pdo->prepare("SELECT idprofessor FROM professor WHERE cpf = ?");
            $result->execute([$cpf]);
            $dados = $result->fetch(PDO::FETCH_ASSOC);
            return $dados ? $dados['idprofessor'] : null;
        }

    }
?>
