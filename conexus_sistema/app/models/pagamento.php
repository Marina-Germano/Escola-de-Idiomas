<?php
require_once __DIR__ . '/../config/conexao.php';

class Pagamento {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function cadastrar($idforma_pagamento, $idaluno, $valor, $data_vencimento, $status_pagamento, $data_pagamento, $valor_pago, $observacoes, $multa = 0.00) {
        $result = $this->pdo->prepare("INSERT INTO pagamento
            (idforma_pagamento, idaluno, valor, data_vencimento, status_pagamento, data_pagamento, valor_pago, observacoes, multa)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        return $result->execute([
            $idforma_pagamento, $idaluno, $valor, $data_vencimento, $status_pagamento,
            $data_pagamento, $valor_pago, $observacoes, $multa]);
    }

    public function alterar($idpagamento, $idforma_pagamento, $idaluno, $valor, $data_vencimento, $status_pagamento, $data_pagamento, $valor_pago, $observacoes, $multa = 0.00) {
        $result = $this->pdo->prepare("UPDATE pagamento SET
                idforma_pagamento = ?, idaluno = ?, valor = ?, data_vencimento = ?, status_pagamento = ?, data_pagamento = ?, valor_pago = ?, nobservacoes = ?, nmulta = ?
            WHERE idpagamento = ?");
        return $result->execute([
            $idforma_pagamento, $idaluno, $valor, $data_vencimento, $status_pagamento,
            $data_pagamento, $valor_pago, $observacoes, $multa, $idpagamento]);
    }

    public function excluir($idpagamento) {
        $result = $this->pdo->prepare("DELETE FROM pagamento WHERE idpagamento = ?");
        return $result->execute([$idpagamento]);
    }

    public function listarTodos() {
        $result = $this->pdo->query("SELECT * FROM pagamento");
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarId($idpagamento) {
        $result = $this->pdo->prepare("SELECT * FROM pagamento WHERE idpagamento = ?");
        $result->execute([$idpagamento]);
        return $result->fetch(PDO::FETCH_ASSOC);
    }

    public function getHistoricoPagamentos($idaluno, $ididioma) {
        error_log("DEBUG Pagamento::getHistoricoPagamentos - idaluno: " . $idaluno . ", ididioma: " . $ididioma);
        try {
            $sql = "SELECT p.data_pagamento, p.observacoes AS descricao, p.valor, p.status_pagamento AS status, p.data_vencimento
                FROM pagamento p
                JOIN aluno_turma at ON p.idaluno = at.idaluno
                JOIN turma t ON at.idturma = t.idturma
                WHERE p.idaluno = :idaluno AND t.ididioma = :ididioma
                ORDER BY p.data_vencimento DESC;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idaluno', $idaluno, PDO::PARAM_INT);
            $stmt->bindParam(':ididioma', $ididioma, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("DEBUG Pagamento::getHistoricoPagamentos - Resultados encontrados: " . count($results));
            return $results;
        } catch (PDOException $e) {
            error_log("Erro ao buscar histórico de pagamentos: " . $e->getMessage());
            return [];
        }
    }

    public function getTotalPago($idaluno, $ididioma) {
        error_log("DEBUG Pagamento::getTotalPago - idaluno: " . $idaluno . ", ididioma: " . $ididioma);
        try {
            $sql = "SELECT SUM(p.valor_pago) AS total_pago
                FROM pagamento p
                JOIN aluno_turma at ON p.idaluno = at.idaluno
                JOIN turma t ON at.idturma = t.idturma
                WHERE p.idaluno = :idaluno AND t.ididioma = :ididioma AND p.status_pagamento = 'pago';";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idaluno', $idaluno, PDO::PARAM_INT);
            $stmt->bindParam(':ididioma', $ididioma, PDO::PARAM_INT); // CORRIGIDO: de :ididioma para :ididioma
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $total = $resultado['total_pago'] ?: 0.00;
            error_log("DEBUG Pagamento::getTotalPago - Total pago: " . $total);
            return $total;
        } catch (PDOException $e) {
            error_log("Erro ao calcular total pago: " . $e->getMessage());
            return 0.00;
        }
    }

    public function getTotalPendente($idaluno, $ididioma) {
        error_log("DEBUG Pagamento::getTotalPendente - idaluno: " . $idaluno . ", ididioma: " . $ididioma);
        try {
            $sql = "SELECT SUM(p.valor) AS total_pendente
                FROM pagamento p
                JOIN aluno_turma at ON p.idaluno = at.idaluno
                JOIN turma t ON at.idturma = t.idturma
                WHERE p.idaluno = :idaluno AND t.ididioma = :ididioma AND p.status_pagamento = 'pendente';";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idaluno', $idaluno, PDO::PARAM_INT);
            $stmt->bindParam(':ididioma', $ididioma, PDO::PARAM_INT); // CORRIGIDO: de :ididioma para :ididioma
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $total = $resultado['total_pendente'] ?: 0.00;
            error_log("DEBUG Pagamento::getTotalPendente - Total pendente: " . $total);
            return $total;
        } catch (PDOException $e) {
            error_log("Erro ao calcular total pendente: " . $e->getMessage());
            return 0.00;
        }
    }

    public function listarPorAluno($idaluno) {
        try {
            $sql = "SELECT * FROM pagamento WHERE idaluno = :idaluno ORDER BY data_vencimento DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idaluno', $idaluno, PDO::PARAM_INT);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $resultados;
        } catch (PDOException $e) {
            error_log("Erro ao listar pagamentos por aluno: " . $e->getMessage());
            return ['error' => "Erro ao acessar os dados de pagamento."];
        }
    }

    public function getPagamentosPendentes($idaluno, $ididioma) {
        error_log("DEBUG Pagamento::getPagamentosPendentes - idaluno: " . $idaluno . ", ididioma: " . $ididioma);
        try {
            $sql = "SELECT p.data_vencimento AS vencimento, p.observacoes AS descricao, p.valor
                FROM pagamento p
                JOIN aluno_turma at ON p.idaluno = at.idaluno
                JOIN turma t ON at.idturma = t.idturma
                WHERE p.idaluno = :idaluno AND t.ididioma = :ididioma AND p.status_pagamento = 'pendente'
                ORDER BY p.data_vencimento ASC;";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idaluno', $idaluno, PDO::PARAM_INT);
            $stmt->bindParam(':ididioma', $idIdioma, PDO::PARAM_INT); // CORRIGIDO: de :idIdioma para :ididioma
            $stmt->execute();
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            error_log("DEBUG Pagamento::getPagamentosPendentes - Resultados encontrados: " . count($results));
            return $results;
        } catch (PDOException $e) {
            error_log("Erro ao buscar pagamentos pendentes: " . $e->getMessage());
            return [];
        }
    }
}
?>
