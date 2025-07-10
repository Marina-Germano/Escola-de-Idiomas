<?php
require_once "config/conexao.php";

class Professor {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($idfuncionario, $especialidade) {
        $result = $this->pdo->prepare(
            "INSERT INTO professor (idfuncionario, especialidade) VALUES (?, ?)"
        );
        return $result->execute([$idfuncionario, $especialidade]);
    }

    public function alterar($idprofessor, $especialidade) {
        $result = $this->pdo->prepare(
            "UPDATE professor SET especialidade = ? WHERE idprofessor = ?"
        );
        return $result->execute([$especialidade, $idprofessor]);
    }

    public function excluir($id) {
        $result = $this->pdo->prepare("DELETE FROM professor WHERE idprofessor = ?");
        return $result->execute([$id]);
    }

    public function listarTodos() {
        $result = $this->pdo->query(
            "SELECT p.*, f.cargo, u.nome, u.email, u.telefone
            FROM professor p
            JOIN funcionario f ON f.idfuncionario = p.idfuncionario
            JOIN usuario u ON u.idusuario = f.idusuario"
        );
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarId($id) {
        $result = $this->pdo->prepare(
            "SELECT p.*, f.cargo, u.nome, u.email, u.telefone
            FROM professor p
            JOIN funcionario f ON f.idfuncionario = p.idfuncionario
            JOIN usuario u ON u.idusuario = f.idusuario
            WHERE p.idprofessor = ?"
        );
        $result->execute([$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarIdPorFuncionario($idfuncionario) {
        $result = $this->pdo->prepare("SELECT idprofessor FROM professor WHERE idfuncionario = ?");
        $result->execute([$idfuncionario]);
        $dados = $result->fetch(PDO::FETCH_ASSOC);
        return $dados ? $dados['idprofessor'] : null;
    }

    // Novo método para buscar professor por CPF via funcionario e usuario
    public function buscarIdPorCpf($cpf) {
        $result = $this->pdo->prepare(
            "SELECT p.idprofessor 
            FROM professor p 
            JOIN funcionario f ON f.idfuncionario = p.idfuncionario 
            JOIN usuario u ON u.idusuario = f.idusuario 
            WHERE u.cpf = ?"
        );
        $result->execute([$cpf]);
        $dados = $result->fetch(PDO::FETCH_ASSOC);
        return $dados ? $dados['idprofessor'] : null;
    }
}
?>
