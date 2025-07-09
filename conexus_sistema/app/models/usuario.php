<?php
require_once "config/conexao.php";

class Usuario {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($nome, $telefone, $email, $data_nascimento, $cpf, $senha, $papel) {
        $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
        $result = $this->pdo->prepare(
            "INSERT INTO usuario (nome, telefone, email, data_nascimento, cpf, senha, papel, ativo, tentativas_login, bloqueado)
            VALUES (?, ?, ?, ?, ?, ?, ?, true, 0, false)"
        );
        return $result->execute([$nome, $telefone, $email, $data_nascimento, $cpf, $senha_criptografada, $papel]);
    }

    public function alterar($idusuario, $nome, $telefone, $email, $data_nascimento, $cpf, $senha, $papel, $ativo = true, $tentativas_login = 0, $bloqueado = false) {
        $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
        $result = $this->pdo->prepare(
            "UPDATE usuario SET nome = ?, telefone = ?, email = ?, data_nascimento = ?, cpf = ?, senha = ?, papel = ?, 
            ativo = ?, tentativas_login = ?, bloqueado = ? WHERE idusuario = ?"
        );
        return $result->execute([
            $nome, $telefone, $email, $data_nascimento, $cpf, $senha_criptografada,
            $papel, $ativo, $tentativas_login, $bloqueado, $idusuario
        ]);
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

    public function buscarPorCpf($cpf) {
    $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE cpf = ?");
    $stmt->execute([$cpf]);
    return $stmt->fetch(PDO::FETCH_ASSOC); // retorna o usuário ou false
    }

    public function verificarLogin($cpf, $senha) {
        $result = $this->pdo->prepare("SELECT * FROM usuario WHERE cpf = ?");
        $result->execute([$cpf]);
        $usuario = $result->fetch(PDO::FETCH_ASSOC);

        if ($usuario && password_verify($senha, $usuario['senha'])) {
            return $usuario;
        } else {
            return false; // ou: return "Usuário ou senha inválidos.";
        }
    }
}
?>
