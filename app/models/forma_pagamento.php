<?php
    require_once "config/conexao.php";

    class FormaPagamento {
        private $pdo;

        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($forma_pagamento) {
            $result = $this->pdo->prepare("INSERT INTO forma_pagamento VALUES (null, ?)");
            return $result->execute([$forma_pagamento]);
        }

        public function alterar($idforma_pagamento, $forma_pagamento) {
            $result = $this->pdo->prepare("UPDATE forma_pagamento SET forma_pagamento = ? WHERE idforma_pagamento = ?");
            return $result->execute([$forma_pagamento, $idforma_pagamento]);
        }

        public function excluir($id) {
            $result = $this->pdo->prepare("DELETE FROM forma_pagamento WHERE idforma_pagamento = ?");
            return $result->execute([$id]);
        }

        public function listarTodos() {
            $result = $this->pdo->query("SELECT * FROM forma_pagamento");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarId($id) {
            $result = $this->pdo->prepare("SELECT * FROM forma_pagamento WHERE idforma_pagamento = ?");
            $result->execute([$id]);
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }
?>
