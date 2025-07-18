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
                idforma_pagamento = ?,
                idaluno = ?,
                valor = ?,
                data_vencimento = ?,
                status_pagamento = ?,
                data_pagamento = ?,
                valor_pago = ?,
                observacoes = ?,
                multa = ?
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

    public function listarPorAluno($idAluno) {
        try {
            $sql = "SELECT * FROM pagamento WHERE idaluno = :idaluno ORDER BY data_vencimento DESC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':idaluno', $idAluno, PDO::PARAM_INT);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $resultados;
        } catch (PDOException $e) {
            error_log("Erro ao listar pagamentos por aluno: " . $e->getMessage());
            return ['error' => "Erro ao acessar os dados de pagamento."];
        }
    }
}
?>
