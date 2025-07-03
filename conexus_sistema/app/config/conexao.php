
<?php
    class Conexao{
        public static function conectar(){
            try{
                return new PDO("mysql:host=localhost; dbname=escola_idiomas", "root", "root");
            }
            catch (PDOException $e){
                die("Erro: " .$e -> getMessage());
            }
        }
    }
// Testando a conexão
$conexao = Conexao::conectar();
echo "Conexão bem-sucedida!";
?>
