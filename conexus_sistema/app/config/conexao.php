<?php
class Conexao {
    public static function conectar() {
        try {
            $opcoes = [
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

            return new PDO("mysql:host=localhost;dbname=escola_idiomas;charset=utf8mb4", "root", "", $opcoes);
        } catch (PDOException $e) {
            die("Erro na conexão: " . $e->getMessage());
        }
    }
}
?>
