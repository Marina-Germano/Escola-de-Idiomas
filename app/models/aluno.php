<?php
    require_once "config/conexao.php";

    class Aluno{
        private $pdo;

        public function __construct(){
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($nome, $cpf, $cep, $rua, $numero, $bairro, $complemento, $responsavel, $tel_responsavel, $datanascimento, $email, $telefone, $situacao){  //ainda tem a SITUAÇÃO pra acrescentar aqui
            $result = $this->pdo->prepare("INSERT INTO aluno VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            return $result->execute([$nome, $cpf, $cep, $rua, $numero, $bairro, $complemento, $responsavel, $tel_responsavel, $datanascimento, $email, $telefone, $situacao]);
        }

        public function alterar($nome, $cpf, $cep, $rua, $numero, $bairro, $complemento, $responsavel, $tel_responsavel, $datanascimento, $email, $telefone){
            $result = $this->pdo->prepare("UPDATE aluno SET nome = ?, cpf = ?, cep = ?, rua = ?, numero = ?, bairro = ?, complemento = ?, responsavel = ?, tel_responsavel = ?, datanascimento = ?, email = ?, telefone = ? WHERE idaluno = ?");
            return $result->execute([$$nome, $cpf, $cep, $rua, $numero, $bairro, $complemento, $responsavel, $tel_responsavel, $datanascimento, $email, $telefone]);
        }

        public function excluir($id){
            $result = $this->pdo->prepare("DELETE FROM aluno WHERE idaluno = ?");
            return $result->execute([$id]);
        }

        public function listarTodos(){
            $result = $this->pdo->query("SELECT * FROM aluno");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarId($id){
            $result = $this->pdo->prepare("SELECT * FROM aluno WHERE idaluno = ?");
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }
?>