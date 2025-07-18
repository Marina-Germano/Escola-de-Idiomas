<?php
require_once __DIR__ . '/../config/conexao.php';

class Usuario {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    // Cadastrar usuário com senha criptografada e foto
    public function cadastrar($nome, $telefone, $email, $data_nascimento, $cpf, $senha, $papel, $foto) {
        $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
        $result = $this->pdo->prepare(
            "INSERT INTO usuario (
                nome, telefone, email, data_nascimento, cpf, senha, papel, ativo, foto, tentativas_login, bloqueado
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, true, ?, 0, false
            )"
        );
        return $result->execute([
            $nome, $telefone, $email, $data_nascimento, $cpf, $senha_criptografada, $papel, $foto
        ]);
    }

    // Alterar usuário, incluindo alteração de foto e senha
    public function alterar($idusuario, $nome, $telefone, $email, $data_nascimento, $cpf, $senha, $papel, $foto, $ativo = true, $tentativas_login = 0, $bloqueado = false) {
        $senha_criptografada = password_hash($senha, PASSWORD_DEFAULT);
        $result = $this->pdo->prepare(
            "UPDATE usuario SET 
                nome = ?, telefone = ?, email = ?, data_nascimento = ?, cpf = ?, senha = ?, 
                papel = ?, ativo = ?, foto = ?, tentativas_login = ?, bloqueado = ? 
             WHERE idusuario = ?"
        );
        return $result->execute([
            $nome, $telefone, $email, $data_nascimento, $cpf, $senha_criptografada,
            $papel, $ativo, $foto, $tentativas_login, $bloqueado, $idusuario
        ]);
    }

    // Excluir usuário
    public function excluir($id) {
        $result = $this->pdo->prepare("DELETE FROM usuario WHERE idusuario = ?");
        return $result->execute([$id]);
    }

    // Listar todos os usuários com identificação do tipo
    public function listarTodos() {
        $sql = "
            SELECT
                u.idusuario,
                u.nome,
                u.email,
                u.telefone,
                u.cpf,
                u.papel,
                u.foto,
                CASE
                    WHEN a.idaluno IS NOT NULL THEN 'aluno'
                    WHEN p.idprofessor IS NOT NULL THEN 'professor'
                    WHEN f.idfuncionario IS NOT NULL THEN 'funcionario'
                    ELSE u.papel
                END AS tipo_usuario
            FROM usuario u
            LEFT JOIN aluno a ON u.idusuario = a.idusuario
            LEFT JOIN professor p ON u.idusuario = p.idusuario
            LEFT JOIN funcionario f ON u.idusuario = f.idusuario
        ";
        
        $result = $this->pdo->query($sql);
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    // Buscar usuário por CPF
    public function buscarPorCpf($cpf) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuario WHERE cpf = ?");
        $stmt->execute([$cpf]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Buscar usuário por ID
    public function listarId($id) {
        $result = $this->pdo->prepare("SELECT * FROM usuario WHERE idusuario = ?");
        $result->execute([$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}
?>
