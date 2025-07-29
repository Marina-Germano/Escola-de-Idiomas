<?php
    require_once __DIR__ . '/../config/conexao.php';

    class EmprestimoMaterial {
        private $pdo;

        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($idaluno, $idmaterial, $data_emprestimo, $data_prevista_devolucao, $data_devolvido = null, $status = 'emprestado', $observacoes = null, $valor_multa = 0.00) {
            $result = $this->pdo->prepare("INSERT INTO emprestimo_material VALUES (null, ?, ?, ?, ?, ?, ?, ?)");
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

        public function listarTodo() {
            $result = $this->pdo->query("SELECT * FROM emprestimo_material");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarTodos() {
            $sql = "SELECT 
                        em.idemprestimo,
                u.nome AS nome_aluno,
                m.titulo AS titulo_material,
                em.data_emprestimo,
                em.data_prevista_devolucao,
                em.data_devolvido,
                em.valor_multa,
                em.status
            FROM emprestimo_material em
            INNER JOIN aluno a ON em.idaluno = a.idaluno
            LEFT JOIN usuario u ON a.idusuario = u.idusuario
            INNER JOIN material m ON em.idmaterial = m.idmaterial";

            $stmt = $this->pdo->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }


        public function listarId($id) {
            $result = $this->pdo->prepare("SELECT * FROM emprestimo_material WHERE idemprestimo = ?");
            $result->execute([$id]);
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }
?>
