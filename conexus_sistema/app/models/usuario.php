<?php
require_once __DIR__ . "/../config/conexao.php";


class Usuario {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    // Método para cadastrar um novo usuário
    public function cadastrar($nome, $telefone, $email, $data_nascimento, $cpf, $senha, $papel, $foto = null) {
        try {
            $sql = "INSERT INTO usuario (nome, telefone, email, data_nascimento, cpf, senha, papel, foto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->pdo->prepare($sql);
            // A senha deve ser criptografada antes de ser salva no banco de dados
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT); // Usando password_hash para segurança
            return $stmt->execute([$nome, $telefone, $email, $data_nascimento, $cpf, $senhaHash, $papel, $foto]);
        } catch (PDOException $e) {
            error_log("Erro ao cadastrar usuário: " . $e->getMessage());
            return false;
        }
    }

    // Método para alterar um usuário
    public function alterar($idusuario, $nome, $telefone, $email, $data_nascimento, $cpf, $senha, $papel, $ativo, $foto, $tentativas_login, $bloqueado) {
        try {
            $sql = "UPDATE usuario SET nome = ?, telefone = ?, email = ?, data_nascimento = ?, cpf = ?, senha = ?, papel = ?, ativo = ?, foto = ?, tentativas_login = ?, bloqueado = ? WHERE idusuario = ?";
            $stmt = $this->pdo->prepare($sql);
            $senhaHash = password_hash($senha, PASSWORD_DEFAULT); // Criptografa a senha novamente se for alterada
            return $stmt->execute([$nome, $telefone, $email, $data_nascimento, $cpf, $senhaHash, $papel, $ativo, $foto, $tentativas_login, $bloqueado, $idusuario]);
        } catch (PDOException $e) {
            error_log("Erro ao alterar usuário: " . $e->getMessage());
            return false;
        }
    }

    // Método para excluir um usuário
    public function excluir($idusuario) {
        try {
            $sql = "DELETE FROM usuario WHERE idusuario = ?";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([$idusuario]);
        } catch (PDOException $e) {
            error_log("Erro ao excluir usuário: " . $e->getMessage());
            return false;
        }
    }

    // Método para listar todos os usuários
    public function listarTodos() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM usuario");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar todos os usuários: " . $e->getMessage());
            return [];
        }
    }

    // Método para listar um usuário por ID
    public function listarId($idusuario) {
        try {
            $sql = "SELECT * FROM usuario WHERE idusuario = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$idusuario]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar usuário por ID: " . $e->getMessage());
            return null;
        }
    }

    // Método para buscar um usuário pelo CPF (usado para login)
    public function buscarPorCpf($cpf) {
        try {
            $sql = "SELECT * FROM usuario WHERE cpf = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$cpf]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar usuário por CPF: " . $e->getMessage());
            return null;
        }
    }

    // Método para buscar o idaluno a partir do idusuario
    public function buscarAlunoPorIdUsuario($idusuario) {
        try {
            $sql = "SELECT idaluno FROM aluno WHERE idusuario = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$idusuario]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar idaluno por idusuario: " . $e->getMessage());
            return null;
        }
    }

    // Método para listar funcionários por cargo (ex: 'Professor')
    public function listarFuncionariosPorCargo($cargo) {
        try {
            $sql = "SELECT u.idusuario, u.nome, f.cargo, f.especialidade FROM usuario u JOIN funcionario f ON u.idusuario = f.idusuario WHERE u.papel = 'funcionario' AND f.cargo = ?";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$cargo]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar funcionários por cargo: " . $e->getMessage());
            return [];
        }
    }
}
