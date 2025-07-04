<?php
require_once "config/conexao.php";

class CalendarioAula {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar(
        $data_aula,
        $hora_inicio,
        $hora_fim,
        $idprofessor,
        $idturma,
        $idmaterial,
        $sala = null,
        $observacoes = null,
        $status_aula = "Agendada",
        $link_reuniao = null,
        $aula_extra = false,
        $recorrente = false
    ) {
        $result = $this->pdo->prepare("INSERT INTO calendario_aula 
            (data_aula, hora_inicio, hora_fim, idprofessor, idturma, idmaterial, sala, observacoes, status_aula, link_reuniao, aula_extra, recorrente) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $result->execute([
            $data_aula,
            $hora_inicio,
            $hora_fim,
            $idprofessor,
            $idturma,
            $idmaterial,
            $sala,
            $observacoes,
            $status_aula,
            $link_reuniao,
            $aula_extra,
            $recorrente
        ]);
    }

    public function alterar(
        $idaula,
        $data_aula,
        $hora_inicio,
        $hora_fim,
        $idprofessor,
        $idturma,
        $idmaterial,
        $sala = null,
        $observacoes = null,
        $status_aula = "Agendada",
        $link_reuniao = null,
        $aula_extra = false,
        $recorrente = false
    ) {
        $result = $this->pdo->prepare("UPDATE calendario_aula SET
            data_aula = ?, hora_inicio = ?, hora_fim = ?, idprofessor = ?, idturma = ?, idmaterial = ?, sala = ?, observacoes = ?, status_aula = ?, link_reuniao = ?, aula_extra = ?, recorrente = ?
            WHERE idaula = ?");
        return $result->execute([
            $data_aula,
            $hora_inicio,
            $hora_fim,
            $idprofessor,
            $idturma,
            $idmaterial,
            $sala,
            $observacoes,
            $status_aula,
            $link_reuniao,
            $aula_extra,
            $recorrente,
            $idaula
        ]);
    }

    public function excluir($idaula) {
        $result = $this->pdo->prepare("DELETE FROM calendario_aula WHERE idaula = ?");
        return $result->execute([$idaula]);
    }

    public function listarTodos() {
        $result = $this->pdo->query("SELECT * FROM calendario_aula");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarId($idaula) {
        $result = $this->pdo->prepare("SELECT * FROM calendario_aula WHERE idaula = ?");
        $result->execute([$idaula]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }
}
?>
