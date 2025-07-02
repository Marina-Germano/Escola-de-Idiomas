<?php
    require_once "config/conexao.php";

    class EmprestimoMaterial {
        private $pdo;

        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($idaluno, $idmaterial, $data_emprestimo, $data_prevista_devolucao, $data_devolvido, $status = 'Disponível', $observacoes = null, $valor_multa = 0.00) {
            $result = $this->pdo->prepare("INSERT INTO emprestimo_material VALUES (null, ?, ?, ?, ?, ?, ?, ?, ?)");
            return $result->execute([$idaluno, $idmaterial, $data_emprestimo, $data_prevista_devolucao, $data_devolvido, $status, $observacoes, $valor_multa]);
        }

        public function alterar($idemprestimo, $idaluno, $idmaterial, $data_emprestimo, $data_prevista_devolucao, $data_devolvido, $status, $observacoes = null, $valor_multa = 0.00) {
            $result = $this->pdo->prepare("UPDATE emprestimo_material SET idaluno = ?, idmaterial = ?, data_emprestimo = ?, data_prevista_devolucao = ?, data_devolvido = ?, status = ?, observacoes = ?, valor_multa = ? WHERE idemprestimo = ?");
            return $result->execute([$idaluno, $idmaterial, $data_emprestimo, $data_prevista_devolucao, $data_devolvido, $status, $observacoes, $valor_multa, $idemprestimo]);
        }

        public function excluir($id) {
            $result = $this->pdo->prepare("DELETE FROM emprestimo_material WHERE idemprestimo = ?");
            return $result->execute([$id]);
        }

        public function listarTodos() {
            $result = $this->pdo->query("SELECT * FROM emprestimo_material");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarId($id) {
            $result = $this->pdo->prepare("SELECT * FROM emprestimo_material WHERE idemprestimo = ?");
            $result->execute([$id]);
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }
?>
