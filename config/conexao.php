<?php
class Conexao
{
    private static $name = "diego165_pet";
    private static $host = "localhost";
    private static $user = "root";
    private static $pass = "";
    private static $con = null;

    public static function conectar()
    {
        if (self::$con == null) {
            try {
                self::$con = new PDO("mysql:host=" . self::$host .
                    ";dbname=" . self::$name, self::$user, self::$pass);
                self::$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                //Parar a execução
                die("Erro ao conectar com o banco de dados");
            }
        }
        //retornar a conexão
        return self::$con;
    }

    public static function desconectar()
    {
        self::$con = null;
    }
}
