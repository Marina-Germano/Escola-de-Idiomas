<?php
require_once __DIR__ . '/../config/conexao.php';

class Aluno {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($idusuario, $cep, $rua, $numero, $bairro, $complemento, $responsavel, $tel_responsavel, $situacao = 'ativo') {
        try {
            $stmt = $this->pdo->prepare(
                "INSERT INTO aluno (idusuario, cep, rua, numero, bairro, complemento, responsavel, tel_responsavel, situacao)
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)"
            );
            return $stmt->execute([$idusuario, $cep, $rua, $numero, $bairro, $complemento, $responsavel, $tel_responsavel, $situacao]);
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
            exit;
        }
    }

    public function alterar($idaluno, $cep, $rua, $numero, $bairro, $complemento, $responsavel, $tel_responsavel, $situacao) {
        $stmt = $this->pdo->prepare(
            "UPDATE aluno 
             SET cep = ?, rua = ?, numero = ?, bairro = ?, complemento = ?, responsavel = ?, tel_responsavel = ?, situacao = ? 
             WHERE idaluno = ?"
        );
        return $stmt->execute([$cep, $rua, $numero, $bairro, $complemento, $responsavel, $tel_responsavel, $situacao, $idaluno]);
    }

    public function excluir($idaluno) {
        $stmt = $this->pdo->prepare("DELETE FROM aluno WHERE idaluno = ?");
        return $stmt->execute([$idaluno]);
    }

    public function listarTodos() {
        $stmt = $this->pdo->query(
            "SELECT a.idaluno, u.nome AS nome, u.cpf, u.email, u.telefone, u.data_nascimento, t.descricao AS turma
             FROM aluno a
             JOIN usuario u ON u.idusuario = a.idusuario
             LEFT JOIN aluno_turma at ON at.idaluno = a.idaluno
             LEFT JOIN turma t ON t.idturma = at.idturma"
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarId($idaluno) {
        $stmt = $this->pdo->prepare("SELECT * FROM aluno WHERE idaluno = ?");
        $stmt->execute([$idaluno]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function buscarIdPorUsuario($idusuario) {
        $stmt = $this->pdo->prepare("SELECT idaluno FROM aluno WHERE idusuario = ?");
        $stmt->execute([$idusuario]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? $dados['idaluno'] : null;
    }

    public function buscarIdPorCpf($cpf) {
        $stmt = $this->pdo->prepare(
            "SELECT a.idaluno 
             FROM aluno a 
             JOIN usuario u ON u.idusuario = a.idusuario 
             WHERE u.cpf = ?"
        );
        $stmt->execute([$cpf]);
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);
        return $dados ? $dados['idaluno'] : null;
    }
}
