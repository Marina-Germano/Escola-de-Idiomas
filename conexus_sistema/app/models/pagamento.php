<?php
    require_once "config/conexao.php";

    class Pagamento {
        private $pdo;

        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($valor, $data_vencimento, $status_pagamento, $data_pagamento, $valor_pago, $observacoes, $multa) {
            $result = $this->pdo->prepare("INSERT INTO pagamento VALUES (null, ?)");
            return $result->execute([$valor, $data_vencimento, $status_pagamento, $data_pagamento, $valor_pago, $observacoes, $multa]);
        }

        public function alterar($idaluno, $valor, $data_vencimento, $status_pagamento, $data_pagamento, $valor_pago, $observacoes, $multa) {
            $result = $this->pdo->prepare("UPDATE pagamento SET  nome_arquivo = ?, caminho_arquivo = ?, observacoes = ?,
            status_documento = ? WHERE pagamento = ?");
            return $result->execute([$idaluno, $valor, $data_vencimento, $status_pagamento, $data_pagamento, $valor_pago, $observacoes, $multa]);
        }

        public function excluir($id) {
            $result = $this->pdo->prepare("DELETE FROM pagamento WHERE pagamento = ?");
            return $result->execute([$id]);
        }

        public function listarTodos() {
            $result = $this->pdo->query("SELECT * FROM pagamento");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarId($id) {
            $result = $this->pdo->prepare("SELECT * FROM pagamento WHERE pagamento = ?");
            $result->execute([$id]);
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }
?>
