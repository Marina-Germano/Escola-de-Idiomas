<?php
require_once "config/conexao.php";

class Funcionario {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($idusuario, $cargo) {
        $result = $this->pdo->prepare(
            "INSERT INTO funcionario (idusuario, cargo) VALUES (?, ?)"
        );
        return $result->execute([$idusuario, $cargo]);
    }

    public function alterar($idfuncionario, $cargo) {
        $result = $this->pdo->prepare(
            "UPDATE funcionario SET cargo = ? WHERE idfuncionario = ?"
        );
        return $result->execute([$cargo, $idfuncionario]);
    }

    public function excluir($id) {
        $result = $this->pdo->prepare("DELETE FROM funcionario WHERE idfuncionario = ?");
        return $result->execute([$id]);
    }

    public function listarTodos() {
        $result = $this->pdo->query("SELECT f.*, u.nome, u.email, u.cpf, u.telefone FROM funcionario f JOIN usuario u ON u.idusuario = f.idusuario");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarId($id) {
        $result = $this->pdo->prepare("SELECT f.*, u.nome, u.email, u.cpf, u.telefone FROM funcionario f JOIN usuario u ON u.idusuario = f.idusuario WHERE f.idfuncionario = ?");
        $result->execute([$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarIdPorUsuario($idusuario) {
        $result = $this->pdo->prepare("SELECT idfuncionario FROM funcionario WHERE idusuario = ?");
        $result->execute([$idusuario]);
        $dados = $result->fetch(PDO::FETCH_ASSOC);
        return $dados ? $dados['idfuncionario'] : null;
    }

    public function buscarIdPorCpf($cpf) {
    $result = $this->pdo->prepare("SELECT idfuncionario FROM funcionario WHERE cpf = ?");
    $result->execute([$cpf]);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['idfuncionario'] : null;
}

}
?>
