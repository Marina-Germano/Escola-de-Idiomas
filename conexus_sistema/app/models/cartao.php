<?php
    require_once "config/conexao.php";

    class Cartao {
        private $pdo;

        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

        public function cadastrar($idaluno, $nome_titular, $bandeira, $ultimos_digitos, $numero_cartao, $validade_mes, $validade_ano) {
            $numero_criptografado = md5($numero_cartao); // criptografia MD5
            $result = $this->pdo->prepare("INSERT INTO cartao VALUES (null, ?, ?, ?, ?, ?, ?, ?)");
            return $result->execute([$idaluno, $nome_titular, $bandeira, $ultimos_digitos, $numero_criptografado, $validade_mes, $validade_ano]);
        }

        public function alterar($idcartao, $idaluno, $nome_titular, $bandeira, $ultimos_digitos, $numero_cartao, $validade_mes, $validade_ano) {
            $numero_criptografado = md5($numero_cartao); // criptografia MD5
            $result = $this->pdo->prepare("UPDATE cartao SET idaluno = ?, nome_titular = ?, bandeira = ?, ultimos_digitos = ?, numero_criptografado = ?, validade_mes = ?, validade_ano = ? WHERE idcartao = ?");
            return $result->execute([$idaluno, $nome_titular, $bandeira, $ultimos_digitos, $numero_criptografado, $validade_mes, $validade_ano, $idcartao]);
        }

        public function excluir($id) {
            $result = $this->pdo->prepare("DELETE FROM cartao WHERE idcartao = ?");
            return $result->execute([$id]);
        }

        public function listarTodos() {
            $result = $this->pdo->query("SELECT * FROM cartao");
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }

        public function listarId($id) {
            $result = $this->pdo->prepare("SELECT * FROM cartao WHERE idcartao = ?");
            $result->execute([$id]);
            return $result->fetch(PDO::FETCH_ASSOC);
        }
    }
?>
