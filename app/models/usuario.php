<?php
    require_once "config/conexao.php";

    class Usuario {
        private $pdo;

        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($cpf, $senha, $papel, $idaluno = null, $idprofessor = null, $idfuncionario = null) {
            $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT); //password_hash é uma função do PHP que gera um hash seguro da senha
            $result = $this->pdo->prepare("INSERT INTO usuario VALUES (null, ?, ?, ?, ?, ?, ?, true, 0, false)");
            return $result->execute([$cpf, $senha_criptografada, $papel, $idaluno, $idprofessor, $idfuncionario]);
        }

        public function alterar($idusuario, $cpf, $senha, $papel, $idaluno = null, $idprofessor = null, $idfuncionario = null, $ativo = true, $tentativas_login = 0, $bloqueado = false) {
            $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
            $result = $this->pdo->prepare("UPDATE usuario SET cpf = ?, senha = ?, papel = ?, idaluno = ?, idprofessor = ?, idfuncionario = ?, ativo = ?, tentativas_login = ?, bloqueado = ? WHERE idusuario = ?");
            return $result->execute([$cpf, $senha_criptografada, $papel, $idaluno, $idprofessor, $idfuncionario, $ativo, $tentativas_login, $bloqueado, $idusuario]);
        }

        public function excluir($id) {
            $result = $this->pdo->prepare("DELETE FROM usuario WHERE idusuario = ?");
            return $result->execute([$id]);
        }

        public function listarTodos() {
            $result = $this->pdo->query("SELECT * FROM usuario");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarId($id) {
            $result = $this->pdo->prepare("SELECT * FROM usuario WHERE idusuario = ?");
            $result->execute([$id]);
            return $result->fetch(PDO::FETCH_ASSOC);
        }

        // Método auxiliar para verificar login (opcional)
        public function verificarLogin($cpf, $senha) {
            $result = $this->pdo->prepare("SELECT * FROM usuario WHERE cpf = ?");
            $result->execute([$cpf]);
            $usuario = $result->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($senha, $usuario['senha'])) {
                return $usuario;
            } else {
                return false; //print("Usuário ou senha inválidos."); melhor deixar false ou a mensagem?
            }
        }
    }
?>
