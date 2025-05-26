<?php
namespace MiniERP\Config\BancoDados;
use PDO;

class Conexao
{
    private static $host = "localhost";
    private static $bancoDados = "mini_erp";
    private static $nomeUsuario = "root";
    private static $senha = "";
    public static $conexao;

    public static function conectar(): PDO
    {
        if (self::$conexao == null) {
            self::$conexao = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$bancoDados, self::$nomeUsuario, self::$senha);
        }
        return self::$conexao;
    }
}
?>