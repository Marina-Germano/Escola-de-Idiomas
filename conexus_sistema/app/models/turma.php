<?php
    require_once __DIR__ . '/../config/conexao.php';

class Turma {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    
//     public function cadastrar($ididioma, $idnivel, $descricao, $dias_semana, $hora_inicio, $capacidade_maxima, $sala, $imagem, $idfuncionario, $tipo_recorrencia = null) {
//     $result = $this->pdo->prepare("INSERT INTO turma VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
//     return $result->execute([$ididioma, $idnivel, $descricao, $dias_semana, $hora_inicio, $capacidade_maxima, $sala, $imagem, $idfuncionario, $tipo_recorrencia]);
// }

public function cadastrar($ididioma, $idnivel, $descricao, $dias_semana, $hora_inicio, $capacidade_maxima, $sala, $imagem, $idfuncionario, $tipo_recorrencia = null) {
    $sql = "INSERT INTO turma (ididioma, idnivel, descricao, dias_semana, hora_inicio, capacidade_maxima, sala, imagem, idfuncionario, tipo_recorrencia)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$ididioma, $idnivel, $descricao, $dias_semana, $hora_inicio, $capacidade_maxima, $sala, $imagem, $idfuncionario, $tipo_recorrencia]);
}

    public function alterar($idturma, $ididioma, $idnivel, $descricao, $dias_semana, $hora_inicio, $capacidade_maxima, $sala, $imagem, $idfuncionario, $tipo_recorrencia = null) {
    $result = $this->pdo->prepare("UPDATE turma SET ididioma = ?, idnivel = ?, descricao = ?, dias_semana = ?, hora_inicio = ?, capacidade_maxima = ?, sala = ?, imagem = ?, idfuncionario = ?, tipo_recorrencia = ? WHERE idturma = ?");
    return $result->execute([$ididioma, $idnivel, $descricao, $dias_semana, $hora_inicio, $capacidade_maxima, $sala, $imagem, $idfuncionario, $tipo_recorrencia, $idturma]);
}

    public function excluir($id) {
        $result = $this->pdo->prepare("DELETE FROM turma WHERE idturma = ?");
        return $result->execute([$id]);
    }

public function listarTurma() {
    $sql = "SELECT t.*, n.descricao AS nivel, i.descricao AS idioma FROM turma t
            LEFT JOIN nivel n ON t.idnivel = n.idnivel
            LEFT JOIN idioma i ON t.ididioma = i.ididioma";
    $result = $this->pdo->query($sql);
    return $result->fetchAll(PDO::FETCH_ASSOC);
}

    public function listarTodos() {
        $result = $this->pdo->query("SELECT * FROM turma");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

     public function listarId($id) {
        $result = $this->pdo->prepare("SELECT * FROM turma WHERE idturma = ?");
        $result->execute([$id]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

public function buscarPorId($idturma) {
    $sql = "SELECT t.*, i.nome AS idioma, n.nome AS nivel 
            FROM turma t
            JOIN idioma i ON t.ididioma = i.ididioma
            JOIN nivel n ON t.idnivel = n.idnivel
            WHERE t.idturma = :idturma";
    $stmt = $this->pdo->prepare($sql);
    $stmt->bindParam(':idturma', $idturma, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
public function getIdiomasByAluno($idusuario) {
    $sql = "SELECT DISTINCT i.descricao AS nome_idioma
            FROM aluno_turma at
            JOIN aluno a ON a.idaluno = at.idaluno
            JOIN turma t ON t.idturma = at.idturma
            JOIN idioma i ON i.ididioma = t.ididioma
            WHERE a.idusuario = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$idusuario]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

}
?>
