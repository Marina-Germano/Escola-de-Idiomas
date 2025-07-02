<?php
    require_once "config/conexao.php";

    class Funcionario {
        private $pdo;

        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($nome, $cpf, $datanascimento, $email, $telefone, $cargo, $turno) {
            $result = $this->pdo->prepare("INSERT INTO funcionario VALUES (null, ?, ?, ?, ?, ?, ?, ?)");
            return $result->execute([$nome, $cpf, $datanascimento, $email, $telefone, $cargo, $turno]);
        }

        public function alterar($idfuncionario, $nome, $cpf, $datanascimento, $email, $telefone, $cargo, $turno) {
            $result = $this->pdo->prepare("UPDATE funcionario SET nome = ?, cpf = ?, datanascimento = ?, email = ?, telefone = ?, cargo = ?, turno = ? WHERE idfuncionario = ?");
            return $result->execute([$nome, $cpf, $datanascimento, $email, $telefone, $cargo, $turno, $idfuncionario]);
        }

        public function excluir($id) {
            $result = $this->pdo->prepare("DELETE FROM funcionario WHERE idfuncionario = ?");
            return $result->execute([$id]);
        }

        public function listarTodos() {
            $result = $this->pdo->query("SELECT * FROM funcionario");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarId($id) {
            $result = $this->pdo->prepare("SELECT * FROM funcionario WHERE idfuncionario = ?");
            $result->execute([$id]);
            return $result->fetch(PDO::FETCH_ASSOC);
        }

        public function buscarIdPorCpf($cpf) {
            $result = $this->pdo->prepare("SELECT idfuncionario FROM funcionario WHERE cpf = ?");
            $result->execute([$cpf]);
            $dados = $result->fetch(PDO::FETCH_ASSOC);
            return $dados ? $dados['idfuncionario'] : null;
}

    }
?>
