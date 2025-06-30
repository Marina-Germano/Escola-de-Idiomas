<?php
    require_once "config/conexao.php";

    class Aluno{
        private $pdo;

        public function __construct(){
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($nome, $cpf, $cep, $rua, $numero, $bairro, $complemento, $responsavel, $tel_responsavel, $datanascimento, $email, $telefone){  //ainda tem a SITUAÇÃO pra acrescentar aqui
            var_dump($nome); var_dump($cpf); var_dump($cep); var_dump($rua); var_dump($numero); var_dump($bairro);
            var_dump($complemento); var_dump($responsavel); var_dump($tel_responsavel); var_dump($datanascimento); var_dump($email); var_dump($telefone);
            $result = $this->pdo->prepare("INSERT INTO aluno VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            return $result->execute([$nome, $cpf, $cep, $rua, $numero, $bairro, $complemento, $responsavel, $tel_responsavel, $datanascimento, $email, $telefone]);
        }
    }
?>