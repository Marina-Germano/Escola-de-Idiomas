<?php
require_once __DIR__ . '/../config/conexao.php';

class Avaliacao {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($idaluno_turma, $idfuncionario, $descricao, $titulo, $data_avaliacao, $nota, $peso = 1.0, $observacao = null) {
        $result = $this->pdo->prepare("INSERT INTO avaliacao VALUES (null, ?, ?, ?, ?, ?, ?)");
        return $result->execute([$idaluno_turma, $idfuncionario, $descricao, $titulo, $data_avaliacao, $nota, $peso, $observacao]);
    }

    public function alterar($idavaliacao, $idaluno_turma, $idfuncionario, $descricao, $titulo, $data_avaliacao, $nota, $peso = 1.0, $observacao = null) {
        $result = $this->pdo->prepare("UPDATE avaliacao SET idaluno_turma = ?, idfuncionario = ?, descricao = ?, titulo = ?, data_avaliacao = ?, nota = ?, peso = ?, observacao = ? WHERE idavaliacao = ?");
        return $result->execute([$idaluno_turma, $idfuncionario, $descricao, $titulo, $data_avaliacao, $nota, $peso, $observacao, $idavaliacao]);
    }

    public function excluir($id) {
        $result = $this->pdo->prepare("DELETE FROM avaliacao WHERE idavaliacao = ?");
        return $result->execute([$id]);
    }

    public function listarTodos() {
        $result = $this->pdo->query("SELECT * FROM avaliacao");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarId($id) {
        $result = $this->pdo->prepare("SELECT * FROM avaliacao WHERE idavaliacao = ?");
        $result->execute([$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function getAvaliacoesByAlunoAndIdioma($idusuario, $idioma_descricao = null) {
        $sql = "SELECT av.titulo, av.descricao as nome_atividade, av.nota, av.peso, av.data_avaliacao, t.descricao as nome_turma, i.descricao as nome_idioma
                FROM avaliacao av
                JOIN aluno_turma at ON av.idaluno_turma = at.idaluno_turma
                JOIN turma t ON at.idturma = t.idturma
                JOIN idioma i ON t.ididioma = i.ididioma
                JOIN aluno al ON at.idaluno = al.idaluno
                WHERE al.idusuario = :idusuario";

        $params = [':idusuario' => $idusuario];

        if ($idioma_descricao) {
            $sql .= " AND i.descricao = :idioma_descricao";
            $params[':idioma_descricao'] = $idioma_descricao;
        }

        $sql .= " ORDER BY av.data_avaliacao DESC, av.titulo ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function registrar($idavaliacao, $nota, $peso = 1.0) {
        $result = $this->pdo->prepare("UPDATE avaliacao SET nota = ?, peso = ? WHERE idavaliacao = ?");
        return $result->execute([$nota, $peso, $idavaliacao]);
    }
}
?>
