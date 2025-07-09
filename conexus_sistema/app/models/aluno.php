<?php
require_once "config/conexao.php";

class Aluno {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($idusuario, $cep, $rua, $numero, $bairro, $complemento, $responsavel, $tel_responsavel, $situacao = 'ativo') {
        $result = $this->pdo->prepare(
            "INSERT INTO aluno (idusuario, cep, rua, numero, bairro, complemento, responsavel, tel_responsavel, situacao)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        return $result->execute([$idusuario, $cep, $rua, $numero, $bairro, $complemento, $responsavel, $tel_responsavel, $situacao]);
    }

    public function alterar($idaluno, $cep, $rua, $numero, $bairro, $complemento, $responsavel, $tel_responsavel, $situacao) {
        $result = $this->pdo->prepare(
            "UPDATE aluno SET cep = ?, rua = ?, numero = ?, bairro = ?, complemento = ?, responsavel = ?, tel_responsavel = ?, situacao = ?
            WHERE idaluno = ?"
        );
        return $result->execute([$cep, $rua, $numero, $bairro, $complemento, $responsavel, $tel_responsavel, $situacao, $idaluno]);
    }

    public function excluir($id) {
        $result = $this->pdo->prepare("DELETE FROM aluno WHERE idaluno = ?");
        return $result->execute([$id]);
    }

    public function listarTodos() {
        // Boa prática: listar os campos
        $result = $this->pdo->query(
            "SELECT a.*, u.nome, u.cpf, u.email, u.telefone, u.data_nascimento
            FROM aluno a JOIN usuario u ON u.idusuario = a.idusuario"
        );
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarId($id) {
        $result = $this->pdo->prepare(
            "SELECT a.*, u.nome, u.cpf, u.email, u.telefone, u.data_nascimento
            FROM aluno a
            JOIN usuario u ON u.idusuario = a.idusuario WHERE a.idaluno = ?"
        );
        $result->execute([$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarIdPorUsuario($idusuario) {
        $result = $this->pdo->prepare("SELECT idaluno FROM aluno WHERE idusuario = ?");
        $result->execute([$idusuario]);
        $dados = $result->fetch(PDO::FETCH_ASSOC);
        return $dados ? $dados['idaluno'] : null;
    }

    public function buscarIdPorCpf($cpf) {
    $result = $this->pdo->prepare("SELECT a.idaluno FROM aluno a JOIN usuario u ON u.idusuario = a.idusuario WHERE u.cpf = ?");
    $result->execute([$cpf]);
    $dados = $result->fetch(PDO::FETCH_ASSOC);
    return $dados ? $dados['idaluno'] : null;
    }
}
?>
